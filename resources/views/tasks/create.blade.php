@extends('layouts.app')

@section('content')
    <h2>Add New Task</h2>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('tasks.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Task name" required>
        </div>
        <div class="form-group">
            <label for="priority">Priority</label>
            <input type="number" class="form-control" id="priority" name="priority" placeholder="Priority" required>
        </div>
        <div class="form-group">
            <label for="project">Select Project</label>
            <select class="form-control" id="project" name="project_id" required>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Task</button>
    </form>
@endsection
