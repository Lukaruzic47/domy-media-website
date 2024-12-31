<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index($project_id)
    {
//        $project = Project::findOrFail($project_id);
        $media = Media::where('project_id', $project_id)->get();

        return response()->json([
            'images' => $media
        ]);
    }

    public function upload(Request $request)
    {

        $validated = $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:4096', // Validacija za svaku datoteku
            'id.*' => 'required|string|max:255',
            'project_id.*' => 'required|exists:projects,id|int'
        ]);

        // Provjera da li je korisnik odabrao projekt za upload
        $projectId = $request->input('project_id');
        $project = Project::findOrFail($projectId);

        // Upload datoteka
        foreach ($request->file('files') as $file) {
            $projectPath = 'images/project-' . $projectId . '-media';
            $path = $file->store($projectPath, 'public');
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $size = $file->getSize();

            Media::create([
                'project_id' => $projectId,
                'path' => $path,
                'file_name' => $fileName,
                'mime_type' => $mimeType,
                'size' => $size,
            ]);
        }

        return back()->with('success', 'Media uploaded successfully!');
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
