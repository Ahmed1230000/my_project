<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreFormRequest;
use App\Http\Requests\ProjectUpdateFormRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Fetch all projects
            $projects = QueryBuilder::for(Project::class)
                ->allowedIncludes('unit')
                ->get();
            return response()->json(['data' => ProjectResource::collection($projects)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch projects: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreFormRequest $request)
    {
        try {
            // Create a new project
            $project = Project::create($request->validated());
            return response()->json(['data' => ProjectResource::make($project)], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create project: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            // Find the project by ID
            $project = QueryBuilder::for(Project::where('id', $project->id))
                ->allowedIncludes('unit')->firstOrFail();
            return response()->json(['data' => ProjectResource::make($project)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Project not found: ' . $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateFormRequest $request, string $id)
    {
        try {
            // Find the project by ID
            $project = Project::findOrFail($id);

            // Update the project with validated data
            $project->update($request->validated());

            return response()->json(['data' => ProjectResource::make($project)], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update project: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the project by ID
            $project = Project::findOrFail($id);
            // Delete the project
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete project: ' . $e->getMessage()], 500);
        }
    }
}
