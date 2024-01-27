<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::orderBy('priority')->get();

            $projects = Project::all();
            return view('tasks.index', compact('tasks', 'projects'));
        } catch (\Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching tasks.');
        }
    }

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'project_id' => 'required|exists:projects,id',
            ]);

            Task::create([
                'name' => $request->input('name'),
                'priority' => $request->input('priority'),
                'project_id' => $request->input('project_id')
            ]);

            return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing task: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while storing the task.');
        }
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task->update([
            'name' => $request->input('name'),
            'priority' => $request->input('priority'),
            'project_id' => $request->input('project_id')
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.']);
    }

    public function updatePriorities(Request $request)
    {
        try {
            $draggedId = $request->input('draggedId');
            $droppedId = $request->input('droppedId');

            $draggedTask = Task::find($draggedId);
            $droppedTask = Task::find($droppedId);

            if ($draggedTask && $droppedTask) {
                $tempPriority = $draggedTask->priority;
                $draggedTask->priority = $droppedTask->priority;
                $droppedTask->priority = $tempPriority;

                $draggedTask->save();
                $droppedTask->save();

                return response()->json(['message' => 'Priorities updated successfully.']);
            } else {
                return response()->json(['error' => 'Tasks not found.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating priorities: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating priorities.'], 500);
        }
    }
}
