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
<link href="css/style.css" rel="stylesheet">
<!-- <script src="js/jquery-3.3.1.min.map"></script> -->
<style>
    .no-boradius { border-radius: 0 }
</style>
</head>
<body class="bg-dark">

<!-- Title Section -->
<div id="score-title" class="score-area mb-3 container">
    <div class="row">
        <div class="col-lg-12 bg-dark py-3 border-bottom border-secondary">
            <h4 class="text-warning font-weight-light"><i class="fab fa-readme text-secondary"></i> DASHBOARD</h4>
        </div>
    </div>
</div>
<!-- End Title Section -->

<!-- Score A Section -->
<div id="score-a-area" class="container score-area mb-3">
    <div class="row">
        <div class="col-lg-12">
            <img id="score-a-logo" class="sb-logo-cls" src="uploads/no-image.png">
            <h5 id="score-team-a-title" class="text-primary">Team A - Player A</h5>
            <form id="form-score-a" action="controller.php" method="post" class="form">
                <table class="table table-sm">
                    <thead class="">
                        <tr class="">
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-timer-cls"><i class="far fa-clock"></i></th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p1-cls">1</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p2-cls">2</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p3-cls">3</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p4-cls">4</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p5-cls">5</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p6-cls">6</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-set-total-points-cls">Set Total</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-game-total-points-cls">Game Total</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-set-points-cls">Set Pts</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-game-points-cls">Game Pts</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-description-cls">Desc</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pt-0 pl-0 sb-timer-cls"><input type="text" readonly size="10" class="form-control font-italic border-primary border-top-0 text-white no-boradius bg-dark" name="score_a_timer" id="score-a-timer"></td>
                            <td class="pt-0 sb-p1-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt1" id="score-a-pt1" value=""></td>
                            <td class="pt-0 sb-p2-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt2" id="score-a-pt2" value=""></td>
                            <td class="pt-0 sb-p3-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt3" id="score-a-pt3" value=""></td>
                            <td class="pt-0 sb-p4-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt4" id="score-a-pt4" value=""></td>
                            <td class="pt-0 sb-p5-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt5" id="score-a-pt5" value=""></td>
                            <td class="pt-0 sb-p6-cls"><input type="text" size="5" class="form-control border-primary border-top-0 text-white score-a-input-cls no-boradius bg-dark" name="score_a_pt6" id="score-a-pt6" value=""></td>
                            <td class="pt-0 sb-set-total-points-cls"><input type="text" size="8" class="form-control border-primary border-top-0 text-white no-boradius bg-dark" readonly name="score_a_total" id="score-a-total" value=""></td>
                            <td class="pt-0 sb-game-total-points-cls"><input type="text" size="8" class="form-control border-primary border-top-0 text-white no-boradius bg-dark" readonly name="score_a_gametotal" id="score-a-gametotal" value=""></td>
                            <td class="pt-0 sb-set-points-cls"><input type="text" size="8" class="form-control border-primary border-top-0 text-white no-boradius bg-dark" name="score_a_setpoints" id="score-a-setpoints" data-setpoints="0" value=""></td>
                            <td class="pt-0 sb-game-points-cls"><input type="text" size="8" class="form-control border-primary border-top-0 text-white no-boradius bg-dark" readonly name="score_a_gamepoints" id="score-a-gamepoints" data-gamepoints="0" value=""></td>
                            <td class="pt-0 sb-description-cls"><input type="text" size="30" class="form-control border-primary border-top-0 text-white no-boradius bg-dark" name="score_a_desc" id="score-a-desc" value=""></td>
                            <td class="pr-0 pt-0">
                                <input type="hidden" name="score_a_gamedraw_id" id="score-a-gamedraw-id" value="">
                                <input type="hidden" name="score_a_gameset_id" id="score-a-gameset-id" value="">
                                <input type="hidden" name="score_a_id" id="score-a-id" value="">
                                <input type="hidden" name="score_action" value="update-a">
                                <input type="submit" value="Update" id="score-a-submit" class="btn btn-primary no-boradius">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div id="score-a-control" class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary" disabled><i class="far fa-clock"></i></button>
        <button class="btn btn-outline-primary" id="score-a-timer-play" disabled="disabled">Play</button>
        <button class="btn btn-outline-primary" id="score-a-timer-pause" disabled="disabled">Pause</button>
    </div>
