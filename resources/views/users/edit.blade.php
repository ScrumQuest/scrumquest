@extends('template')

@section('content')
    <h1>Edit account details</h1>
    <form action="/profile" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3 mt-3">
            <label for="name" class="col-sm-4 col-form-label"><strong>Display name*</strong></label>
            <div class="col-sm-8">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name"
                    value="{{ old('name') ?? $user->name }}"
                    required
                >
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <button title="Update" type="submit" class="btn btn-primary bi bi-check2-square"></button>
        <a title="Cancel" href="/projects" class="btn btn-warning bi bi-x-square"></a>
    </form>

@endsection
