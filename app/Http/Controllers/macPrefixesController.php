<?php

namespace App\Http\Controllers;

use App\Models\configuredRouters;
use App\Models\Credit;
use App\Models\MacAddress;
use App\Models\macPrefixesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class macPrefixesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $macs = macPrefixesModel::all();
        return view('mac.index', compact('macs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mac.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'prefix' => [
                'required',
                'unique:mac_prefixes,prefix',
                'max:8',
                'regex:/^([0-9A-Fa-f]{2}[:\-]){2}[0-9A-Fa-f]{2}$/'
            ],
        ]);

        $mac = new macPrefixesModel();
        $mac->prefix = strtoupper($request->prefix);
        $mac->status = true;
        $mac->save();

        return redirect()->route('mac.index')->with('success', 'MAC Prefix added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(macPrefixesModel $macPrefixesModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(macPrefixesModel $macPrefixesModel)
    {
        return view('mac.edit', compact('macPrefixesModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, macPrefixesModel $macPrefixesModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(macPrefixesModel $macPrefixesModel)
    {
        //
    }

    /**
     * Deactivete the specified resource from storage.
     */
    public function deactivate(macPrefixesModel $macPrefixesModel)
    {
        $macPrefixesModel->status = false;
        $macPrefixesModel->save();
        return redirect()->route('mac.index')->with('success', 'MAC Prefix deactiveted successfully!');
    }

    /**
     * Activate the specified resource from storage.
     */
    public function activate(macPrefixesModel $macPrefixesModel)
    {
        $macPrefixesModel->status = true;
        $macPrefixesModel->save();
        return redirect()->route('mac.index')->with('success', 'MAC Prefix activated successfully!');
    }

    /**
     * Get all MAC prefixes
     */
    public function getMacPrefixes()
    {
        $macs = macPrefixesModel::where('status', true)->get();
        return response()->json($macs);
    }

    /**
     * Get base MAC
     */

    public function getBaseMac(Request $request)
    {
        try {
            $request->validate([
                'prefix' => ['required', 'regex:/^([0-9A-Fa-f]{2}:){2}[0-9A-Fa-f]{2}$/']
            ]);

            $credit = Credit::first();
            if (!$credit) {
                return response()->json(['message' => 'Credit record not found'], 404);
            }

            $costPerBatch = 1;
            if ($credit->balance < $costPerBatch) {
                return response()->json(['message' => 'Insufficient credits'], 403);
            }

            $prefix = strtoupper($request->prefix);

            DB::beginTransaction();
            try {
                // **Step 1: Lock the batch search to prevent race conditions**
                $unassignedBatchId = DB::table('mac_addresses')
                    ->select('batchid')
                    ->where('mac_address', 'LIKE', "$prefix%")
                    ->where('assigned', false)
                    ->groupBy('batchid')
                    ->havingRaw('COUNT(*) = 8')
                    ->lockForUpdate() // This prevents other transactions from modifying this batch
                    ->value('batchid');

                if ($unassignedBatchId) {
                    $firstMac = MacAddress::where('batchid', $unassignedBatchId)
                        ->orderBy('mac_address', 'asc')
                        ->first();

                    DB::commit();
                    return response()->json([
                        'base_mac' => $firstMac->mac_address,
                        'batch_id' => $unassignedBatchId,
                    ], 200);
                }

                // **Step 2: If no unassigned batch, generate a new one**
                $lastMac = MacAddress::where('mac_address', 'LIKE', "$prefix%")
                    ->orderBy('mac_address', 'desc')
                    ->first();

                $startMac = $lastMac ? $this->incrementMac($lastMac->mac_address) : "$prefix:00:00:00";

                $macAddresses = $this->generateSequentialMacs($startMac, 8);
                if (empty($macAddresses)) {
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to generate MAC addresses'], 500);
                }

                // Re-check database to prevent race conditions
                $existingMacs = MacAddress::whereIn('mac_address', $macAddresses)->pluck('mac_address')->toArray();
                $availableMacs = array_diff($macAddresses, $existingMacs);

                if (empty($availableMacs)) {
                    DB::rollBack();
                    return response()->json(['message' => 'No available MAC addresses in the generated sequence'], 409);
                }

                $batchId = uniqid('batch_', true);

                foreach ($availableMacs as $mac) {
                    MacAddress::create([
                        'mac_address' => $mac,
                        'batchid' => $batchId,
                        'assigned' => false,
                    ]);
                }

                DB::commit();
                return response()->json([
                    'base_mac' => $availableMacs[0],
                    'batch_id' => $batchId,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }


    // Function to generate sequential MAC addresses
    private function generateSequentialMacs($startMac, $count)
    {
        $macs = [];
        $base = hexdec(str_replace(':', '', $startMac));

        for ($i = 0; $i < $count; $i++) {
            // Generate new MAC and ensure proper formatting
            $newMac = strtoupper(implode(':', str_split(str_pad(dechex($base + $i), 12, '0', STR_PAD_LEFT), 2)));
            $macs[] = $newMac;
        }

        return $macs;
    }

    // Function to increment MAC address by 1
    private function incrementMac($mac)
    {
        $macHex = str_replace(':', '', $mac);
        $newMacHex = str_pad(dechex(hexdec($macHex) + 1), 12, '0', STR_PAD_LEFT);
        return strtoupper(implode(':', str_split($newMacHex, 2)));
    }

    /**
     * Assign MAC addresses to a batch
     */
    public function configSuccess(Request $request)
    {
        try {
            $request->validate([
                'batch_id' => 'required|string',
                'serial_number' => 'required|string',
                'router_model' => 'required|int',
            ]);

            $costPerBatch = 1;

            // Get authenticated user
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Find MACs by batch ID
            $macsToAssign = MacAddress::where('batchid', $request->batch_id)
                ->where('assigned', false)
                ->get();

            if ($macsToAssign->isEmpty()) {
                return response()->json(['message' => 'No unassigned MACs found for this batch.'], 404);
            }

            // Check if credit exists
            $credit = Credit::first();
            if (!$credit || $credit->balance < $costPerBatch) {
                return response()->json(['message' => 'Insufficient credits to perform this operation.'], 400);
            }

            DB::beginTransaction();
            try {
                // Mark MACs as assigned
                foreach ($macsToAssign as $mac) {
                    $mac->assigned = true;
                    $mac->save();
                }

                // Store configured router with the user ID
                configuredRouters::create([
                    'router_model' => $request->router_model,
                    'serial_number' => $request->serial_number,
                    'mac_batch' => $request->batch_id,
                    'configured_by' => $user->id, // Track user who did the configuration
                ]);

                // Deduct credits
                $credit->decrement('balance', $costPerBatch);

                DB::commit();

                return response()->json([
                    'message' => 'MAC addresses assigned successfully.',
                ], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'message' => 'Failed to assign MAC addresses.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