</div>
<!-- End Score A Section -->

<!-- Score B Section -->
<div id="score-b-area" class="score-area mb-5 container">
    <div id="score-b-area" class="row">
        <div class="col-lg-12">
            <img id="score-b-logo" class="sb-logo-cls" src="uploads/no-image.png">
            <h5 id="score-team-b-title" class="text-success">Team B - Player B</h5>
            <form id="form-score-b" action="controller.php" method="post" class="form">
                <table class="table table-sm">
                    <thead>
                        <tr class="">
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-timer-cls"><i class="far fa-clock"></i></th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p1-cls">1</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p2-cls">2</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p3-cls">3</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p4-cls">4</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p5-cls">5</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-p6-cls">6</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-set-total-points-cls">Set Total</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-game-total-points-cls">Game Total</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-set-points-cls">Set Pts</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-game-points-cls">Game Pts</th>
                            <th class="pl-3 border-primary font-weight-normal text-primary sb-description-cls">Desc</th>
                            <th class="pl-3 border-success font-weight-normal text-success"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pt-0 pl-0 sb-timer-cls"><input type="text" readonly size="10" class="form-control font-italic border-success border-top-0 text-white no-boradius bg-dark" name="score_b_timer" id="score-b-timer"></td>
                            <td class="pt-0 sb-p1-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt1" id="score-b-pt1" value=""></td>
                            <td class="pt-0 sb-p2-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt2" id="score-b-pt2" value=""></td>
                            <td class="pt-0 sb-p3-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt3" id="score-b-pt3" value=""></td>
                            <td class="pt-0 sb-p4-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt4" id="score-b-pt4" value=""></td>
                            <td class="pt-0 sb-p5-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt5" id="score-b-pt5" value=""></td>
                            <td class="pt-0 sb-p6-cls"><input type="text" size="5" class="form-control border-success border-top-0 text-white score-b-input-cls no-boradius bg-dark" name="score_b_pt6" id="score-b-pt6" value=""></td>
                            <td class="pt-0 sb-set-total-points-cls"><input type="text" size="8" class="form-control border-success border-top-0 text-white no-boradius bg-dark" readonly name="score_b_total" id="score-b-total" value=""></td>
                            <td class="pt-0 sb-game-total-points-cls"><input type="text" size="8" class="form-control border-success border-top-0 text-white no-boradius bg-dark" readonly name="score_b_gametotal" id="score-b-gametotal" value=""></td>
                            <td class="pt-0 sb-set-points-cls"><input type="text" size="8" class="form-control border-success border-top-0 text-white no-boradius bg-dark" name="score_b_setpoints" id="score-b-setpoints" data-setpoints="0" value=""></td>
                            <td class="pt-0 sb-game-points-cls"><input type="text" size="8" class="form-control border-success border-top-0 text-white no-boradius bg-dark" readonly name="score_b_gamepoints" id="score-b-gamepoints" data-gamepoints="0" value=""></td>
                            <td class="pt-0 sb-description-cls"><input type="text" size="30" class="form-control border-success border-top-0 text-white no-boradius bg-dark" name="score_b_desc" id="score-b-desc" value=""></td>
                            <td class="pt-0 pr-0">
                                <input type="hidden" name="score_b_gamedraw_id" id="score-b-gamedraw-id" value="">
                                <input type="hidden" name="score_b_gameset_id" id="score-b-gameset-id" value="">
                                <input type="hidden" name="score_b_id" id="score-b-id" value="">
                                <input type="hidden" name="score_action" value="update-b">
                                <input type="submit" value="Update" id="score-b-submit" class="btn btn-success no-boradius">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div id="score-b-control" class="btn-group btn-group-sm">
        <button class="btn btn-outline-success" disabled><i class="far fa-clock"></i></button>
        <button class="btn btn-outline-success" id="score-b-timer-play" disabled="disabled">Play</button>
        <button class="btn btn-outline-success" id="score-b-timer-pause" disabled="disabled">Pause</button>
    </div>
