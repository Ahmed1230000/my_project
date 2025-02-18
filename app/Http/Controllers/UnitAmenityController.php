<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmenityUnitStoreFormRequest;
use App\Http\Requests\AmenityUnitUpdateFormRequest;
use App\Models\Unit;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use App\Http\Requests\AttachAmenityRequest;
use App\Http\Requests\DetachAmenityRequest;
use App\Http\Resources\AmenityResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitAmenityController extends Controller
{
    /**
     * Display a listing of amenities for a specific unit.
     */
    public function index()
    {
        $amenities = QueryBuilder::for(Amenity::query()) // Fetch all amenities
            ->allowedFilters([
                AllowedFilter::exact('place_type'), // Filter by place_type
                AllowedFilter::partial('name'),     // Filter by name (partial match)
            ])
            ->allowedSorts(['name', 'distance'])  // Sort by name or distance
            ->allowedIncludes(['units'])          // Include related units
            ->get();

        // Return all amenities as a resource collection
        return AmenityResource::collection($amenities);
    }


    /**
     * Attach amenities to a unit.
     */
    public function store(AmenityUnitStoreFormRequest $request, Unit $unit): JsonResponse
    {
        $unit->amenities()->attach($request->validated()['amenity_ids']);

        // Return the updated list of amenities as a resource collection
        return AmenityResource::collection($unit->amenities)
            ->response()
            ->setStatusCode(201);
    }
    public function show(Unit $unit)
    {
        $amenities = QueryBuilder::for($unit->amenities())
            ->allowedFilters([
                AllowedFilter::exact('place_type'), // Filter by place_type
                AllowedFilter::partial('name'),     // Filter by name (partial match)
            ])
            ->allowedSorts(['name', 'distance'])    // Sort by name or distance
            ->allowedIncludes(['units'])            // Include related units
            ->get();

        // Return the amenities as a resource collection
        return AmenityResource::collection($amenities);
    }

    /**
     * Update the amenities for a unit.
     */
    public function update(AmenityUnitUpdateFormRequest $request, Unit $unit)
    {
        $unit->amenities()->sync($request->amenity_ids);

        // Return the updated list of amenities as a resource collection
        return AmenityResource::collection($unit->amenities);
    }

    /**
     * Detach amenities from a unit.
     */

    public function destroy(Request $request, Unit $unit)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'amenity_ids'   => ['required', 'array'],
            'amenity_ids.*' => ['integer', Rule::exists('amenities', 'id')->whereNull('deleted_at')],
        ]);

        // Detach the amenities from the unit
        $unit->amenities()->detach($validatedData['amenity_ids']);

        // Return the updated list of amenities as a resource collection
        return AmenityResource::collection($unit->amenities);
    }
}
