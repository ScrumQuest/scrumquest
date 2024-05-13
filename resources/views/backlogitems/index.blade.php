@extends('template')

@section('content')
    <div class="d-flex align-items-end">
        <div>
            <h1>Backlog for {{ $project->name }}</h1>
        </div>
        <div class="ms-auto pe-2 mb-2">
            <a title="Create a new backlog item" href="/projects/{{ $project->id }}/backlogitems/create" class="btn btn-primary bi bi-plus-square"></a>
            @if($project->getNextAvailableSprint() != null)
                <a title="Go to current sprint" href="{{ route('sprints.show', ['project' => $project, 'sprint' => $project->getNextAvailableSprint(), 1]) }}" class="btn btn-primary bi bi-fast-forward"></a>
            @endif
        </div>
    </div>

    <table id="backlogItems" class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col">Sprint</th>
                <th scope="col">Assignee</th>
            </tr>
        </thead>
        <tbody>
        @foreach($backlogItems as $item)
            <tr>
                <th scope="row">{{ $item->project_number }}</th>
                <td class="text-start">
                    <a href="/projects/{{ $project->id }}/backlogitems/{{ $item->id }}">
                        {{ \Illuminate\Support\Str::words($item->title, 15) }}
                    </a>
                </td>
                <td>{{ $item->completed ? "Completed" : "Incomplete" }}</td>
                <td>
                @if(!is_null($item->sprint))
                    <a class="badge rounded-pill text-bg-dark"
                       href="/projects/{{ $project->id }}/sprints/{{ $item->sprint->id }}/week/1">
                        {{ $item->sprint->sprint_number }}
                    </a>
                @endif
                </td>
                <td>{{ $item->assignee->name ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        new DataTable('#backlogItems', {
            info: false,
            paging: false,
            order: [
                [2, 'desc' ],
                [3, 'desc'],
                [0, 'asc' ],
            ]
        });
    </script>
@endsection
