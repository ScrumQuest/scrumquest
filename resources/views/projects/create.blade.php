@extends('template')

@section('content')
<h1>Create new project</h1>
<form action="/projects" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label"><strong>Project name*</strong></label>
        <input type="text" class="form-control" name="name" required>
        @error('name')
        <div class="invalid-feedback">Please provide a name for the project.</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="weeks_in_sprint" class="form-label"><strong>Weeks in sprint*</strong></label>
        <input type="number" min="1" max="4" class="form-control" name="weeks_in_sprint" value="2" required>
        @error('weeks_in_sprint')
        <div class="invalid-feedback">Please provide the number of weeks in a sprint.</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="expected_workdays_per_week" class="form-label"><strong>The amount of days per week that team members should spend on the project</strong></label>
        <input type="number" min="1" max="5" class="form-control" name="expected_workdays_per_week" value="5">
        @error('expected_workdays_per_week')
        <div class="invalid-feedback">Please provide the amount of days per week that team members should spend on the project.</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="amount_of_sprints" class="form-label"><strong>Amount of pre-created sprints</strong> - you can always create more later</label>
        <input type="number" min="1" max="16" class="form-control" name="amount_of_sprints" value="1">
        @error('amount_of_sprints')
        <div class="invalid-feedback">Please use a number between 1 and 16.</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="first_sprint_day" class="form-label"><strong>First day of the first sprint*</strong></label>
        <input type="date" id="first_sprint_day" name="first_sprint_day" class="form-control" min="{{ $today }}" value="{{ $today }}" required />
        @error('first_sprint_day')
        <div class="invalid-feedback">Please pick a valid date in dd/mm/yyyy format.</div>
        @enderror
    </div>
    <button title="Create" type="submit" class="btn btn-primary bi bi-check2-square"></button>
    <a title="Cancel" href="{{ route('projects.index') }}" class="btn btn-warning bi bi-x-square"></a>
</form>

@endsection
