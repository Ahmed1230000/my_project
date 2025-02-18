<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationStoreFormRequest;
use App\Http\Requests\ReservationUpdateFormRequest;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use App\Http\Resources\ReservationResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Use Spatie Query Builder for filtering, sorting, and including relationships
            $reservations = QueryBuilder::for(Reservation::class)
                ->allowedFilters([
                    AllowedFilter::exact('user_id'), // Filter by user_id
                    AllowedFilter::exact('unit_id'), // Filter by unit_id
                    AllowedFilter::partial('payment_method'), // Filter by payment_method (partial match)
                ])
                ->allowedSorts(['payment_date', 'down_payment']) // Sort by payment_date or down_payment
                ->allowedIncludes(['user', 'unit']) // Include user and unit relationships
                ->paginate();

            return ReservationResource::collection($reservations);
        } catch (\Exception $e) {
            // Log the error and return a generic error message
            Log::error('Error fetching reservations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch reservations.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreFormRequest $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validated();

            // Create the reservation
            $reservation = Reservation::create($validatedData);

            // Return the created reservation as a resource
            return (new ReservationResource($reservation))
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            // Log database-related errors
            Log::error('Error creating reservation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create reservation.'], 500);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        try {
            // Use Spatie Query Builder to include relationships dynamically
            $reservation = QueryBuilder::for(Reservation::where('id', $reservation->id))
                ->allowedIncludes(['user', 'unit']) // Include user and unit relationships
                ->firstOrFail();

            // Return the reservation as a resource
            return new ReservationResource($reservation);
        } catch (ModelNotFoundException $e) {
            // Log the error if the reservation is not found
            Log::error('Reservation not found: ' . $e->getMessage());
            return response()->json(['error' => 'Reservation not found.'], 404);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Error fetching reservation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch reservation.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationUpdateFormRequest $request, Reservation $reservation)
    {
        try {
            // Validate the request data
            $validatedData = $request->validated();

            // Update the reservation
            $reservation->update($validatedData);

            // Return the updated reservation as a resource
            return new ReservationResource($reservation);
        } catch (QueryException $e) {
            // Log database-related errors
            Log::error('Error updating reservation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update reservation.'], 500);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        try {
            // Delete the reservation
            $reservation->delete();

            // Return a success message
            return response()->json(['message' => 'Reservation deleted successfully.'], 200);
        } catch (QueryException $e) {
            // Log database-related errors
            Log::error('Error deleting reservation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete reservation.'], 500);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
