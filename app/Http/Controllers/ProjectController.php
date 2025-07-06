<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Show the project type selection page.
     *
     * @return \Illuminate\View\View
     */
    public function showProjectTypeSelection()
    {
        return view('projects.project-selection-key');
    }

    /**
     * Set the project type in session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setProjectType(Request $request)
    {
        $request->validate([
            'project_type' => 'required|in:transport,fire'
        ]);

        // Store project type in session
        session(['project_type' => $request->project_type]);

        return response()->json([
            'message' => 'تم اختيار نوع المشروع بنجاح',
            'project_type' => $request->project_type
        ]);
    }

    /**
     * Get the current project type from session.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentProjectType()
    {
        return response()->json([
            'project_type' => session('project_type')
        ]);
    }
} 