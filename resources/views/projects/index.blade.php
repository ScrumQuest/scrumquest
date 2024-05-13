@extends('template')

@section('content')
<h1>Welcome {{ $user->name }}!</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Project</th>
                <th scope="col">Sprints</th>
                <th scope="col">Weeks in sprint</th>
                <th scope="col">Team member workload</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></td>
                <td>{{ $project->amountOfSprints }}</td>
                <td>{{ $project->weeks_in_sprint }}</td>
                <td>{{ $project->expected_workdays_per_week }} days per week</td>
                <td>
                    <a title="View backlog" href="{{ route('backlogitems.index', [$project]) }}" class="btn btn-primary bi bi-card-list"></a>
                    @if($user->is_admin)
                        <a title="Project collaborators" href="{{ route('collaborators.show', [$project]) }}" class="btn btn-primary bi bi-person-add"></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @can('create', \App\Models\Project::class)
        <a title="Create project" href="/projects/create" class="btn btn-primary bi bi-plus-square"></a>
    @endcan

@endsection