</div>
<!-- End Score B Section -->

<!-- Web Config Section -->
<div class="container mb-3">
    <div class="row">
        <div class="col-lg-3 py-3">
            <h4 class="text-warning font-weight-light"><i class="fas fa-trophy text-secondary"></i> WEB SETTING</h4>
            <form action="controller.php" method="post" id="form-config">
                <div class="form-group">
                    <label for="config-time-interval" class="text-info">Time Interval:</label>
                    <input readonly type="number" name="config_time_interval" id="config-time-interval" class="form-control border-primary bg-dark text-secondary" min="100" max="60000" value="100">
                </div>
                <div class="form-group">
                    <label for="config-active-mode" class="text-info">Active Mode:</label>
                    <select name="config_active_mode" id="config-active-mode" class="form-control border-primary bg-dark text-light">
                        <option value="0">Invisible</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4 - Recurve</option>
                        <option value="5">5 - Recurve</option>
                        <option value="6">6 - Recurve</option>
                        <option value="7">7 - Compound</option>
                        <option value="8">8 - Compound</option>
                        <option value="9">9 - Compound</option>
                    </select>
                </div>
                <input type="hidden" id="config-id" name="config_id" value="0">
                <input type="hidden" id="config-action" name="config_action" value="update">
                <input type="submit" value="Save" class="btn btn-primary" id="config-submit">
            </form>
        </div>
        <div class="col-lg-9">
            <h4 class="text-warning font-weight-light"><i class="fas fa-trophy text-secondary"></i> PREVIEW</h4>
            <div id="prev-scoreboard">
                <table id="table-prev-scoreboard">
                    <thead>
                        <tr>
                            <td class="prev-score-logo"></td>
                            <td class="td-w prev-score-team"><span id="prev-set-num-team" class="prev-set-num">Set X</span></td>
                            <td class="td-w prev-score-player"></td>
                            <td class="prev-score-timer"></td>
                            <td class="prev-score-point-1"></td>
                            <td class="prev-score-point-2"></td>
                            <td class="prev-score-point-3"></td>
                            <td class="prev-score-point-4"></td>
                            <td class="prev-score-point-5"></td>
                            <td class="prev-score-point-6"></td>
                            <td class="align-middle prev-score-total"></td>
                            <td class="align-middle prev-score-gametotal"><span>Total</span></td>
                            <td class="prev-score-setpoint"></td>
                            <td class="prev-score-gamepoint"><span>Set pts</span></td>
                            <td class="prev-score-desc"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="prev-score-logo"><div><img id="" class="img-fluid" src="uploads/no-image.png"></div></td>
                            <td class="td-w prev-score-team"><div><span id="" class="">Team A</span></div></td>
                            <td class="td-w prev-score-player"><div><span id="" class="">Player A</span></div></td>
                            <td class="prev-score-timer"><div><span id="">120s</span></div></td>
                            <td class="prev-score-point-1"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-2"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-3"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-4"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-5"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-6"><div><span id="">0</span></div></td>
                            <td class="align-middle prev-score-total"><div><span id="" class="">0</span></div></td>
                            <td class="align-middle prev-score-gametotal"><div><span id="" class="">0</span></div></td>
                            <td class="prev-score-setpoint"><div><span>0</span></div></td>
                            <td class="prev-score-gamepoint"><div><span id="">0</span></div></td>
                            <td class="td-w align-middle prev-score-desc"><div><span id="">WINNER</span></div></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="prev-score-logo"><div class="align-middle"><img id="" class="img-fluid" src="uploads/no-image.png"></div></td>
                            <td class="td-w prev-score-team"><div><span id="t" class="">Team B</span></div></td>
                            <td class="td-w prev-score-player"><div><span id="" class="">Player B</span></div></td>
                            <td class="prev-score-timer"><div><span id="">120s</span></div></td>
                            <td class="prev-score-point-1"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-2"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-3"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-4 "><div><span id="">0</span></div></td>
                            <td class="prev-score-point-5"><div><span id="">0</span></div></td>
                            <td class="prev-score-point-6"><div><span id="">0</span></div></td>
                            <td class="align-middle prev-score-total"><div><span id="" class="">0</span></div></td>
                            <td class="align-middle prev-score-gametotal"><div><span id="" class="">0</span></div></td>
                            <td class="prev-score-setpoint"><div><span id="">0</span></div></td>
                            <td class="prev-score-gamepoint"><div><span id="">0</span></div></td>
                            <td class="td-w align-middle prev-score-desc"><div><span id="">WINNER</span></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <img id="config-img" class="img-fluid" src="images/mode_2.png"> -->
        </div>
    </div>
