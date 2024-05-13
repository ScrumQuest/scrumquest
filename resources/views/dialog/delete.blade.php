<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button title="Cancel" type="button" class="btn btn-secondary bi bi-x-square" data-bs-dismiss="modal"></button>
                <form action="{{ $action }}" class="m-1" method="post">
                    @csrf
                    @method('DELETE')
                    <button title="Delete" type="submit" class="btn btn-danger bi bi-trash"></button>
                </form>
            </div>
        </div>
    </div>
</div>
