@php use App\Enum\SprintStatus; @endphp

<div id='{{"backlogitem".$item->id}}' data-id="{{ $item->id }}" class="mb-2"
     @if($sprint->status() !== SprintStatus::Finished)  draggable="true" @endif >
    @php
        $completed = $item->completed;
        if($completed) {
            $bgClass = "bg-success";
        } elseif ($item->onTrack()) {
            $bgClass = "bg-primary";
        } else {
            $bgClass = "bg-warning";
        }
    @endphp
    <div class="card">
        <a class="card-header {{ $bgClass }} bg-gradient text-white" href="{{ route('backlogitems.show', [$item->project, $item]) }}">
            <div class="form-check float-start"
                 style="{{ $item->assignee ? "" : "display: none;" }}">
                <input title="Mark item as done or as in progress"
                       class="form-check-input custom-checkbox"
                       type="checkbox" {{ $completed ? "checked" : "" }}
                       data-project="{{ $item->project_id }}" data-id="{{ $item->id }}"
                       data-onTrack="{{ $item->onTrack() }}"
                       id="done-switch-{{ $item->id }}"
                       {{ $sprint->status() === SprintStatus::Progress ? "" : "disabled" }}>
            </div>

            <p class="float-start m-1 text-white">#{{ $item->project_number }}</p>

            <img title="Assigned to: "
                 class="avatar-link rounded-circle float-end"
                 src="{{ $item->assignee->avatar_link ?? "" }}"
                 style="{{ $item->assignee ? "" : "display: none;" }}">
            <i class="bi bi-person-x float-end"
               style="font-size: 25px; {{ $item->assignee ? "display: none;" : "" }}"></i>
        </a>
        <div class="card-body">
            <p class="card-text">{{ \Illuminate\Support\Str::words($item->title, 15) }}</p>
        </div>
    </div>
</div>
