<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 me-2" id="exampleModalLabel">Project backlog</h1>
                <a title="View backlog" href="{{ route('backlogitems.index', [$project]) }}" class="btn btn-outline-primary bi bi-card-list"></a>
                <p class="ms-2">From here you can drag items in your sprint</p>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="noBacklogItems"
                   style="{{ $unplannedItems->isEmpty() ? "" : "display: none;" }}">
                    There are currently no items in your backlog.
                </p>
                <div id="backlogItems"
                     class="container-fluid">
                    <div id="unplannedItems" class="row">
                        @foreach($unplannedItems as $unplannedItem)
                            <div class="col-md-3 backlog-container">
                                @include('sprints.backlogitem-card', ['item' => $unplannedItem, 'onTrack' => true])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
