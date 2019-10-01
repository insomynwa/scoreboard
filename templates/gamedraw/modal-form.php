<div class="modal" id="form-gamedraw-modal">
    <div class="modal-dialog">
        <div class="modal-content bg-gray-1">

            <!-- Modal Header -->
            <div class="modal-header border-gray-5">
                <h4 id="gamedraw-modal-title" class="modal-title text-warning">New Game Draw</h4>
                <button type="button" class="close text-gray-4 modal-close-btn" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id="form-gamedraw" action="controller.php" method="post" class="modal-form">
                    <div class="form-group">
                        <label for="gamedraw-num" class="text-gray-4">Draws</label>
                        <input type="number" name="gamedraw_num" id="gamedraw-num" min="1" max="100" value="1" class="form-control shadow-none">
                    </div>
                    <div id="gamedraw-radio-bowstyle-area" class="form-group">
                    </div>
                    <div id="gamedraw-radio-area" class="form-group">
                    </div>
                    <div class="form-group">
                        <div class="gamedraw-team-opt-area-cls">
                            <label for="gamedraw-team-a" class="text-gray-4">Team A:</label>
                            <select name="gamedraw_team_a" id="gamedraw-team-a" class="gamedraw-team-cls form-control shadow-none"></select>
                        </div>
                        <div class="gamedraw-player-opt-area-cls hide">
                            <label for="gamedraw-player-a" class="text-gray-4">Player A:</label>
                            <select name="gamedraw_player_a" id="gamedraw-player-a" class="form-control gamedraw-player-cls shadow-none"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="gamedraw-team-opt-area-cls">
                            <label for="gamedraw-team-b" class="text-gray-4">Team B:</label>
                            <select name="gamedraw_team_b" id="gamedraw-team-b" class="form-control gamedraw-team-cls shadow-none"></select>
                        </div>
                        <div class="gamedraw-player-opt-area-cls hide">
                            <label for="gamedraw-player-b" class="text-gray-4">Player B:</label>
                            <select name="gamedraw_player_b" id="gamedraw-player-b" class="form-control gamedraw-player-cls shadow-none"></select>
                        </div>
                    </div>
                    <input type="hidden" id="gamedraw-id" name="gamedraw_id" value="create">
                    <input type="hidden" id="gamedraw-action" name="gamedraw_action" value="create">
                    <input type="submit" value="Create" id="gamedraw-submit" class="btn btn-primary">
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer border-gray-5">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>