<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmenityStoreFormRequest;
use App\Http\Requests\AmenityUpdateFormRequest;
use App\Http\Resources\AmenityResource;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            // Retrieve all amenities and return as JSON
            $amenities = Amenity::all();
            return response()->json(['meta' => AmenityResource::collection($amenities)]);
        } catch (QueryException $e) {
            // Log the database error
            Log::error('Database error in AmenityController : ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve amenities.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmenityStoreFormRequest $request): JsonResponse
    {
        try {
            // Create and return the new amenity with a 201 status code
            $amenity = Amenity::create($request->validated());
            return response()->json(['meta' => AmenityResource::make($amenity)], 201);
        } catch (QueryException $e) {
            // Log the database error
            Log::error('Database error in AmenityController : ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create amenity.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Amenity $amenity): JsonResponse
    {
        try {
            // Return the specified amenity as JSON (implicit model binding)
            Amenity::findOrFail($amenity->id);
            return response()->json(['meta' => AmenityResource::make($amenity)]);
        } catch (ModelNotFoundException $e) {
            // Log the error
            Log::error('Model not found in AmenityController : ' . $e->getMessage());
            return response()->json(['error' => 'Amenity not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmenityUpdateFormRequest $request, Amenity $amenity): JsonResponse
    {
        try {
            // Update the amenity with validated data and return as JSON
            $amenity->update($request->validated());
            return response()->json(['meta' => AmenityResource::make($amenity)]);
        } catch (QueryException $e) {
            // Log the database error
            Log::error('Database error in AmenityController : ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update amenity.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity): JsonResponse
    {
        try {
            // Delete the amenity and return a 200 No Content response
            $amenity->delete();
            return response()->json(['message' => 'Amenity deleted successfully.'], 200);
        } catch (QueryException $e) {
            // Log the database error
            Log::error('Database error in AmenityController : ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete amenity.'], 500);
        }
    }
}
