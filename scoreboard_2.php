<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Setup</title>

<!-- <script src="js/jquery-3.3.1.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<link href="font-awesome/css/all.css" rel="stylesheet">
<!-- <script src="js/jquery-3.3.1.min.map"></script> -->

<style>
  .show-row{
    display:block;
  }
  .hide-row{
    display:none;
  }
  .show{
    display:block;
  }
  .hide{
    display:none;
  }
</style>
</head>
<body>
<div id="score-title" class="score-area mb-3 mt-3 container">
  <div class="row">
    <div class="col-lg-12">
      <h4 class="mb-2"><i class="fab fa-readme"></i> Scoreboard</h4>
      <form id="form-scoreboard" action="controller.php" method="get" class="form-inline">
        <label for="scoreboard-gamedraw">Game</label>
        <select name="scoreboard_gamedraw" id="scoreboard-gamedraw" class="form-control-sm mx-2">
          <option value='0'>Select a game draw!</option>
        </select>
        <label for="scoreboard-gameset" class="ml-2">Set</label>
        <select name="scoreboard_gameset" id="scoreboard-gameset" class="form-control-sm mx-2">
          <option value='0'>Select a game set!</option>
        </select>
        <button id="scoreboard-render-btn" class="btn btn-sm btn-success">Render</button>
        <button data-lock="0" id="scoreboard-filter-lock" class="btn btn-sm btn-warning ml-2"><i class="fas fa-unlock text-white"></i></button>
      </form>
    </div>
  </div>
</div>
<div id="score-a-area" class="score-area mb-3 container">
  <div class="row">
    <div class="col-lg-12">
      <h5 id="score-team-a-title">Team A</h5>
      <form id="form-score-a" action="controller.php" method="post" class="row">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="table-primary">
              <th scope="col">Timer</th>
              <th scope="col">1</th>
              <th scope="col">2</th>
              <th scope="col">3</th>
              <th scope="col">4</th>
              <th scope="col">5</th>
              <th scope="col">6</th>
              <th scope="col">Total</th>
              <th scope="col">Set Pts</th>
              <th scope="col">Desc</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" class="form-control" name="score_a_timer" id="score-a-timer"></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt1" id="score-a-pt1" value=""></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt2" id="score-a-pt2" value=""></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt3" id="score-a-pt3" value=""></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt4" id="score-a-pt4" value=""></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt5" id="score-a-pt5" value=""></td>
              <td><input type="text" class="form-control score-a-input-cls" name="score_a_pt6" id="score-a-pt6" value=""></td>
              <td><input type="text" class="form-control" name="score_a_total" id="score-a-total" value=""></td>
              <td><input type="text" class="form-control" name="score_a_setpoints" id="score-a-setpoints" value=""></td>
              <td><input type="text" class="form-control" name="score_a_desc" id="score-a-desc" value=""></td>
              <td>
                <input type="hidden" name="score_a_gamedraw_id" id="score-a-gamedraw-id" value="">
                <input type="hidden" name="score_a_gameset_id" id="score-a-gameset-id" value="">
                <input type="hidden" name="score_a_id" id="score-a-id" value="">
                <input type="hidden" name="score_action" value="update-a">
                <input type="submit" value="Update" class="btn btn-primary">
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
  <div id="score-a-control" class="btn-group btn-group-sm">
    <button class="btn btn-outline-dark" disabled>Timer</button>
    <button class="btn btn-outline-dark" id="score-a-timer-play" disabled="disabled">Play</button>
    <button class="btn btn-outline-dark" id="score-a-timer-pause" disabled="disabled">Pause</button>
  </div>
</div>
<div id="score-b-area" class="score-area mb-5 container">
  <div id="score-b-area" class="row">
    <div class="col-lg-12">
      <h5 id="score-team-b-title">Team B</h5>
      <form id="form-score-b" action="controller.php" method="post" class="row">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="table-warning">
              <th scope="col">Timer</th>
              <th scope="col">1</th>
              <th scope="col">2</th>
              <th scope="col">3</th>
              <th scope="col">4</th>
              <th scope="col">5</th>
              <th scope="col">6</th>
              <th scope="col">Total</th>
              <th scope="col">Set Pts</th>
              <th scope="col">Desc</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" class="form-control" name="score_b_timer" id="score-b-timer"></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt1" id="score-b-pt1" value=""></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt2" id="score-b-pt2" value=""></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt3" id="score-b-pt3" value=""></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt4" id="score-b-pt4" value=""></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt5" id="score-b-pt5" value=""></td>
              <td><input type="text" class="form-control score-b-input-cls" name="score_b_pt6" id="score-b-pt6" value=""></td>
              <td><input type="text" class="form-control" name="score_b_total" id="score-b-total" value=""></td>
              <td><input type="text" class="form-control" name="score_b_setpoints" id="score-b-setpoints" value=""></td>
              <td><input type="text" class="form-control" name="score_b_desc" id="score-b-desc" value=""></td>
              <td>
                <input type="hidden" name="score_b_gamedraw_id" id="score-b-gamedraw-id" value="">
                <input type="hidden" name="score_b_gameset_id" id="score-b-gameset-id" value="">
                <input type="hidden" name="score_b_id" id="score-b-id" value="">
                <input type="hidden" name="score_action" value="update-b">
                <input type="submit" value="Update" class="btn btn-primary">
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
  <div id="score-b-control" class="btn-group btn-group-sm">
    <button class="btn btn-outline-dark" disabled>Timer</button>
    <button class="btn btn-outline-dark" id="score-b-timer-play" disabled="disabled">Play</button>
    <button class="btn btn-outline-dark" id="score-b-timer-pause" disabled="disabled">Pause</button>
  </div>
