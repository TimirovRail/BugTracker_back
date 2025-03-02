<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'planned',
        ]);

        $projects = Project::all();
        return response()->json($projects, 201);
    }
}