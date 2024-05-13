<div class="modal fade" id="sprintFinishModal" tabindex="-1" aria-labelledby="sprintFinishModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Would you like to finish the sprint?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <p>This action cannot be undone. All unfinished items will be moved back to the sprint backlog.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back to planning</button>
                <form action="{{ route('sprints.finish', [$sprint->project, $sprint]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <button title="Finish sprint" class="btn btn-success">Finish sprint</button>
                </form>
            </div>
        </div>
    </div>
</div>
