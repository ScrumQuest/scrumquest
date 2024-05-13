@extends('template')

@section('content')
    <div class="text-center bor">
        <img class="logo col-lg-10 pt-4" src="/img/logo.png" />
        <h3 class="pt-3 font-weight-bold">Welcome to Scrum Quest, {{ $user->name }}!</h3>
    </div>

    <div class="mx-3 my-2 py-2" style="border-top: 1px dotted #aaa; position: relative;">
        <div class="py-3 text-start">
            In order to continue to use the ScrumQuest tooling you must consent to the following:
            <ul>
                <li>I give permission for the data that is collected during this study to be used for this scientific research.</li>
                <li>I have read the information brief related to this study and I have had the opportunity to ask questions to the researcher if certain points were not clear.</li>
                <li>I understand that all the information that I supply in relation to this study will be collected in a safe manner, will be published anonymously (if applicable) and therefore will not lead back to me.</li>
                <li>I understand that I can pull out of the study at any time and I do not have to provide a reason for doing so.</li>
                <li>The data is stored for a maximum period of 10 years.</li>
                <li>Participation in this research is voluntary.</li>
                <li>The collected data can be used for open scientific purposes when anonymized.</li>
                <li>I have read and agree with the information in the 'information letter' provided to me separately.</li>
                <li>If you have read the above points and agree to participate in the study please digitally sign this consent form below by inserting today's date and clicking 'I consent'.</li>
            </ul>
        </div>
        <form action="{{ route('consent.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="currentDate" class="form-label">Today's date:</label>
                <input type="string" class="form control form-control-sm" name="currentDate" placeholder="dd-mm-yyyy" value="{{ old('currentDate') }}" />
                @error('currentDate')
                <div class="invalid-feedback" style="display: block">Please fill in the current date</div>
                @enderror
            </div>

            <button title="I consent" type="submit" class="btn btn-primary">I consent</button>
        </form>
    </div>
@endsection
