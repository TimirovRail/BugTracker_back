<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BugController extends Controller
{

    public function index()
    {
        $bugs = Bug::with('assignedTo')->get();
        return response()->json($bugs);
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
            'assigned_to' => $request->assignee, // Добавляем назначенного пользователя
        ]);

        return response()->json($bug, 201);
    }

    public function getComments($bugId)
    {
        $bug = Bug::findOrFail($bugId);
        return $bug->comments()->get();
    }

    public function addComment(Request $request, $bugId)
    {
        $bug = Bug::findOrFail($bugId);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = auth()->user()->id;
        $bug->comments()->save($comment);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $comment->attachments()->create(['path' => $path]);
            }
        }

        return response()->json($comment, 201);
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
    public function getAssignedBugs()
    {
        try {
            $user = auth()->user();
            $bugs = Bug::where('assigned_to', $user->id)->get();
            return response()->json($bugs);
        } catch (\Exception $e) {
            \Log::error('Ошибка при загрузке назначенных ошибок: ' . $e->getMessage());
            return response()->json(['error' => 'Произошла ошибка при загрузке данных'], 500);
        }
    }
    public function assignedBugs(Request $request)
    {
        $user = $request->user();
        $bugs = Bug::where('assigned_to', $user->id)->get();
        return response()->json($bugs);
    }
}

