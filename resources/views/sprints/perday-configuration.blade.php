<div class="d-flex">
    @if($absenceitems->isEmpty())
        <form action="{{ route('absenceitems.store', [$sprint->project]) }}" method="POST" class="ms-auto">
            @csrf
            <input type="hidden" name="assignee_id" value="{{ $userId }}">
            <input type="hidden" name="day_in_week" value="{{ $day }}">
            <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
            <input type="hidden" name="week_in_sprint" value="{{ $week }}">
            <button type="submit" title="Mark as non-project day" class="btn bi bi-calendar2-x"></button>
        </form>
    @else
        <form action="{{ route('absenceitems.destroy', [$sprint->project, $absenceitems->first()]) }}" method="POST" class="ms-auto">
            @csrf
            @method('DELETE')
            <input type="hidden" name="assignee_id" value="{{ $userId }}">
            <input type="hidden" name="day_in_week" value="{{ $day }}">
            <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
            <input type="hidden" name="week_in_sprint" value="{{ $week }}">
            <button type="submit" title="Remove non-project day" class="btn bi bi-calendar2-check"></button>
        </form>
    @endif
</div>
