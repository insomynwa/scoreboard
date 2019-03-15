<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Setup</title>

<!-- <script src="js/jquery-3.3.1.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
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
<div id="score-title" class="score-area mb-3 container">
  <div class="row">
    <div class="col-lg-12">
      <h4>Scoreboard</h4>
    </div>
  </div>
</div>
<div id="score-a-area" class="score-area mb-3 container">
  <div class="row">
    <div class="col-lg-12">
      <form id="form-score-a" action="controller.php" method="post" class="row">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="table-primary">
              <th scope="col">Team</th>
              <th scope="col">Timer</th>
              <th scope="col">1</th>
              <th scope="col">2</th>
              <th scope="col">3</th>
              <th scope="col">4</th>
              <th scope="col">5</th>
              <th scope="col">6</th>
              <th scope="col">Total</th>
              <th scope="col">Set Pts</th>
              <th scope="col">Status</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">A</th>
              <td><input type="text" class="form-control" name="score_a_timer" id="score-a-timer"></td>
              <td><input type="text" class="form-control" name="score_a_pt1" id="score-a-pt1" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_pt2" id="score-a-pt2" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_pt3" id="score-a-pt3" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_pt4" id="score-a-pt4" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_pt5" id="score-a-pt5" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_pt6" id="score-a-pt6" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_total" id="score-a-total" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_setpoints" id="score-a-setpoints" value="0"></td>
              <td><input type="text" class="form-control" name="score_a_status" id="score-a-status" value=""></td>
              <td>
                <input type="hidden" name="score_a_id" id="score-a-id" value="0">
                <input type="hidden" name="score_action" value="update">
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
    <button class="playTimerA btn btn-info" id="score-a-timer-play">Play</button>
    <button class="playTimerA btn btn-warning" id="score-a-timer-pause">Pause</button>
  </div>
</div>
<div id="score-b-area" class="score-area mb-3 container">
  <div id="score-b-area" class="row">
    <div class="col-lg-12">
      <form id="form-score-b" action="controller.php" method="post" class="row">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="table-warning">
              <th scope="col">Team</th>
              <th scope="col">Timer</th>
              <th scope="col">1</th>
              <th scope="col">2</th>
              <th scope="col">3</th>
              <th scope="col">4</th>
              <th scope="col">5</th>
              <th scope="col">6</th>
              <th scope="col">Total</th>
              <th scope="col">Set Pts</th>
              <th scope="col">Status</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">B</th>
              <td><input type="text" class="form-control" name="score_b_timer" id="score-b-timer"></td>
              <td><input type="text" class="form-control" name="score_b_pt1" id="score-b-pt1" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_pt2" id="score-b-pt2" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_pt3" id="score-b-pt3" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_pt4" id="score-b-pt4" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_pt5" id="score-b-pt5" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_pt6" id="score-b-pt6" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_total" id="score-b-total" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_setpoints" id="score-b-setpoints" value="0"></td>
              <td><input type="text" class="form-control" name="score_b_status" id="score-b-status" value=""></td>
              <td>
                <input type="hidden" name="score_b_id" id="score-b-id" value="0">
                <input type="hidden" name="score_action" value="update">
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
    <button class="playTimerB btn btn-info" id="score-b-timer-play" href="#">Play</button>
    <button class="playTimerB btn btn-warning" id="score-b-timer-pause" href="#">Pause</button>
  </div>
</div>
<div class="container mb-3">
  <div class="row">
    <div class="col-lg-4">
      <h4>Create Scoreboard</h4>
      <p id="form-scoreboard-msg"></p>
      <form id="form-scoreboard" action="controller.php" method="post">
        <div class="form-group">
          <label for="scoreboard-games">Game</label>
          <select name="scoreboard_games" id="scoreboard-games" class="form-control">
            <option value='0'>Select a game draw!</option>
          </select>
        </div>
        <div class="form-group">
          <label for="scoreboard-set">Set:</label>
          <input type="number" name="scoreboard_set" min="1" class="form-control" max="5" id="scoreboard-set" value="1">
        </div>
          <input type="hidden" id="scoreboard-action" name="scoreboard_action" value="create">
          <input type="submit" value="Create" class="btn btn-primary" id="scoreboard-submit">
      </form>
    </div>
    <div class="col-lg-8">
      <h4>Scoreboard</h4>
      <table id="scoreboard-table"></table>
    </div>
  </div>
</div>
<div class="container mb-3">
  <div class="row">
    <div class="col-lg-3">
      <h4>Team</h4>
      <table id="tbl-team"><tr><td>0 team. buat dulu!</td></tr></table>
    </div>
    <div class="col-lg-3">
      <h4>Player</h4>
      <table id="tbl-player"><tr><td>0 player. buat dulu!</td></tr></table>
    </div>
    <div class="col-lg-6">
      <h4>Game Draw</h4>
      <table id="tbl-gamedraw"><tr><td>0 game draw. buat dulu!</td></tr></table>
    </div>
  </div>
</div>
<div class="container mb-3">
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
          <!-- <div class="form-check form-check-inline">
            <input type="radio" name="gamedraw_gamemode" class="gamedraw-gamemode-cls form-check-input" value="0" id="gamedraw-gamemode-beregu" checked="checked"><label for="gamedraw-gamemode-beregu" id="gamedraw-gamemode-beregu-lbl" class="form-check-label">Beregu</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" name="gamedraw_gamemode" class="gamedraw-gamemode-cls form-check-input" value="1" id="gamedraw-gamemode-individu"><label for="gamedraw-gamemode-individu" id="gamedraw-gamemode-individu-lbl" class="form-check-label">Individu</label>
          </div> -->
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
</div>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>