<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectImage\StoreProjectImageRequest;
use App\Http\Requests\ProjectImage\UpdateProjectImageRequest;
use App\Http\Resources\ProjectImageResource;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        return ProjectImageResource::collection($project->images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectImageRequest $request)
    {
        $image = $request->file('image')->store('images/projects');
        ProjectImage::query()->create([
            'title' => $request->input('title'),
            'image' => $image,
            'project_id' => $request->input('project_id'),
        ]);

        return response()->json([
            'message' => 'Успешно создана'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectImageRequest $request, ProjectImage $projectImage)
    {
        $image = $projectImage->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/projects');
            Storage::delete($projectImage->image);
        }
        $projectImage->update([
            'title' => $request->input('title'),
            'image' => $image,
        ]);

        return response()->json([
            'message' => 'Успешно изменена'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectImage $projectImage)
    {
        Storage::delete($projectImage->image);
        $projectImage->delete();

        return response()->json([
            'message' => 'Успешно удалена',
        ]);
    }
}
