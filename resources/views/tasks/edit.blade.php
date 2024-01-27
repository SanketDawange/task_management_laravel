@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Task</h2>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Task Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}">
            </div>
            <div class="form-group">
                <label for="priority">Priority:</label>
                <input type="number" class="form-control" id="priority" name="priority" value="{{ $task->priority }}"
                    required>
            </div>
            <div class="form-group">
                <label for="project">Select Project</label>
                <select class="form-control" id="project" name="project_id" required>
                    @foreach ($projects as $project)
                        <option @if ($project->id == $task->project_id) selected @endif value="{{ $project->id }}"> {{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>
@endsection
