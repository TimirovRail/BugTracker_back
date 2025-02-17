<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BugController extends Controller
{
    public function index()
    {
        return response()->json(Bug::with('user')->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Bug::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,normal,high',
            'status' => 'required|in:new,in_progress,testing,closed',
            'steps_to_reproduce' => 'nullable|string',
            'environment_info' => 'nullable|string',
            'attachments.*' => 'file|max:2048', // Ограничение 2MB на файл
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('bugs', 'public');
                $attachments[] = $path;
            }
        }

        $bug = Bug::create([
        'title' => $request->title,
        'description' => $request->description,
        'severity' => $request->severity,
        'priority' => $request->priority,
        'status' => $request->status,
        'steps_to_reproduce' => $request->steps_to_reproduce,
        'environment_info' => $request->environment_info,
        'attachments' => $attachments,
        'user_id' => auth()->id(),
    ]);

        return response()->json($bug, 201);
    }

    public function update(Request $request, Bug $bug)
    {
        $this->authorize('update', $bug);

        $bug->update($request->all());
        return response()->json($bug);
    }

    public function destroy(Bug $bug)
    {
        $this->authorize('delete', $bug);

        $bug->delete();
        return response()->json(['message' => 'Ошибка удалена']);
    }
}

