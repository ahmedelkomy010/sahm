<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function showProjectTypeSelection()
    {
        return view('projects.type-selection');
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_number' => 'required|string|max:255|unique:projects',
            'project_type' => 'required|in:civil,electrical,mixed',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    public function show(Project $project)
    {
        $project->load('attachments');
        return view('projects.show', compact('project'));
    }

    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'attachment' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('attachment');
        $path = $file->store('project-attachments/' . $project->id, 'public');

        ProjectAttachment::create([
            'project_id' => $project->id,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'تم رفع الملف بنجاح');
    }
} 