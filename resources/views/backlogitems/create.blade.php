@extends('template')

@section('content')
    @vite('resources/js/tinymce.js')

    <h1>Create new backlog item for project {{ $project->name }}</h1>
    <form action="{{ route('backlogitems.store', [$project]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label"><strong>Name*</strong></label>
            <input type="text" class="form-control" name="title" required>
            @error('title')
            <div class="invalid-feedback">Please provide a name for the backlog item!</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label"><strong>Description</strong></label>
            <textarea class="editable" name="description"></textarea>
            @error('description')
            <div class="invalid-feedback">Please fill a description for your backlog item!</div>
            @enderror
        </div>
        <button title="Create" type="submit" class="btn btn-primary bi bi-check2-square"></button>
        <a title="Cancel" href="{{ route('backlogitems.index', ['project' => $project]) }}" class="btn btn-warning bi bi-x-square"></a>
    </form>

@endsection
