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

            // Ensure unique MACs before proceeding
            do {
                // Generate random starting MAC
                $startMac = $this->generateRandomMac($prefix);

                // Generate a batch of 8 MACs
                $macAddresses = $this->generateSequentialMacs($startMac, 8);

                // Check if any of these MACs already exist in the database
                $existingMacs = MacAddress::whereIn('mac_address', $macAddresses)->exists();
            } while ($existingMacs); // If any exist, regenerate

            return response()->json([
                'base_mac' => $macAddresses[0],
                'macs' => $macAddresses,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Assign MAC addresses to a batch
     */
    public function configSuccess(Request $request)
    {
        try {
            $request->validate([
                'serial_number' => 'required|string',
                'router_model' => 'required|int',
                'base_mac' => ['required', 'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/'], // Expect only base MAC
            ]);

            $costPerBatch = 1;

            // Get authenticated user
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Check credit balance
            $credit = Credit::first();
            if (!$credit || $credit->balance < $costPerBatch) {
                return response()->json(['message' => 'Insufficient credits.'], 400);
            }

            // Generate all 8 MAC addresses starting from base MAC
            $macAddresses = $this->generateSequentialMacs($request->base_mac, 8);

            // Create batch ID (not storing in DB yet)
            $batchId = uniqid('batch_', true);

            DB::beginTransaction();
            try {
                // Store MACs in DB
                foreach ($macAddresses as $mac) {
                    MacAddress::create([
                        'mac_address' => $mac,
                        'batchid' => $batchId,
                        'assigned' => true,
                    ]);
                }

                // Store configured router
                ConfiguredRouters::create([
                    'router_model' => $request->router_model,
                    'serial_number' => $request->serial_number,
                    'mac_batch' => $batchId,
                    'configured_by' => $user->id,
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

    private function generateRandomMac($prefix)
    {
        // Generate a random 6-character suffix (last 3 octets)
        $randomHex = strtoupper(str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));

        // Format as a MAC address
        return "$prefix:" . implode(':', str_split($randomHex, 2));
    }

    // Function to increment MAC address by 1
    private function incrementMac($mac)
    {
        $macHex = str_replace(':', '', $mac);
        $newMacHex = str_pad(dechex(hexdec($macHex) + 1), 12, '0', STR_PAD_LEFT);
        return strtoupper(implode(':', str_split($newMacHex, 2)));
    }
}
