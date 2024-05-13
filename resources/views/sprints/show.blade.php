@php use App\Enum\SprintStatus; @endphp
@extends('template', ['pageWidth' => '99.5%'])

@php
    $today = new \DateTime();
    $today->modify('midnight');
    $plannedStart = new \DateTime($sprint->planned_sprint_start);
    $todayPrintable = $today->format('l d-m');
@endphp



@section('content')
    @vite('resources/js/sprint.show.js')
    <!-- Page content -->
    <div class="ps-1">
        <div class='text-center w-100'>
            <div class="d-flex align-items-center">
                @include('dialog.backlog', ['id' => 'showBacklog',
                                            'unplannedItems' => $unplannedItems,
                                            'project' => $sprint->project])
                <button id="showBacklogButton" title="Show project backlog" type="button"
                        class="btn btn-outline-primary bi bi-card-list"
                        data-project="{{ $sprint->project->id }}">Backlog</button>
                @if($sprint->status() === SprintStatus::Progress)
                    @include('dialog.daily-feedback')
                    <button id="dailyFeedbackButton" title="Show daily feedback" type="button"
                            class="btn btn-outline-success bi bi-arrow-repeat position-relative ms-3"
                            data-bs-toggle="modal" data-bs-target="#dailyFeedbackModal"
                            data-sprint="{{ $sprint->id }}" data-project="{{ $sprint->project->id }}">
                        Feedback
                        <span id="dailyFeedbackCounter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count($dailyFeedback) }}
                    </span>
                    </button>
                @endif

                <div class="w-100">
                    <div class='ms-auto text-center'>
                        <h4>Sprint {{ $sprint->sprint_number }}</h4>
                        <h5>
                            @if($week > 1)
                                <a
                                    class='link-underline link-underline-opacity-0'
                                    href='/projects/{{ $sprint->project->id }}/sprints/{{ $sprint->id }}/week/{{ $week - 1 }}'
                                ><i class="bi bi-chevron-bar-left"></i></a>
                            @endif
                            Week {{ $week }} / {{ $sprint->project->weeks_in_sprint }}
                            @if($week < $sprint->project->weeks_in_sprint)
                                <a
                                    class='link-underline link-underline-opacity-0'
                                    href='/projects/{{ $sprint->project->id }}/sprints/{{ $sprint->id }}/week/{{ $week + 1 }}'
                                ><i class="bi bi-chevron-bar-right"></i></a>
                            @endif
                        </h5>
                    </div>
                </div>
                <button title="Open help screen"
                        data-bs-toggle="modal" data-bs-target="#helpModal"
                        type="button"
                        class="btn btn-outline-primary m-2 bi bi-question-diamond">Help</button>

                @if($sprint->status() === SprintStatus::Planning)
                    @include('dialog.sprint-feedback')
                    @if($sprint->canBeStarted())
                        @if($today == $plannedStart)
                            <button id="sprint-start-button" title="Start sprint"
                                    class="btn btn-outline-success m-2"
                                    data-bs-toggle="modal" data-bs-target="#sprintFeedbackModal"
                                    data-sprint="{{ $sprint->id }}" data-project="{{ $sprint->project->id }}">Start sprint</button>
                        @elseif($today > $plannedStart)
                            <button id="sprint-start-button" title="Start sprint"
                                    class="btn btn-outline-warning m-2"
                                    data-bs-toggle="modal" data-bs-target="#sprintFeedbackModal"
                                    data-sprint="{{ $sprint->id }}" data-project="{{ $sprint->project->id }}">Start sprint (late)</button>
                        @else
                            <button id="sprint-start-button" title="Start sprint"
                                    class="btn btn-outline-warning m-2"
                                    data-bs-toggle="modal" data-bs-target="#sprintFeedbackModal"
                                    data-sprint="{{ $sprint->id }}" data-project="{{ $sprint->project->id }}">Start sprint (early)</button>
                        @endif
                    @else
                        <button id="sprint-start-button" title="There is already another sprint in progress"
                                class="btn btn-outline-secondary m-2" disabled>Start sprint</button>
                    @endif
                @elseif($sprint->status() === SprintStatus::Progress)
                    @include('dialog.sprint-finish')
                    <button title="Finish sprint"
                            class="btn btn-outline-success m-2"
                            data-bs-toggle="modal" data-bs-target="#sprintFinishModal">Finish sprint</button>
                @elseif($sprint->status() === SprintStatus::Finished)
                    <a title="Sprint already finished" class="btn btn-outline-secondary m-2 disabled" aria-disabled="true">Finished sprint</a>
                @endif
            </div>
        </div>
        <div class="grid">
            <div></div> <!-- empty div above usernames -->
            <div></div> <!-- empty div above previous sprint droppable column -->
            @foreach($weekDays as $weekDay)
                <div class='text-center fw-bold card {{ $todayPrintable === $weekDay ? "bg-primary-subtle bg-gradient" : null }}'>
                    {{ $weekDay }}
                </div>
            @endforeach
            <div></div> <!-- empty div above next sprint droppable column -->
            @while($itemsPerDayPerUser->valid())
                @php
                    $user = $itemsPerDayPerUser->current();
                    $itemsPerDay = $itemsPerDayPerUser->getInfo();
                @endphp
                <div class="user-row">
                    <h4 class='align-middle text-center h-100'>{{ $user->name }}</h4>
                </div>
                @if($week > 1)
                    <div data-user="{{ $user->id }}" data-day="previous" data-sprint="{{ $sprint->id }}"
                         data-week="{{ $week - 1}}" data-project="{{ $sprint->project->id }}"
                         class="droppable otherweek text-center">
                        <i class="bi bi-chevron-bar-left"></i>
                    </div>
                @else
                    <div></div>
                @endif
                @foreach($itemsPerDay as $day => $items)
                    <div data-user="{{ $user->id }}" data-day="{{ $day }}" data-sprint="{{ $sprint->id }}"
                         data-week="{{ $week }}" data-project="{{ $sprint->project->id }}" class="droppable">
                        @if($sprint->status() === SprintStatus::Planning)
                            @include('sprints.perday-configuration', ['sprint' => $sprint,
                                                                'absenceitems' => $items['absenceitems'],
                                                                'week' => $week,
                                                                'day' => $day,
                                                                'userId' => $user->id])
                        @endif
                        @if($items['absenceitems']->isNotEmpty())
                            @include('sprints.absenceitem-card', ['item' => $items['absenceitems']->first()])
                        @endif
                        @foreach($items['backlogitems'] as $item)
                            @include('sprints.backlogitem-card', ['item' => $item])
                        @endforeach
                    </div>
                @endforeach
                @if($week < $sprint->project->weeks_in_sprint)
                    <div data-user="{{ $user->id }}" data-day="next" data-sprint="{{ $sprint->id }}"
                         data-week="{{ $week + 1}}" data-project="{{ $sprint->project->id }}"
                         class="droppable otherweek text-center">
                        <i class="bi bi-chevron-bar-right"></i>
                    </div>
                @else
                    <div></div>
                @endif
                @php
                    $itemsPerDayPerUser->next()
                @endphp
            @endwhile
        </div>
    </div>
@endsection
