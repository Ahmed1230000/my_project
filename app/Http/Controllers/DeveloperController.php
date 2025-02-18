<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeveloperStoreFormRequest;
use App\Http\Requests\DeveloperUpdateFormRequest;
use App\Http\Resources\DeveloperResource;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $developers = QueryBuilder::for(Developer::class)
                ->allowedIncludes(['units', 'user']) // Assuming a Developer has Units
                ->get();
            return DeveloperResource::collection($developers);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve developers', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve developers', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeveloperStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $developer = Developer::create($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Developer created successfully',
                'data' => DeveloperResource::make($developer)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create developer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create developer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        try {
            $showDeveloper = QueryBuilder::for(Developer::where('id', $developer->id))
                ->allowedIncludes(['units', 'user'])
                ->firstOrFail();
            return DeveloperResource::make($showDeveloper);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve developer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve developer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeveloperUpdateFormRequest $request, Developer $developer)
    {
        try {
            DB::beginTransaction();
            $developer->update($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Developer updated successfully',
                'data' => DeveloperResource::make($developer)
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update developer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update developer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer)
    {
        try {
            DB::beginTransaction();
            $developer->delete();
            DB::commit();
            return response()->json(['message' => 'Developer deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete developer', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete developer', 'error' => $e->getMessage()], 500);
        }
    }
}
