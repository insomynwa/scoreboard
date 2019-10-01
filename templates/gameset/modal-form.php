<div class="modal" id="form-gameset-modal">
    <div class="modal-dialog">
        <div class="modal-content bg-gray-1">

        <!-- Modal Header -->
        <div class="modal-header border-gray-5">
            <h4 id="gameset-modal-title" class="modal-title text-warning">New Game Set</h4>
            <button type="button" class="close text-gray-4 modal-close-btn" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form id="form-gameset" action="controller.php" method="post" class="modal-form">
                <div class="form-group">
                    <label for="gameset-gamedraw" class="text-gray-4">Game</label>
                    <select name="gameset_gamedraw" id="gameset-gamedraw" class="form-control shadow-none">
                        <option value='0'>Select a game draw!</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="gameset-setnum" class="text-gray-4">Set:</label>
                    <input type="number" name="gameset_setnum" min="1" class="form-control shadow-none" max="5" id="gameset-setnum" value="1">
                </div>
                <div id="gameset-status-area" class="form-group hide">
                    <label for="gameset-status" class="text-gray-4">Status</label>
                    <select name="gameset_status" class="form-control shadow-none" id="gameset-status">
                        <option value='0'>-</option>
                    </select>
                </div>
                <input type="hidden" id="gameset-prev-status" name="gameset_prev_status" value="1">
                <input type="hidden" id="gameset-id" name="gameset_id" value="0">
                <input type="hidden" id="gameset-action" name="gameset_action" value="create">
                <input type="submit" value="Save" class="btn btn-primary" id="gameset-submit">
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer border-gray-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>