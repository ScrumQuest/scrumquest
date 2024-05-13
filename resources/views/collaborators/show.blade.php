@extends('template')

@section('content')
    <h1>Collaborators for {{ $project->name }}</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->users()->orderBy('name')->get() as $user)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td class="text-start">
                    <p><img class="avatar-link rounded-circle" src="{{ $user->avatar_link ?? "" }}"> {{ $user->name }}
                    </p>
                </td>
                <td class="d-flex justify-content-end">
                        <form action="{{ route('project.supervisor', [$project, $user]) }}" method="post" class="me-1">
                            @csrf
                            @method('PUT')
                            @if($project->supervisors->contains($user))
                                <button title="Remove as supervisor" type="submit"
                                        class="btn btn-warning bi bi-person-down"></button>
                            @else
                                <button title="Make supervisor" type="submit"
                                        class="btn btn-primary bi bi-person-up"></button>
                            @endif
                        </form>
                        <div>
                            <button title="Remove from project" type="button"
                                    class="btn btn-danger bi bi-person-x"
                                    data-bs-toggle="modal" data-bs-target="#deleteCollaborator{{$user->id}}"></button>
                            @include('dialog.delete', ['id' => "deleteCollaborator{$user->id}",
                                                            'title' => 'Delete backlog item',
                                                            'message' => "Are you sure you want to remove this person from the project? All registered absence will be lost and all items planned for this person will be moved back to the project backlog.",
                                                            'action' => route('collaborators.destroy', [$project, $user])])
                        </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Users not in project</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($usersNotInProject as $user)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td class="text-start">
                    <p><img class="avatar-link rounded-circle" src="{{ $user->avatar_link ?? "" }}"> {{ $user->name }}
                    </p>
                </td>
                <td class="d-flex justify-content-end">
                    <form action="{{ route('collaborators.store', [$project]) }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button title="Add to project" type="submit" class="btn btn-primary bi bi-person-add"></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
