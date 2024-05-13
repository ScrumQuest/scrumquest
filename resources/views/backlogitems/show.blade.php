@extends('template')

@section('content')
    @vite('resources/js/tinymce.js')

    <div class="d-flex mb-3">
        <div>
            <button title="Back" class="m-1 btn btn-warning bi bi-arrow-left" onclick="history.back()"></button>
        </div>
        <div class="w-100">
            <p class="fw-bold text-start w-100 text-center">#{{ $backlogItem->project_number }}</p>
            <p class="text-start w-100 text-center">{{ $backlogItem->title }}</p>
        </div>
        <div>
            <div class="ms-auto d-flex">
                <a title="Edit" href="{{ route('backlogitems.edit', [$project, $backlogItem]) }}"
                   class="m-1 btn btn-warning bi bi-pencil"></a>

                <button title="Delete" type="button"
                        class="btn btn-danger bi bi-trash m-1"
                        data-bs-toggle="modal" data-bs-target="#deleteBacklogItem"></button>
                @include('dialog.delete', ['id' => 'deleteBacklogItem',
                                                'title' => 'Delete backlog item',
                                                'message' => 'Are you sure you want to delete the this backlog item? All content of this item will be lost.',
                                                'action' => route('backlogitems.destroy', [$project, $backlogItem])])
            </div>
        </div>
    </div>

    <div class="d-flex text-start pb-1">
        <div>
            <p><strong>Assignee</strong></p>
            @if($backlogItem->assignee == null)
                <p>Not assigned</p>
            @else
                <p><img class="rounded-circle" width="30" height="30"
                        src="{{ $backlogItem->assignee->avatar_link ?? '' }}"> {{ $backlogItem->assignee->name ?? '' }}
                </p>
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
            <input title="Mark item as done or as in progress"
                   class="form-check-input custom-checkbox"
                   type="checkbox" {{ $backlogItem->completed ? "checked" : "" }}
                   id="completed"
                   disabled>
        </div>
    </div>

    <textarea name="description">{{ $backlogItem->description }}</textarea>

@endsection
