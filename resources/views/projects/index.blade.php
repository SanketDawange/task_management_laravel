@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="container">
        <h2>Project List <a href="{{ route('projects.create') }}" class="btn btn-primary">Add Project</a></h2>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <ul class="list-group">
            @foreach ($projects as $project)
                <li class="list-group-item">
                    {{ $project->name }}
                    <div class="float-right">
                        <a href="{{ route('projects.edit', ['project' => $project->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="deleteProject({{ $project->id }})">Delete</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        window.deleteProject = function(projectId) {
            if (confirm('Are you sure you want to delete this project?')) {
                $.ajax({
                    type: 'DELETE',
                    url: '/projects/' + projectId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Project deleted successfully.');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error deleting project:', error);
                    }
                });
            }
        };
    </script>
@endsection