</div>
<!-- End Web Config Section -->

<!-- Game Draw & Set Section -->
<div class="container mb-3">
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-outline-warning btn-menu" data-toggle="collapse" href="#gamedraw-collapse" role="button" aria-expanded="false" aria-controls="gamedraw-collapse">
                <h4 class="text-warning font-weight-light"><i class="fas fa-trophy text-secondary"></i> GAME DRAW <i class="fas fa-caret-down text-warning caret-cls"></i></h4>
            </a>
            <div class="collapse card-block" id="gamedraw-collapse">
                <div class="card card-body">
                    <button type="button" id="gamedraw-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Game Draw</button>
                    <table id="tbl-gamedraw" class="table table-sm table-hover"><tr><td>0 game draw. buat dulu!</td></tr></table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <a class="btn btn-outline-warning btn-menu" data-toggle="collapse" href="#gameset-collapse" role="button" aria-expanded="false" aria-controls="gameset-collapse">
                <h4 class="text-warning font-weight-light"><i class="fas fa-ellipsis-v text-secondary"></i> GAME SET <i class="fas fa-caret-down text-warning caret-cls"></i></h4>
            </a>
            <div class="collapse card-block" id="gameset-collapse">
                <div class="card card-body">
                    <button type="button" id="gameset-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Game Set</button>
                    <table id="gameset-table" class="table table-sm table-hover"><tr><td>0 game set. buat dulu!</td></tr></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Game Draw & Set Section -->

<!-- <div class="container mb-3">
    <div class="row">
        <div id="gamedraw-accordion" class="col-lg-6"></div>
    </div>
</div> -->

<!-- Team & Player Section -->
<div class="container mb-3">
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-outline-warning btn-menu" data-toggle="collapse" href="#team-collapse" role="button" aria-expanded="false" aria-controls="team-collapse">
                <h4 class="text-warning font-weight-light"><i class="fas fa-flag text-secondary"></i> TEAM <i class="fas fa-caret-down text-warning caret-cls"></i></h4>
            </a>
            <div class="collapse card-block" id="team-collapse">
                <div class="card card-body">
                    <button type="button" id="team-create-btn" class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Team</button>
                    <table id="tbl-team" class="table table-sm table-hover"><tr><td>0 team. buat dulu!</td></tr></table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <a class="btn btn-outline-warning btn-menu" data-toggle="collapse" href="#player-collapse" role="button" aria-expanded="false" aria-controls="player-collapse">
                <h4 class="text-warning font-weight-light"><i class="fas fa-users text-secondary"></i> PLAYER <i class="fas fa-caret-down text-warning caret-cls"></i></h4>
            </a>
            <div class="collapse card-block" id="player-collapse">
                <div class="card card-body">
                    <button type="button" id="player-create-btn"  class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> New Player</button>
                    <table id="tbl-player" class="table table-sm table-hover"><tr><td>0 player. buat dulu!</td></tr></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Team & Player Section -->

