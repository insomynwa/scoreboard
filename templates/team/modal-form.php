<div class="modal" id="form-team-modal">
    <div class="modal-dialog">
        <div class="modal-content bg-gray-1">

        <!-- Modal Header -->
        <div class="modal-header border-gray-5">
            <h4 id="team-modal-title" class="modal-title text-warning">New Team</h4>
            <button type="button" class="close text-gray-4 modal-close-btn" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form action="controller.php" id="form-team" method="post" enctype="multipart/form-data" class="modal-form">
                <div class="form-group">
                    <img id="team-modal-image" src="" class="hide"><br>
                    <label for="team-logo" class="text-gray-4">Logo</label>
                    <input type="file" name="team_logo" id="team-logo" accept="image/*" class="form-control-file text-gray-4 shadow-none">
                </div>
                <div class="form-group">
                    <label for="team-name" class="text-gray-4">Name</label>
                    <input type="text" name="team_name" id="team-name" placeholder="team name" class="form-control shadow-none">
                </div>
                <div class="form-group">
                    <label for="team-desc" class="text-gray-4">Description</label>
                    <textarea name="team_desc" id="team-desc" cols="30" rows="5" class="form-control shadow-none"></textarea>
                </div>
                <input type="hidden" id="team-id" name="team_id" value="0">
                <input type="hidden" id="team-action" name="team_action" value="create">
                <input type="submit" value="Save" class="btn btn-primary" id="team-submit">
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer border-gray-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>