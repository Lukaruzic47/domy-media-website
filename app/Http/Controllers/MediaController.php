<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index($project_id, Request $request)
    {
        $amount = $request->amount;
        $media = Media::where('project_id', $project_id)->orderBy('created_at', 'desc')->take($amount)->get();
        return response()->json([
            'images' => $media,
        ]);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:4096',
            'project_id' => 'required|exists:projects,id|integer'
        ]);

        $projectId = $validated['project_id'];

        foreach ($validated['files'] as $index => $file) {
            $projectPath = 'images/project-' . $projectId . '-media';
            $path = $file->store($projectPath, 'public');

            Media::create([
                'project_id' => $projectId,
                'path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        $media = Media::where('project_id', $projectId)->get();
        return response()->json($media);
    }

    public function destroy($id)
    {
        $image = Media::findOrFail($id);
        $image->delete();

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        dd('Im here');
    }
}
