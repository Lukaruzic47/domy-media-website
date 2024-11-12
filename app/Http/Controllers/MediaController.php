<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::paginate(30)->onEachSide(2);

        return Inertia::render('Media', [
            'media' => $media,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:10240', // Validacija za svaku datoteku
        ]);

        // Provjera da li je korisnik odabrao projekt za upload
        $projectId = $request->input('project_id');
        $project = $projectId ? Project::find($projectId) : null;

        foreach ($request->file('files') as $file) {
            // Kreiranje ili dodavanje medija
            if ($project) {
                $project->addMedia($file)->toMediaCollection('images');
            } else {
                $media = new Media();
                $media->addMedia($file)->toMediaCollection('general');
            }
        }

        return back()->with('success', 'Media uploaded successfully!');
    }
}
