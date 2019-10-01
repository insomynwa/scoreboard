<div class="modal" id="form-player-modal">
    <div class="modal-dialog">
        <div class="modal-content bg-gray-1">

        <!-- Modal Header -->
        <div class="modal-header border-gray-5">
            <h4 id="player-modal-title" class="modal-title text-warning">New Player</h4>
            <button type="button" class="close text-gray-4 modal-close-btn" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form action="controller.php" id="form-player" method="post" class="modal-form">
                <div class="form-group">
                    <label for="player-name" class="text-gray-4">Name</label>
                    <input type="text" name="player_name" id="player-name" class="form-control shadow-none" placeholder="player name">
                </div>
                <div class="form-group">
                    <label for="player-team" class="text-gray-4">Team</label>
                    <select name="player_team" class="form-control shadow-none" id="player-team">
                        <option value='0'>-</option>
                    </select>
                    <input type="hidden" id="player-id" name="player_id" value="0">
                    <input type="hidden" id="player-action" name="player_action" value="create">
                </div>
                <input type="submit" value="Save" class="btn btn-primary" id="player-submit">
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer border-gray-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>