<!-- Scoreboard Config Section -->
<!-- <div id="scoreboard-config" class="mb-3 container">
    <div class="row">
        <div class="col-lg-12 bg-dark py-3 border-bottom border-secondary">
            <h5 class="text-warning font-weight-light"><i class="fab fa-readme text-secondary"></i> SCOREBOARD</h5>
            <button type="button" id="scoreboard-ui-config-btn"  class="btn btn-sm btn-link my-1"><i class="fas fa-plus-square"></i> Setup</button> -->
            <!-- <form id="form-scoreboard-config" action="controller.php" method="get" class="">
                <div class="form-group">
                    <label for="scoreboard-settext">Set Text</label>
                    <input type="text" name="scoreboard_settext" id="scoreboard_settext" class="form-control-sm mx-2">
                </div>
                <div class="form-group">
                    <label for="sb-logo">Show/Hide</label><br>
                    <input id="sb-logo" class="sb-checkbox-cls" type="checkbox" name="sb_logo" value="1" checked="checked">&nbsp;Logo&nbsp;&nbsp;
                    <input id="sb-team" class="sb-checkbox-cls" type="checkbox" name="sb_team" value="1" checked="checked">&nbsp;Team&nbsp;&nbsp;
                    <input id="sb-player" class="sb-checkbox-cls" type="checkbox" name="sb_player" value="1" checked="checked">&nbsp;Player&nbsp;&nbsp;
                    <input id="sb-timer" class="sb-checkbox-cls" type="checkbox" name="sb_timer" value="1" checked="checked">&nbsp;Timer&nbsp;&nbsp;
                    <input id="sb-p1" class="sb-checkbox-cls" type="checkbox" name="sb_p1" value="1" checked="checked">&nbsp;Point 1&nbsp;&nbsp;
                    <input id="sb-p2" class="sb-checkbox-cls" type="checkbox" name="sb_p2" value="1" checked="checked">&nbsp;Point 2&nbsp;&nbsp;
                    <input id="sb-p3" class="sb-checkbox-cls" type="checkbox" name="sb_p3" value="1" checked="checked">&nbsp;Point 3&nbsp;&nbsp;
                    <input id="sb-p4" class="sb-checkbox-cls" type="checkbox" name="sb_p4" value="1" checked="checked">&nbsp;Point 4&nbsp;&nbsp;
                    <input id="sb-p5" class="sb-checkbox-cls" type="checkbox" name="sb_p5" value="1" checked="checked">&nbsp;Point 5&nbsp;&nbsp;
                    <input id="sb-p6" class="sb-checkbox-cls" type="checkbox" name="sb_p6" value="1" checked="checked">&nbsp;Point 6 <br>
                    <input id="sb-set-total-points" class="sb-checkbox-cls" type="checkbox" name="sb_set_total_points" value="1" checked="checked">&nbsp;Set Total Points&nbsp;&nbsp;
                    <input id="sb-game-total-points" class="sb-checkbox-cls" type="checkbox" name="sb_game_total_points" value="1" checked="checked">&nbsp;Game Total Points&nbsp;&nbsp;
                    <input id="sb-set-points" class="sb-checkbox-cls" type="checkbox" name="sb_set_points" value="1" checked="checked">&nbsp;Set Points&nbsp;&nbsp;
                    <input id="sb-game-points" class="sb-checkbox-cls" type="checkbox" name="sb_game_points" value="1" checked="checked">&nbsp;Game Points&nbsp;&nbsp;
                    <input id="sb-description" class="sb-checkbox-cls" type="checkbox" name="sb_description" value="1" checked="checked">&nbsp;Description
                </div>
                <input type="hidden" id="scoreboard-config-action" name="scoreboard_config_action" value="update">
                <button id="scoreboard-config-submit" class="btn btn-sm btn-success">Update</button>
            </form> -->
        <!-- </div>
    </div>
