<div class="modal fade" id="sprintFeedbackModal" tabindex="-1" aria-labelledby="sprintFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">We would like to share some feedback on your plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <ol id="sprint-feedback">

                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back to planning</button>
                <form action="{{ route('sprints.start', [$sprint->project, $sprint]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-primary">Start sprint</button>
                </form>
            </div>
        </div>
    </div>
</div>
