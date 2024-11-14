<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

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

        $slug = Str::slug($validated['title']);
        $validated['slug'] = $slug;
        $project = Project::create($validated);

        return redirect()->route('projects.edit', $project->slug);
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
        $project = Project::where('slug', $slug)->firstOrFail();

        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $project->update($request->only([
            'title', 'author', 'description', 'date', 'visible', 'category', 'slug', 'youtubeURL', 'metadata'
        ]));

        // Ponovno dodavanje medija ako je priložen novi
        if ($request->hasFile('image')) {
            $project->clearMediaCollection('images'); // Obriši postojeće slike
            $project->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->hasFile('video')) {
            $project->clearMediaCollection('videos'); // Obriši postojeće videozapise
            $project->addMedia($request->file('video'))->toMediaCollection('videos');
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

}
