<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::paginate(12)->onEachSide(2);

        return Inertia::render('Projects', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:projects,title',
            'author' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $counter = 0;

        while (Project::slugExists($validated['slug'])) {
            $validated['slug'] = $validated['slug'] . '-' . ++$counter;
        }

        Project::create($validated);

        return redirect()->route('projects.edit', ['slug' => $validated['slug']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $projectData = Project::where('slug', $slug)->firstOrFail();
        $projectImages = Media::where('project_id', $projectData->id)->get();

        return Inertia::render('Projects/Edit', [
            'project' => array_merge($projectData->toArray(), ['images' => $projectImages]),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'date' => 'nullable|date',
            'category' => 'required|string|max:255',
            'thumbnail' => $request->input('thumbnail') === 'delete' ? 'string' :
                ($request->hasFile('thumbnail') ? 'file|mimes:jpg,jpeg,png,webp|max:4096' : 'nullable'),
            'description' => 'nullable|string|max:2000',
            'youtube_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'tiktok_url' => 'nullable|string|max:255',
            'main_video' => $request->input('main_video') === 'delete' ? 'string' :
                ($request->hasFile('main_video') ? 'file|mimes:mp4,mov,avi,wmv|max:20480' : 'nullable'),
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $counter = 0;

        while (Project::slugExists($validated['slug'])) {
            $validated['slug'] = $validated['slug'] . '-' . ++$counter;
        }

        try {
            if ($request->hasFile('main_video')) {
                if ($project->main_video) {
                    Storage::disk('public')->delete($project->main_video);
                }

                $projectPath = 'videos/project-' . $project->id;
                $path = $request->file('main_video')->store($projectPath, 'public');
                $validated['main_video'] = $path;

            }
            if ($request->hasFile('thumbnail')) {
                if ($project->thumbnail) {
                    Storage::disk('public')->delete($project->thumbnail);
                }

                $projectPath = 'thumbnail/project-' . $project->id;
                $path = $request->file('thumbnail')->store($projectPath, 'public');
                $validated['thumbnail'] = $path;
            }
            if ($request->input('thumbnail') === 'delete' && $project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
                $validated['thumbnail'] = null;
            }
            if ($request->input('main_video') === 'delete' && $project->main_video) {
                Storage::disk('public')->delete($project->main_video);
                $validated['main_video'] = null;
            }

//            dd($validated);
            $project->update($validated);

            return redirect()
                ->route('projects.edit', $project)
                ->with('success', 'Project updated successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update project: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    public
    function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

}
