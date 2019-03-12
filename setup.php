<!DOCTYPE html>
<html>
<head>
<title>Setup</title>

<!-- <script src="js/jquery-3.3.1.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="js/jquery-3.3.1.min.js"></script>
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

  <h3>Preview</h3>
  <table>
    <tr>
      <th></th>
      <th><span id="sb-gameset">Set X</span></th>
      <th class="timer"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="point"></th>
      <th class="total-point"></th>
      <th class="set-point">Set pts</th>
      <th class="description"></th>
    </tr>
    <tr>
      <td><img src="" id="sb-teamA-logo"></td>
      <td><span id="sb-teamA-name"></span></td>
      <td class="timer"><span id="sb-teamA-timer"></span></td>
      <td class="point"><span id="sb-teamA-pt1"></span></td>
      <td class="point"><span id="sb-teamA-pt2"></span></td>
      <td class="point"><span id="sb-teamA-pt3"></span></td>
      <td class="point"><span id="sb-teamA-pt4"></span></td>
      <td class="point"><span id="sb-teamA-pt5"></span></td>
      <td class="point"><span id="sb-teamA-pt6"></span></td>
      <td class="total-point"><span id="sb-teamA-totpts"></span></td>
      <td class="set-point"><span id="sb-teamA-setpts"></span></td>
      <td class="status"><span id="sb-teamA-status"></span></td>
    </tr>
    <tr>
      <td><img src="" id="sb-teamB-logo"></td>
      <td><span id="sb-teamB-name"></span></td>
      <td class="timer"><span id="sb-teamB-timer"></span></td>
      <td class="point"><span id="sb-teamB-pt1"></span></td>
      <td class="point"><span id="sb-teamB-pt2"></span></td>
      <td class="point"><span id="sb-teamB-pt3"></span></td>
      <td class="point"><span id="sb-teamB-pt4"></span></td>
      <td class="point"><span id="sb-teamB-pt5"></span></td>
      <td class="point"><span id="sb-teamB-pt6"></span></td>
      <td class="total-point"><span id="sb-teamB-totpts"></span></td>
      <td class="set-point"><span id="sb-teamB-setpts"></span></td>
      <td class="status"><span id="sb-teamB-status"></span></td>
    </tr>
  </table>
  <hr>
  <div>
    <h1>Controller</h1>
  </div>
  <hr>
  <div>
    <h1>Settings</h1>
  </div>
  <div id="gamescore-area">
    <h3>Scores</h3>
    <h4 id="fs-teamA-title">Tim A</h4>
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
    <h4 id="fs-teamB-title">Tim B</h4>
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
  <hr>
  <div id="team-area">
    <h4>Create Team</h4>
    <table id="tbl-team"></table>
    <form action="tools/uploader.php" id="form-team" method="post" enctype="multipart/form-data">
      <p>
        <label for="team-logo">Logo</label>
        <input type="file" name="team-logo" id="inp-team-logo" accept="image/*">
      </p>
      <p>
        <label for="team-name">Name</label>
        <input type="text" name="team-name" id="inp-team-name" placeholder="team name">
        <input type="hidden" name="team-action" value="inp-create-team">
      </p>
      <input type="submit" value="Create">
    </form>
  </div>
  <hr>
  <div id="player-area">
    <h4>Create Player</h4>
    <table id="tbl-player"></table>
    <form action="controller.php" id="form-player" method="post"">
      <p>
        <label for="player-name">Name</label>
        <input type="text" name="player-name" id="inp-player-name" placeholder="player name">
      </p>
      <p>
        <label for="player-team">Team</label>
        <select name="player-team" id="player-team"></select>
        <input type="hidden" name="player-action" value="create">
      </p>
      <input type="submit" value="Create">
    </form>
  </div>
  <hr>
  <div id="drawing-area">
    <h3>Game Drawing</h3>
    <table id="tbl-game"></table>
    <form id="form-game" action="controller.php" method="post">
      <label for="game-ke">Drawing ke-</label>
      <input type="number" name="game-ke" id="game-ke" min="1" max="100" value="1">
      <br>
      <input type="radio" name="game-teamgame" class="game-teamgame-radio" value="0" id="game-teamgame-team" checked="checked"> Team Game | 
      <input type="radio" name="game-teamgame" class="game-teamgame-radio" value="1" id="game-teamgame-single"> Single
      <br>
      <label for="game-teamA">Team A:</label>
      <select name="game-teamA" id="game-teamA" class="game-teams"></select>
      <div class="game-player-opt-area hide">
        <label for="game-playerA">Player A:</label>
        <select name="game-playerA" id="game-playerA" class="game-playerteam"></select>
      <br>
      </div>
      <label for="game-teamB">Team B:</label>
      <select name="game-teamB" id="game-teamB" class="game-teams"></select>
      <div class="game-player-opt-area hide">
        <label for="game-playerB">Player B:</label>
        <select name="game-playerB" id="game-playerB" class="game-playerteam"></select>
      </div>
      <br>
      <input type="hidden" id="game-action" name="game-action" value="create">
      <input type="submit" value="Create">
    </form>
  </div>
  <div id="gameset-area">
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
  </div>

  <script src="js/script.js"></script>
</body>
</html>