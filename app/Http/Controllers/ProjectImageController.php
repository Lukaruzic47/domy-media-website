<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function getImages(Project $project)
    {
        return response()->json([
            'images' => $project->projectMedia
        ]);
    }

    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store("images/project-{$project->id}", 'public');

            $media = new Media();
            $media->path = $path;
            $media->save();

            $project->projectMedia()->attach($media->id);
            $uploadedImages[] = $media;
        }

        return response()->json([
            'images' => $uploadedImages,
        ]);
    }

    public function destroy(Media $image)
    {
        // Delete the actual file
        Storage::disk('public')->delete($image->path);

        // Delete the database record and pivot table entry
        $image->projects()->detach();
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully!']);
    }
}
