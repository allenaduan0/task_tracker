<?php

namespace App\Http\Controllers;

use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Notifications\TaskCommentAddedNotification;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'assignedUser'])->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $users = User::where('role', 'member')->get();
        return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:to_do,in_progress,done',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $taskData = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            $taskData['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $task = Task::create($taskData);

        $assignedUser = User::find($request->assigned_to);
        $assignedUser->notify(new TaskAssignedNotification($task));

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignedUser', 'comments.user']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:to_do,in_progress,done',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $taskData = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            $taskData['attachment'] = $request->file('attachment')->store('attachment', 'public');
        }

        $task->update($taskData);

        $assignedUser = $task->assignedUser;
        $assignedUser->notify(new TaskStatusUpdatedNotification($task));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }

    public function storeComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        if ($user->id !== $task->assigned_to && !in_array($user->role, ['admin', 'manager', 'member'])) {
            return back()->with('error', 'You are not allowed to comment on this task.');
        }

        $task->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        if (auth()->id() !== $task->assigned_to) {
            $task->assignedUser->notify(new TaskCommentAddedNotification($task));
        }

        if ($user->role === 'member') {
            $adminsAndManagers = User::whereIn('role', ['admin', 'manager'])->get();

            foreach ($adminsAndManagers as $adminOrManager) {
                $adminOrManager->notify(new TaskCommentAddedNotification($task));
            }
        }

        return back()->with('success', 'Comment added successfully.');
    }

    public function myTasks()
    {
        $user = auth()->user();

        if ($user->role !== 'member') {
            abort(403, 'Access denied');
        }

        $tasks = Task::with(['project'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->get();

        return view('tasks.my', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:to_do,in_progress,done',
        ]);

        $user = auth()->user();

        if (in_array($user->role, ['admin', 'manager'])) {
            $task->update(['status' => $request->status]);
            return back()->with('success', 'Task status updated successfully.');
        }

        if ($user->role === 'member' && $task->assigned_to === $user->id) {
            $task->update(['status' => $request->status]);

            $adminsAndManagers = User::whereIn('role', ['admin', 'manager'])->get();

            foreach ($adminsAndManagers as $recipient) {
                $recipient->notify(new TaskStatusUpdatedNotification($task));
            }

            return back()->with('success', 'Task status updated successfully.');
        }

        return back()->with('error', 'You are not authorized to update this task.');
    }
}
