<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitStoreFormRequest;
use App\Http\Requests\UnitUpdateFormRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $units = QueryBuilder::for(Unit::class)
                ->allowedIncludes(['user', 'developer', 'location', 'project'])->get();
            return UnitResource::collection($units);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve units', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve units', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create the unit
            $unit = Unit::create($request->validated());

            // Handle file uploads if present
            if ($request->hasFile('photo')) {
                $unit->addMultipleMediaFromRequest(['photo'])
                    ->each(function ($photo) {
                        $photo->toMediaCollection('unit_photo');
                    });
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'Unit created successfully',
                'data' => UnitResource::make($unit)
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error
            Log::error('Failed to create unit', ['error' => $e->getMessage()]);

            // Return error response
            return response()->json([
                'message' => 'Failed to create unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        try {
            $showUnit = QueryBuilder::for(Unit::where('id', $unit->id))
                ->allowedIncludes(['user', 'developer', 'location', 'project'])->firstOrFail();
            return UnitResource::make($showUnit);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve unit', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve unit', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitUpdateFormRequest $request, Unit $unit)
    {
        try {
            DB::beginTransaction();

            // Update the unit
            $unit->update($request->validated());

            // Handle file uploads if present
            if ($request->hasFile('photo')) {
                $unit->clearMediaCollection('unit_photo') // Clear existing media
                    ->addMultipleMediaFromRequest(['photo'])
                    ->each(function ($photo) {
                        $photo->toMediaCollection('unit_photo');
                    });
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'Unit updated successfully',
                'data' => UnitResource::make($unit)
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error
            Log::error('Failed to update unit', ['error' => $e->getMessage()]);

            // Return error response
            return response()->json([
                'message' => 'Failed to update unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        try {
            DB::beginTransaction();
            $unit->delete();
            DB::commit();
            return response()->json(['message' => 'Unit deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete unit', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete unit', 'error' => $e->getMessage()], 500);
        }
    }
}
