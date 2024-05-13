@extends('template')

@section('content')
    <h1>Update sprint #{{ $sprint->sprint_number }} for project {{ $project->name }}</h1>
    <form action="{{ route('sprints.update', [$project, $sprint]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="sprint_number" class="form-label"><strong>Sprint number*</strong></label>
            <input type="number" class="form-control" name="sprint_number" value="{{ $sprint->sprint_number }}" required/>
            @error('sprint_number')
            <div class="invalid-feedback">Please provide number for the sprint!</div>
            @enderror
        </div>

        <button title="Update" type="submit" class="btn btn-primary bi bi-check2-square"></button>
        <a title="Cancel" href="{{ route('projects.show', [$project]) }}" class="btn btn-warning bi bi-x-square"></a>
    </form>
@endsection
