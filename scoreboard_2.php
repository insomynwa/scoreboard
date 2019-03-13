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
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div>
        <h1>Controller</h1>
      </div>
    </div>
  </div>
  <div class="row hide">
    <div class="col-lg-12"><h4>Team Score</h4></div>
    <div class="col-lg-6">
      <h5>Team A</h5>
        <form id="form-score-a" action="controller.php" method="post">
          <label for="scoreA-timer">Timer</label>
          <input type="text" name="scoreA-timer" id="fs-teamA-timer"><a class="playTimerA" id="scoreA-timer-btnplay" href="#">Play</a> | <a class="playTimerA" id="scoreA-timer-btnpause" href="#">Pause</a>
          <br>
          <label for="scoreA-pt1">Point 1</label>
          <input type="text" name="scoreA-pt1" id="fs-teamA-pt1" value="0">
          <br>
          <label for="scoreA-pt2">Point 2</label>
          <input type="text" name="scoreA-pt2" id="fs-teamA-pt2" value="0">
          <br>
          <label for="scoreA-pt3">Point 3</label>
          <input type="text" name="scoreA-pt3" id="fs-teamA-pt3" value="0">
          <br>
          <label for="scoreA-pt4">Point 4</label>
          <input type="text" name="scoreA-pt4" id="fs-teamA-pt4" value="0">
          <br>
          <label for="scoreA-pt5">Point 5</label>
          <input type="text" name="scoreA-pt5" id="fs-teamA-pt5" value="0">
          <br>
          <label for="scoreA-pt6">Point 6</label>
          <input type="text" name="scoreA-pt6" id="fs-teamA-pt6" value="0">
          <br>
          <label for="scoreA-setpts">Set Point</label>
          <input type="text" name="scoreA-setpts" id="fs-teamA-setpts" value="0">
          <br>
          <label for="scoreA-status">Status</label>
          <input type="text" name="scoreA-status" id="fs-teamA-status" value="0">
          <br>
          <input type="hidden" name="sb-teamA-scoreid" id="sb-teamA-scoreid" value="0">
          <input type="hidden" name="score-action" value="updateA">
          <input type="submit" value="Update">
        </form>
    </div>
    <div class="col-lg-6">
      <h5>Team B</h5>
      <form id="form-score-b" action="controller.php" method="post">
        <label for="scoreB-timer">Timer</label>
        <input type="text" name="scoreB-timer" id="fs-teamB-timer"><a class="playTimerB" id="scoreB-timer-btnplay" href="#">Play</a> | <a class="playTimerB" id="scoreB-timer-btnpause" href="#">Pause</a>
        <br>
        <label for="scoreB-pt1">Point 1</label>
        <input type="text" name="scoreB-pt1" id="fs-teamB-pt1" value="0">
        <br>
        <label for="scoreB-pt2">Point 2</label>
        <input type="text" name="scoreB-pt2" id="fs-teamB-pt2" value="0">
        <br>
        <label for="scoreB-pt3">Point 3</label>
        <input type="text" name="scoreB-pt3" id="fs-teamB-pt3" value="0">
        <br>
        <label for="scoreB-pt4">Point 4</label>
        <input type="text" name="scoreB-pt4" id="fs-teamB-pt4" value="0">
        <br>
        <label for="scoreB-pt5">Point 5</label>
        <input type="text" name="scoreB-pt5" id="fs-teamB-pt5" value="0">
        <br>
        <label for="scoreB-pt6">Point 6</label>
        <input type="text" name="scoreB-pt6" id="fs-teamB-pt6" value="0">
        <br>
        <label for="scoreB-setpts">Set Point</label>
        <input type="text" name="scoreB-setpts" id="fs-teamB-setpts" value="0">
        <br>
        <label for="scoreB-status">Status</label>
        <input type="text" name="scoreB-status" id="fs-teamB-status" value="0">
        <br>
        <input type="hidden" name="sb-teamB-scoreid" id="sb-teamB-scoreid" value="0">
        <input type="hidden" name="score-action" value="updateB">
        <input type="submit" value="Update">
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div id="scoreboard-area">
        <h4>Create Scoreboard</h4>
        <table id="scoreboard-table"></table>
        <p id="form-scoreboard-msg"></p>
        <form id="form-scoreboard" action="controller.php" method="post">
            <label for="scoreboard_games">Game</label>
            <select name="scoreboard_games" id="scoreboard-games">
              <option value='0'>Select a game draw!</option>
            </select>
            <label for="scoreboard-set">Set:</label>
            <input type="number" name="scoreboard_set" min="1" max="5" id="scoreboard-set" value="1">
            <br>
            <input type="hidden" id="scoreboard-action" name="scoreboard_action" value="create">
            <input type="submit" value="Create" id="scoreboard-submit">
        </form>
      </div>
      <div id="gamescore-area">
        <h3>Scores</h3>
        <h4 id="fs-teamA-title">Tim A</h4>
        <h4 id="fs-teamB-title">Tim B</h4>
      </div>
    </div>
    <div class="col-lg-6">
      <div>
        <h4>Settings</h4>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div id="team-area">
            <h4>Create Team</h4>
            <table id="tbl-team"><tr><td>0 team. buat dulu!</td></tr></table>
            <form action="tools/uploader.php" id="form-team" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="team_logo">Logo</label>
                <input type="file" name="team_logo" id="team-logo" accept="image/*" class="form-control-file">
              </div>
              <div class="form-group">
                <label for="team_name">Name</label>
                <input type="text" name="team_name" id="team-name" placeholder="team name" class="form-control">
                <input type="hidden" name="team_action" value="create">
              </div>
              <input type="submit" value="Create" class="btn btn-primary">
            </form>
          </div>
        </div>
        <div class="col-lg-6">
          <div id="player-area">
            <h4>Create Player</h4>
            <table id="tbl-player"><tr><td>0 player. buat dulu!</td></tr></table>
            <form action="controller.php" id="form-player" method="post">
              <div class="form-group">
                <label for="player_name">Name</label>
                <input type="text" name="player_name" id="player-name" class="form-control" placeholder="player name">
              </div>
              <div class="form-group">
                <label for="player_team">Team</label>
                <select name="player_team" class="form-control" id="player-team">
                  <option value='0'>-</option>
                </select>
                <input type="hidden" name="player_action" value="create">
              </div>
              <input type="submit" value="Create" class="btn btn-primary">
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div id="drawing-area" class="col-lg-12">
          <h3>Game Draws</h3>
          <table id="tbl-gamedraw"><tr><td>0 game draw. buat dulu!</td></tr></table>
          <p id="form-gamedraw-msg"></p>
          <form id="form-gamedraw" action="controller.php" method="post">
            <div class="form-group">
              <label for="gamedraw_num">Draws</label>
              <input type="number" name="gamedraw_num" id="gamedraw-num" min="1" max="100" value="1" class="form-control">
            </div>
            <div class="form-group">
              <div class="form-check">
                <input type="radio" name="gamedraw_gamemode" class="gamedraw-gamemode-cls form-check-input" value="0" id="gamedraw-gamemode-team" checked="checked"><label for="gamedraw_gamemode" class="form-check-label">Team Game</label>
              </div>
              <div class="form-check">
                <input type="radio" name="gamedraw_gamemode" class="gamedraw-gamemode-cls form-check-input" value="1" id="gamedraw-gamemode-single"><label for="gamedraw_gamemode" class="form-check-label">Single</label>
              </div>
            </div>
            <div class="form-group">
              <label for="gamedraw_team_a">Team A:</label>
              <select name="gamedraw_team_a" id="gamedraw-team-a" class="gamedraw-team-cls form-control"></select>
              <div class="gamedraw-player-opt-area-cls hide">
                <label for="gamedraw_player_a">Player A:</label>
                <select name="gamedraw_player_a" id="gamedraw-player-a" class="form-control gamedraw-player-cls"></select>
              </div>
            </div>
            <div class="form-group">
              <label for="gamedraw_team_b">Team B:</label>
              <select name="gamedraw_team_b" id="gamedraw-team-b" class="form-control gamedraw-team-cls"></select>
              <div class="gamedraw-player-opt-area-cls hide">
                <label for="gamedraw_player_b">Player B:</label>
                <select name="gamedraw_player_b" id="gamedraw-player-b" class="form-control gamedraw-player-cls"></select>
              </div>
            </div>
            <input type="hidden" id="gamedraw-action" name="gamedraw_action" value="create">
            <input type="submit" value="Create" id="gamedraw-submit" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- <div id="gameset-area">
    <h3>Game Set</h3>
    <table id="tbl-gameset"></table>
    <form id="form-gameset" action="controller.php" method="post">
      <label for="gameset-games">Game ke-</label>
      <select name="gameset-games" id="gameset-games"></select>
      <br>
      <label for="gameset-set">Set:</label>
      <input type="number" name="gameset-set" min="1" max="100" id="gameset-set" value="1">
      <br>
      <input type="hidden" id="gameset-action" name="gameset-action" value="create">
      <input type="submit" value="Create">
    </form>
  </div> -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>