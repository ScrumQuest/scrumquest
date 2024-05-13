@php use App\Enum\SprintStatus; @endphp
@extends('template')

@section('content')
    <div class="d-flex align-items-center">
        <h1 class="ms-auto">Project: {{ $project->name }}</h1>
        <a title="View backlog" href="/projects/{{ $project->id }}/backlogitems"
           class="ms-auto btn btn-primary bi bi-card-list"></a>
        @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
            <a title="Project collaborators" href="{{ route('collaborators.show', [$project]) }}"
               class="ms-1 btn btn-primary bi bi-person-add"></a>
        @endif
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Sprint</th>
            <th scope="col">Planned start</th>
            <th scope="col">Status</th>
            <th scope="col">&nbsp;</th>
            <th scope="col">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->sprints as $sprint)
            @php
                $plannedStart = new \DateTime($sprint->planned_sprint_start);
            @endphp
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                    <a href="/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/week/1">Sprint {{ $sprint->sprint_number }}</a>
                </td>
                <td>{{ $plannedStart->format('d-m-Y') }}</td>
                @if($sprint->status() === SprintStatus::Planning)
                    <td><a href="/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/week/1"
                           class="btn btn-outline-warning">Planned</a></td>
                @elseif($sprint->status() === SprintStatus::Progress)
                    <td><a href="/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/week/1"
                           class="btn btn-outline-success">In progress</a></td>
                @elseif($sprint->status() === SprintStatus::Finished)
                    <td><a href="/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/week/1"
                           class="btn btn-outline-success">Finished</a></td>
                @endif
                <td>
                    @can('delete', $sprint)
                        @php
                            $sprintId = $sprint->id;
                        @endphp
                        <button title="Delete sprint" type="button"
                                {{ $sprint->status() === SprintStatus::Planning ? "" : "disabled" }}
                                class="btn btn-danger bi bi-trash"
                                data-bs-toggle="modal" data-bs-target="#deleteSprint{{ $sprintId }}"></button>
                        @include('dialog.delete', ['id' => "deleteSprint${sprintId}",
                                                        'title' => 'Delete sprint',
                                                        'message' => 'Are you sure you want to delete this sprint? All items planned in the sprint will be moved to the project backlog.',
                                                        'action' => route('sprints.destroy', [$project, $sprint])])
                    @endcan
                </td>
                <td>
                    @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                        <a title="Edit" href="{{ route('sprints.edit', [$project, $sprint]) }}"
                           class="btn btn-warning bi bi-pencil"></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center ">
        <form action="{{ route('sprints.store', [$project]) }}" class="m-1" method="post">
            @csrf
            <button title="Add sprint" type="submit" class="btn btn-primary bi bi-plus-square"></button>
        </form>
    </div>
@endsection
