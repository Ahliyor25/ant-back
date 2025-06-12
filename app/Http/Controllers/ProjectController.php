<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ProjectResource::collection(
            Project::query()
                ->orderByDesc('id')
                ->paginate($request->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $image = $request->file('image')->store('images/projects');
        Project::query()->create([
            'title' => $request->input('title'),
            'image' => $image,
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Успешно создан'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return ProjectResource::make($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $image = $project->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/projects');
            Storage::delete($project->image);
        }
        $project->update([
            'title' => $request->input('title'),
            'image' => $image,
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Успешно изменен'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Storage::delete($project->image);
        $project->deleteImages();
        $project->delete();

        return response()->json([
            'message' => 'Успешно удален',
        ]);
    }
}
