<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'needs' => 'nullable|string|max:1000',
            'monthly_budget' => 'required|numeric|min:0',
        ]);

        $project = Project::create($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', '¡Proyecto creado correctamente!');
    }

    public function show(Project $project)
{
    $project->load('requirements');

    return view('projects.show', compact('project'));
}
}