<?php

namespace App\Http\Controllers;

use App\Models\configuredRouters;
use App\Models\Credit;
use App\Models\issuesModel;
use App\Models\routerConfiguration;
use App\Models\routerModel;
use Illuminate\Http\Request;

class routerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routers = configuredRouters::all();
        return view('routers.index', compact('routers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(routerModel $routerModel)
    {
        // $routerModelId = $request->input('router_model_id');
        // $routerModel = routerModel::find($routerModelId);
        return response()->json($routerModel);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(routerModel $routerModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, routerModel $routerModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(routerModel $routerModel)
    {
        //
    }

    public function routermodels()
    {
        $routerModels = routerModel::all();
        return response()->json($routerModels);
    }

    public function routerconfigurations(Request $request)
    {
        $costPerBatch = 1;
        try {
            // Check if credit exists
            $credit = Credit::first();
            if (!$credit || $credit->balance < $costPerBatch) {
                return response()->json(['message' => 'Insufficient credits to perform this operation.'], 400);
            }

            // Validate input parameters
            $validated = $request->validate([
                'router_model_id' => 'required',
                'issue_id' => 'required'
            ]);

            $router_model_id = $validated['router_model_id'];
            $issue_id = $validated['issue_id'];

            // Validate if router model exists
            if (!routerModel::find($router_model_id)) {
                return response()->json(['message' => 'Router model not found'], 404);
            }

            // Validate if issue exists
            if (!issuesModel::find($issue_id)) {
                return response()->json(['message' => 'Issue not found'], 404);
            }

            // fetch router configurations based on the router model ID and issue
            $routerConfigurations = routerConfiguration::where('router_model_id', $router_model_id)
                ->where('issue_id', $issue_id)
                ->get();

            if ($routerConfigurations->isEmpty()) {
                return response()->json(['message' => 'No configurations found for the specified router model and issue'], 404);
            }

            return response()->json($routerConfigurations, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }


    public function getIssues()
    {
        $issuetypes = issuesModel::all();

        return response()->json($issuetypes);
    }
}
