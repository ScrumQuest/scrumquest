@extends('template')

@section('content')
<h1>Exports for project {{ $project->name }}</h1>
<div class="text-start">
    <ul>
        @foreach($exports as $export)
            <li>
                <a href=" {{ route('sprintexports.show', [$project, $export]) }}">{{ $export }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