</div> -->
<!-- End Scoreboard Config Section -->

<!-- Link Section -->
<div class="container my-5 py-3 bg-secondary">
    <div class="row">
        <div class="col-lg-12">
            <h4>XAML</h4>
            <span class="mr-5 text-white">Get Live Score 1:</span>
            <input type="text" size="100" class="pl-5 text-warning bg-dark border-0" disabled="disabled" value="<?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/controller.php?GetScoreboard=live&mode=1'; ?>" readonly />
            <br><span class="mr-5 text-white">Get Live Score 2:</span>
            <input type="text" size="100" class="pl-5 text-warning bg-dark border-0" disabled="disabled" value="<?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/controller.php?GetScoreboard=live&mode=2'; ?>" readonly />
            <br><span class="mr-5 text-white">Get Live Score 3:</span>
            <input type="text" size="100" class="pl-5 text-warning bg-dark border-0" disabled="disabled" value="<?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/controller.php?GetScoreboard=live&mode=3'; ?>" readonly />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h4>WEB</h4>
            <span class="mr-5 text-white">Get Live Score 1:</span>
            <input type="text" size="100" class="pl-5 text-warning bg-dark border-0" disabled="disabled" value="<?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/title.php'; ?>" readonly />
        </div>
    </div>
</div>
<!-- End Link Section -->

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
<!-- End Modal Team -->

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
<!-- End Modal Player -->

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
                <div id="gamedraw-radio-bowstyle-area" class="form-group">
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
<!-- End Modal Game Draw -->

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
                <div id="gameset-status-area" class="form-group hide">
                    <label for="gameset-status">Status</label>
                    <select name="gameset_status" class="form-control" id="gameset-status">
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
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
<!-- End Modal Game Set -->

<!-- The Modal Game Draw Info -->
<div class="modal" id="gamedraw-info-modal">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 id="gamedraw-info-modal-title" class="modal-title">Game Info</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <table class="table table-sm" id="gamedraw-info-modal-table">
                <tr><td>Nothing</td></tr>
            </table>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
<!-- End Modal Game Draw Info -->

<!-- The Modal Game Set Info -->
<div class="modal" id="gameset-info-modal">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 id="gameset-info-modal-title" class="modal-title">Game Info</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <table class="table table-sm" id="gameset-info-modal-table">
                <tr><td>Nothing</td></tr>
            </table>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
<!-- End Modal Game Set Info -->

<!-- The Modal Scoreboard UI Info -->
<div class="modal" id="form-scoreboard-ui-modal">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 id="scoreboard-ui-modal-title" class="modal-title">New Player</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form action="controller.php" id="form-scoreboard-ui" method="post">
                <table>
                    <thead>
                        <tr>
                            <td></td>
                            <td>Text</td>
                            <td>Visibility</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Logo</td>
                            <td></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Team</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Player</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Timer</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 1</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 2</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 3</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 4</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 5</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Point 6</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Set Total Points</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Game Total Points</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Set Points</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Game Points</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><input type="text" name="" id=""></td>
                            <td><input type="checkbox" name="" id=""></td>
                        </tr>
                    </tbody>
                </table>
                <!-- <div class="form-group">
                    <label for="player-name">Name</label>
                    <input type="text" name="player_name" id="player-name" class="form-control" placeholder="player name">
                </div>
                <div class="form-group">
                    <label for="player-team">Team</label>
                    <select name="player_team" class="form-control" id="player-team">
                        <option value='0'>-</option>
                    </select>
                    <input type="hidden" id="player-id" name="player_id" value="0">
                </div> -->
                <input type="hidden" id="player-action" name="player_action" value="update">
                <input type="submit" value="Save" class="btn btn-primary" id="scoreboard-ui-submit">
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
<!-- End Modal Game Set Info -->

<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>