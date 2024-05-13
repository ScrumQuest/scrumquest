@extends('template')

@section('content')
    <div class="text-center bor">
        <img class="logo col-lg-10 pt-4" src="/img/logo.png" />
        @if($user)
            <h3 class="pt-3 font-weight-bold">Welcome to Scrum Quest, {{ $user->name }}!</h3>
        @else
            <h3 class="pt-3 font-weight-bold">Welcome to Scrum Quest!</h3>
        @endif
    </div>

    <div class="mx-3 my-2 py-2" style="border-top: 1px dotted #aaa; position: relative;">
        <div class="py-3 text-start">
            <p>ScrumQuest is an innovative web-based tool that not only introduces you to the thrilling world of Scrum but also forms an integral part of an exciting scientific study! Are you ready to embark on an adventure that combines the power of planning, collaboration, and continuous improvement?</p>
            <br/>
            <p>As part of this unique scientific study, ScrumQuest offers you a one-of-a-kind opportunity to learn and experience Scrum through hands-on planning and instant feedback. Delve into the principles and practices of this cutting-edge agile framework used by teams worldwide to tackle complex challenges and achieve remarkable results. While you immerse yourself in this journey of discovery, your progress and insights will be contributing to advancing the understanding of agile methodologies. Unleash your potential and develop valuable skills in project management, teamwork, and adaptability. Embrace the excitement of growth and let ScrumQuest empower you to take charge of your learning journey today! Are you ready to embrace the ScrumQuest challenge and unlock your full potential? Let the adventure begin!</p>
        </div>
        @if($user)
            <a title="Login" href="{{ route('projects.index') }}" class="btn btn-primary">Projects</a>
        @else
            <a title="Login" href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @endif
    </div>
@endsection
