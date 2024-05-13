@extends('template')

@section('content')
    @vite('resources/js/tinymce.js')

    <h1>Update backlog item #{{ $backlogItem->project_number }}</h1>
    <form action="{{ route('backlogitems.update', [$project, $backlogItem]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="d-flex text-start pb-1">
            <div>
                <p><strong>Assignee</strong></p>
                @if($backlogItem->assignee == null)
                    <p>Not assigned</p>
                @else
                    <p><img class="rounded-circle" width="30" height="30" src="{{ $backlogItem->assignee->avatar_link ?? '' }}"> {{ $backlogItem->assignee->name ?? '' }}</p>
                @endif
            </div>
            <div class="ms-auto">
                <p><strong>Sprint</strong></p>
                <p>{{ $backlogItem->sprint->id ?? '' }}</p>
            </div>

            <div class="ms-auto">
                <p><strong>Planned</strong></p>
                <p>{{ $plannedFor ?? 'Not planned' }}</p>
            </div>

            <div class="ms-auto">
                <p><strong>Completed</strong></p>
                <input class="form-check-input custom-checkbox"
                       type="checkbox"
                       id="completed"
                       name="completed"
                       {{ $backlogItem->completed ? "checked" : "" }}>
            </div>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label"><strong>Name*</strong></label>
            <input type="text" class="form-control" name="title" value="{{ $backlogItem->title }}" required>
            @error('title')
            <div class="invalid-feedback">Please provide a name for the backlog item!</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label"><strong>Description</strong></label>
            <textarea class="editable" name="description">{{ $backlogItem->description }}</textarea>
            @error('description')
            <div class="invalid-feedback">Please fill a description for your backlog item!</div>
            @enderror
        </div>
        <button title="Update" type="submit" class="btn btn-primary bi bi-check2-square"></button>
        <a title="Cancel" href="{{ route('backlogitems.show', [$project, $backlogItem]) }}" class="btn btn-warning bi bi-x-square"></a>
    </form>

@endsection
