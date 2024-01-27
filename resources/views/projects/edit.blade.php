@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Project - {{ $project->name }}</h2>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('projects.update', ['project' => $project->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Project Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Project</button>
        </form>
    </div>
@endsection
