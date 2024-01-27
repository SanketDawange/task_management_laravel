
@extends('layouts.app')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="container">
        <h2>Task List
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add task</a>
            <div class="form-group" style="display:inline-block; margin-left: 10px;">
                <select id="project-filter" class="form-control">
                    <option value="">Filter by project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </h2>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <ul id="task-list" class="list-group">
            @foreach ($tasks as $task)
                <li class="list-group-item" data-id="{{ $task->id }}" data-project="{{ $task->project ? $task->project->id : '' }}" draggable="true">
                    <div class="float-left">
                        <span>{{ $loop->index + 1 }}. {{ $task->name }}</span>
                        @if ($task->project)
                            <span class="badge badge-secondary">{{ $task->project->name }}</span>
                        @endif
                    </div>
                    <div class="float-right">
                        <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="deleteTask({{ $task->id }})">Delete</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        $(document).ready(function() {
            var draggedTaskId;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('#task-list li').on('dragstart', function(event) {
                draggedTaskId = $(this).data('id');
            });

            $('#task-list li').on('dragover', function(event) {
                event.preventDefault();
            });

            $('#task-list li').on('drop', function(event) {
                var droppedTaskId = $(this).data('id');
                swapTasksOrder(draggedTaskId, droppedTaskId);
            });

            $('#project-filter').change(function() {
                var selectedProjectId = $(this).val();
                filterTasksByProject(selectedProjectId);
            });

            function swapTasksOrder(draggedId, droppedId) {
                $.ajax({
                    type: 'POST',
                    url: '/tasks/update-priorities',
                    data: {
                        draggedId: draggedId,
                        droppedId: droppedId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log('Priorities updated successfully.');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error updating priorities:', error);
                    }
                });
            }

            function filterTasksByProject(projectId) {
                $('#task-list li').each(function() {
                    var taskProjectId = $(this).data('project');

                    if (projectId && taskProjectId != projectId) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            }

            window.deleteTask = function(taskId) {
                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/tasks/' + taskId,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            console.log('Task deleted successfully.');
                            window.location.reload();
                        },
                        error: function(error) {
                            console.error('Error deleting task:', error);
                        }
                    });
                }
            };
        });
    </script>
@endsection
