<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class UnitFavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorite units.
     */
    public function index()
    {
        try {
            $favorites = QueryBuilder::for(Auth::user()->favoriteUnits()->getQuery())
                ->allowedIncludes(['favoriteUnits','favoritedByUsers'])
                ->get();

            return UnitResource::collection($favorites);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve favorite units', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve favorite units', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created favorite unit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();
            Auth::user()->favoriteUnits()->syncWithoutDetaching([$request->unit_id]);
            DB::commit();

            return response()->json([
                'message' => 'Unit added to favorites'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add unit to favorites', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to add unit to favorites', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a specific favorite unit.
     */
    public function show($unitId)
    {
        try {
            $favorite = QueryBuilder::for(Auth::user()->favoriteUnits()->where('unit_id', $unitId))
                ->allowedIncludes(['favoriteUnits', 'favoritedByUsers'])
                ->firstOrFail();

            return UnitResource::make($favorite);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve favorite unit', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve favorite unit', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove a unit from the user's favorites.
     */
    public function destroy($unitId)
    {
        try {
            DB::beginTransaction();
            Auth::user()->favoriteUnits()->detach($unitId);
            DB::commit();

            return response()->json(['message' => 'Unit removed from favorites']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to remove unit from favorites', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to remove unit from favorites', 'error' => $e->getMessage()], 500);
        }
    }
}