</div>
<div class="container mb-3">
  <div class="row">
    <div class="col-lg-6">
      <h4><i class="fas fa-trophy"></i> Game Draw</h4>
      <button type="button" id="gamedraw-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Game Draw</button>
      <table id="tbl-gamedraw" class="table table-sm table-hover"><tr><td>0 game draw. buat dulu!</td></tr></table>
      <!-- <h4>Create Game Set</h4>
      <p id="form-gameset-msg"></p>
      <form id="form-gameset" action="controller.php" method="post">
        <div class="form-group">
          <label for="gameset-gamedraw">Game</label>
          <select name="gameset_gamedraw" id="gameset-gamedraw" class="form-control">
            <option value='0'>Select a game draw!</option>
          </select>
        </div>
        <div class="form-group">
          <label for="gameset-setnum">Set:</label>
          <input type="number" name="gameset_setnum" min="1" class="form-control" max="5" id="gameset-setnum" value="1">
        </div>
          <input type="hidden" id="gameset-action" name="gameset_action" value="create">
          <input type="submit" value="Create" class="btn btn-primary" id="gameset-submit">
      </form> -->
    </div>
    <div class="col-lg-6">
      <h4><i class="fas fa-ellipsis-v"></i> Game Set</h4>
      <button type="button" id="gameset-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Game Set</button>
      <table id="gameset-table" class="table table-sm table-hover"></table>
    </div>
  </div>
</div>
<div class="container mb-3">
  <div class="row">
    <div class="col-lg-6">
      <h4><i class="fas fa-flag"></i> Team</h4>
      <button type="button" id="team-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Team</button>
      <table id="tbl-team" class="table table-sm table-hover"><tr><td>0 team. buat dulu!</td></tr></table>
    </div>
    <div class="col-lg-6">
      <h4><i class="fas fa-users"></i> Player</h4>
      <button type="button" id="player-create-btn"  class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Player</button>
      <table id="tbl-player" class="table table-sm table-hover"><tr><td>0 player. buat dulu!</td></tr></table>
    </div>
  </div>
</div>
<!-- <div class="container mb-3">
  <div class="row">
    <div class="col-lg-3">
      <h4>Create Team</h4>
      <form action="tools/uploader.php" id="form-team" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="team-logo">Logo</label>
          <input type="file" name="team_logo" id="team-logo" accept="image/*" class="form-control-file">
        </div>
        <div class="form-group">
          <label for="team-name">Name</label>
          <input type="text" name="team_name" id="team-name" placeholder="team name" class="form-control">
        </div>
        <div class="form-group">
          <label for="team-desc">Description</label>
          <textarea name="team_desc" id="team-desc" cols="30" rows="5" class="form-control"></textarea>
        </div>
        <input type="hidden" name="team_action" value="create">
        <input type="submit" value="Create" class="btn btn-primary">
      </form>
    </div>
    <div class="col-lg-3">
      <h4>Create Player</h4>
      <form action="controller.php" id="form-player" method="post">
        <div class="form-group">
          <label for="player-name">Name</label>
          <input type="text" name="player_name" id="player-name" class="form-control" placeholder="player name">
        </div>
        <div class="form-group">
          <label for="player-team">Team</label>
          <select name="player_team" class="form-control" id="player-team">
            <option value='0'>-</option>
          </select>
          <input type="hidden" name="player_action" value="create">
        </div>
        <input type="submit" value="Create" class="btn btn-primary">
      </form>
    </div>
    <div class="col-lg-6">
      <h4>Game Draws</h4>
      <p id="form-gamedraw-msg"></p>
      <form id="form-gamedraw" action="controller.php" method="post">
        <div class="form-group">
          <label for="gamedraw-num">Draws</label>
          <input type="number" name="gamedraw_num" id="gamedraw-num" min="1" max="100" value="1" class="form-control">
        </div>
        <div id="gamedraw-radio-area" class="form-group">
        </div>
        <div class="form-group">
          <div class="gamedraw-team-opt-area-cls">
            <label for="gamedraw-team-a">Team A:</label>
            <select name="gamedraw_team_a" id="gamedraw-team-a" class="gamedraw-team-cls form-control"></select>
          </div>
          <div class="gamedraw-player-opt-area-cls hide">
            <label for="gamedraw-player-a">Player A:</label>
            <select name="gamedraw_player_a" id="gamedraw-player-a" class="form-control gamedraw-player-cls"></select>
          </div>
        </div>
        <div class="form-group">
          <div class="gamedraw-team-opt-area-cls">
            <label for="gamedraw-team-b">Team B:</label>
            <select name="gamedraw_team_b" id="gamedraw-team-b" class="form-control gamedraw-team-cls"></select>
          </div>
          <div class="gamedraw-player-opt-area-cls hide">
            <label for="gamedraw-player-b">Player B:</label>
            <select name="gamedraw_player_b" id="gamedraw-player-b" class="form-control gamedraw-player-cls"></select>
          </div>
        </div>
        <input type="hidden" id="gamedraw-action" name="gamedraw_action" value="create">
        <input type="submit" value="Create" id="gamedraw-submit" class="btn btn-primary">
      </form>
    </div>
  </div>
