<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreFormRequest;
use App\Http\Requests\LocationUpdateFormRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $locations = QueryBuilder::for(Location::class)
                ->allowedIncludes(['unit', 'user'])
                ->get();
            return LocationResource::collection($locations);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve locations', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve locations', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $location = Location::create($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Location created successfully',
                'data' => LocationResource::make($location)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create location', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create location', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        try {
            $showLocation = QueryBuilder::for(Location::where('id', $location->id))
                ->allowedIncludes(['unit', 'user'])
                ->firstOrFail();
            return LocationResource::make($showLocation);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve location', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve location', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationUpdateFormRequest $request, Location $location)
    {
        try {
            DB::beginTransaction();
            $location->update($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Location updated successfully',
                'data' => LocationResource::make($location)
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update location', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update location', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        try {
            DB::beginTransaction();
            $location->delete();
            DB::commit();
            return response()->json(['message' => 'Location deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete location', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete location', 'error' => $e->getMessage()], 500);
        }
    }
}
