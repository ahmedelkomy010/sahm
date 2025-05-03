<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $project = $request->query('project', session('selected_project'));
        
        if ($project) {
            session(['selected_project' => $project]);
        }
        
        $usersCount = User::count();
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('usersCount', 'latestUsers', 'project'));
    }
} 