</div> -->
<!-- The Modal Game Set -->
<div class="modal" id="form-gameset-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 id="gameset-modal-title" class="modal-title">New Game Set</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="form-gameset" action="controller.php" method="post">
          <div class="form-group">
            <label for="gameset-gamedraw">Game</label>
            <select name="gameset_gamedraw" id="gameset-gamedraw" class="form-control">
              <option value='0'>Select a game draw!</option>
            </select>
          </div>
          <div class="form-group">
            <label for="gameset-setnum">Set:</label>
            <input type="number" name="gameset_setnum" min="1" class="form-control" max="5" id="gameset-setnum" value="1">
          </div>
            <input type="hidden" id="gameset-id" name="gameset_id" value="0">
            <input type="hidden" id="gameset-action" name="gameset_action" value="create">
            <input type="submit" value="Save" class="btn btn-primary" id="gameset-submit">
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- The Modal Team -->
<div class="modal" id="form-team-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 id="team-modal-title" class="modal-title">New Team</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="controller.php" id="form-team" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <img id="team-modal-image" src="" class="hide"><br>
            <label for="team-logo">Logo</label>
            <input type="file" name="team_logo" id="team-logo" accept="image/*" class="form-control-file">
          </div>
          <div class="form-group">
            <label for="team-name">Name</label>
            <input type="text" name="team_name" id="team-name" placeholder="team name" class="form-control">
          </div>
          <div class="form-group">
            <label for="team-desc">Description</label>
            <textarea name="team_desc" id="team-desc" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <input type="hidden" id="team-id" name="team_id" value="0">
          <input type="hidden" id="team-action" name="team_action" value="create">
          <input type="submit" value="Save" class="btn btn-primary" id="team-submit">
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- The Modal Player -->
<div class="modal" id="form-player-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 id="player-modal-title" class="modal-title">New Player</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="controller.php" id="form-player" method="post">
          <div class="form-group">
            <label for="player-name">Name</label>
            <input type="text" name="player_name" id="player-name" class="form-control" placeholder="player name">
          </div>
          <div class="form-group">
            <label for="player-team">Team</label>
            <select name="player_team" class="form-control" id="player-team">
              <option value='0'>-</option>
            </select>
            <input type="hidden" id="player-id" name="player_id" value="0">
            <input type="hidden" id="player-action" name="player_action" value="create">
          </div>
          <input type="submit" value="Save" class="btn btn-primary" id="player-submit">
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- The Modal Game Draw -->
<div class="modal" id="form-gamedraw-modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 id="gamedraw-modal-title" class="modal-title">New Game Draw</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="form-gamedraw" action="controller.php" method="post">
          <div class="form-group">
            <label for="gamedraw-num">Draws</label>
            <input type="number" name="gamedraw_num" id="gamedraw-num" min="1" max="100" value="1" class="form-control">
          </div>
          <div id="gamedraw-radio-area" class="form-group">
          </div>
          <div class="form-group">
            <div class="gamedraw-team-opt-area-cls">
              <label for="gamedraw-team-a">Team A:</label>
              <select name="gamedraw_team_a" id="gamedraw-team-a" class="gamedraw-team-cls form-control"></select>
            </div>
            <div class="gamedraw-player-opt-area-cls hide">
              <label for="gamedraw-player-a">Player A:</label>
              <select name="gamedraw_player_a" id="gamedraw-player-a" class="form-control gamedraw-player-cls"></select>
            </div>
          </div>
          <div class="form-group">
            <div class="gamedraw-team-opt-area-cls">
              <label for="gamedraw-team-b">Team B:</label>
              <select name="gamedraw_team_b" id="gamedraw-team-b" class="form-control gamedraw-team-cls"></select>
            </div>
            <div class="gamedraw-player-opt-area-cls hide">
              <label for="gamedraw-player-b">Player B:</label>
              <select name="gamedraw_player_b" id="gamedraw-player-b" class="form-control gamedraw-player-cls"></select>
            </div>
          </div>
          <input type="hidden" id="gamedraw-id" name="gamedraw_id" value="create">
          <input type="hidden" id="gamedraw-action" name="gamedraw_action" value="create">
          <input type="submit" value="Create" id="gamedraw-submit" class="btn btn-primary">
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>