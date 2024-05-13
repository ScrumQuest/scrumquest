<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ScrumQuest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-1.13.8/datatables.min.js"></script>

    @vite('resources/scss/app.scss')
</head>

<body>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">

                    <div class="navbar-brand">
                        <a href="/"><img style="height: 2rem" src="/img/logo.png"/></a>
                    </div>
                    <div class="nav-item nav-link">
                        {{ Breadcrumbs::render() }}
                    </div>


                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            @if(Auth::check())
                                <li class="nav-item"><a class="nav-link" href="/projects">Projects</a></li>
                                @include('dialog.help')
                                <li class="nav-item" style="border-left: 1px dotted #aaa"><a class="nav-link" data-bs-toggle="modal" data-bs-target="#helpModal" href="">Help</a></li>
                                @include('users.profile-dropdown')
                            @else
                                <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                            @endif

                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="d-flex">
                    <!-- Page content-->
                    <div class="w-100" style="margin: 50px auto;">
                        <div class="row text-center justify-content-center">
                            <div class="border bg-white pb-2 pt-2"
                                 style="width: {{ $pageWidth ?? '50%' }}; min-height: 250px; box-shadow: 5px 5px 20px rgb(91,91,91); border-radius: 12px;">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @vite('resources/js/app.js')
    </body>
</html>
