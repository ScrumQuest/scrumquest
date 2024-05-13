@extends('template')

@section('content')
    <div class="text-center bor">
        <img class="logo col-lg-10 pt-4" src="/img/logo.png" />
        <h3 class="pt-3 font-weight-bold">Login using GitHub</h3>
    </div>

    <div class="mx-3 my-2 py-2 bordert">
        <div class="text-center py-3">

            <a href="{{ url('auth/github') }}" class="px-2">
                <img class="login_method"
                     src="https://www.freepnglogos.com/uploads/512x512-logo-png/512x512-logo-github-icon-35.png"
                     alt="GitHub login">
            </a>
        </div>
    </div>
@endsection
