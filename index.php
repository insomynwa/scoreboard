<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Setup | Scoreboard</title>

<!-- <script src="js/jquery-3.3.1.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<link href="font-awesome/css/all.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/print.min.css">
<!-- <script src="js/jquery-3.3.1.min.map"></script> -->
<style>
    .no-boradius { border-radius: 0 }
</style>
</head>
<body class="bg-gray-1">
<?php
    include 'includes/init.php';
?>

<div id="" class="container mt-5 mb-3">
    <div class="row">
        <div class="col-lg-12 px-2 mb-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <h5 class="text-warning text-center">Live Score</h5>
                </div>
                <div id="form-scoreboard-wrapper" class="card-body pb-1 pt-1">
                </div>
                <div class="card-footer pt-0">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="" class="container mt-5 mb-3">
    <div class="row">
        <div class="col-lg-12 px-2 mb-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-primary">
                    <img id="score-a-logo" class="sb-logo-cls" src="uploads/no-image.png">
                    <h5 id="score-team-a-title" class="text-primary">Team A - Player A</h5>
                </div>
                <div class="card-body pb-1 pt-1">
                    <form id="form-score-a" action="controller.php" method="post" class="form  form-scoreboard">
                        <table class="table table-sm">
                            <thead class="">
                                <tr class="">
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-timer-cls"><i class="far fa-clock"></i></th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p1-cls">1</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p2-cls">2</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p3-cls">3</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p4-cls">4</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p5-cls">5</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-p6-cls">6</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-set-total-points-cls">Set Total</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-game-total-points-cls">Game Total</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-set-points-cls">Set Pts</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-game-points-cls">Game Pts</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary sb-description-cls">Desc</th>
                                    <th class="pl-3 border-primary border-bottom border-top-0 font-weight-normal text-primary"></th>
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
                    </form> -->
                    <!-- / form-score-a -->
                <!-- </div>
                <div class="card-footer pt-0">
                    <div id="score-a-control" class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" disabled><i class="far fa-clock"></i></button>
                        <button class="btn btn-outline-primary" id="score-a-timer-play" disabled="disabled">Play</button>
                        <button class="btn btn-outline-primary" id="score-a-timer-pause" disabled="disabled">Pause</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 px-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-success">
                    <img id="score-b-logo" class="sb-logo-cls" src="uploads/no-image.png">
                    <h5 id="score-team-b-title" class="text-success">Team B - Player B</h5>
                </div>
                <div class="card-body pb-1 pt-1">
                    <form id="form-score-b" action="controller.php" method="post" class="form form-scoreboard">
                        <table class="table table-sm">
                            <thead>
                                <tr class="">
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-timer-cls"><i class="far fa-clock"></i></th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p1-cls">1</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p2-cls">2</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p3-cls">3</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p4-cls">4</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p5-cls">5</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-p6-cls">6</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-set-total-points-cls">Set Total</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-game-total-points-cls">Game Total</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-set-points-cls">Set Pts</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-game-points-cls">Game Pts</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success sb-description-cls">Desc</th>
                                    <th class="pl-3 border-success border-bottom border-top-0 font-weight-normal text-success"></th>
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
                    </form> -->
                    <!-- / form-score-b -->
                <!-- </div>
                <div class="card-footer pt-0">
                    <div id="score-b-control" class="btn-group btn-group-sm">
                        <button class="btn btn-outline-success" disabled><i class="far fa-clock"></i></button>
                        <button class="btn btn-outline-success" id="score-b-timer-play" disabled="disabled">Play</button>
                        <button class="btn btn-outline-success" id="score-b-timer-pause" disabled="disabled">Pause</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div id="scoreboard-style" class="container mb-3">
    <div class="row">
        <div class="col-lg-12 px-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <div class="row">
                        <div class="col-lg-4">
                            <h5 class="text-warning">Scoreboard Style</h5>
                        </div>
                        <div class="col-lg-3">
                            <p class="text-gray-4">Active Style: <span class="text-success font-weight-bold" id="scoreboard-style-active-bowstyle-info"></span><span class="text-success font-weight-bold" id="scoreboard-style-active-bowstyle-style-info"></span></p>
                            <p id="scoreboard-style-message" class="text-warning small"></p>
                        </div>
                        <div class="col-lg-5">
                            <p class="text-gray-4">Title URL: [<a class="text-info" href="<?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/title.php'; ?>" target="_blank" rel="noopener noreferrer"><?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/title.php'; ?></a>]</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-scoreboard-style" method="post" action="controller.php" class="form">
                        <div class="row w-100 mb-3">
                            <div class="col input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="scoreboard-style-bowstyle-select">Bowstyle</label>
                                </div>
                                <select class="custom-select shadow-none scoreboard-style-select" name="bowstyle_id" id="scoreboard-style-bowstyle-select"></select>
                            </div>
                            <div class="col input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="scoreboard-style-style-select">Style</label>
                                </div>
                                <select class="custom-select shadow-none scoreboard-style-select" name="style" id="scoreboard-style-style-select">
                                    <option value="0">Choose</option>
                                </select>
                            </div>
                            <div class="col">
                                <div>
                                    <input type="button" value="Activate" class="btn btn-outline-success hide" id="scoreboard-style-activate">
                                    <input type="hidden" id="form-scoreboard-style-mode" name="" value="view">
                                    <input type="hidden" id="scoreboard-style-action" name="scoreboard_style_action" value="view">
                                    <input type="submit" value="Save" class="btn btn-primary hide" id="scoreboard-style-submit">
                                    <input type="button" value="Cancel" class="btn btn-warning hide" id="scoreboard-style-cancel">
                                </div>
                            </div>
                            <div class="col">
                                <div id="scoreboard-style-crud-btn">
                                    <button type="button" id="scoreboard-style-btn-create" class="btn btn-sm btn-link-gray-4 hide">
                                        <i class="fas fa-plus mr-1"></i>
                                        new
                                    </button>
                                    <!-- / scoreboard-style-btn-create -->
                                    <button type="button" id="scoreboard-style-btn-edit" class="btn btn-sm btn-link-gray-4 hide">
                                        <i class="fas fa-pen mr-1"></i>
                                        edit
                                    </button>
                                    <!-- / scoreboard-style-btn-edit -->
                                    <button type="button" id="scoreboard-style-btn-delete" class="btn btn-sm btn-link-gray-4 hide">
                                        <i class="fas fa-trash mr-1"></i>
                                        delete
                                    </button>
                                    <!-- / scoreboard-style-btn-edit -->
                                </div>
                            </div>
                        </div>
                        <div id="form-scoreboard-style-visibility" class="row hide">
                            <div class="col">
                                <label for="table-style-visibility" class="text-light">Visibility:<input type="checkbox" name="" id="ssv-collective-cb" class="ml-3 mr-1"><span class="text-info small">select all</span></label>
                                <table id="table-style-visibility" class="table table-borderless">
                                    <!-- <tr>
                                        <td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-logo" class="ssv-cb" checked name="logo" id="ssv-logo-cb" value=""> logo</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score1" class="ssv-cb" checked name="score1" id="ssv-score1-cb" value=""> score 1</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score4" class="ssv-cb" checked name="score4" id="ssv-score4-cb" value=""> score 4</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setpoint" class="ssv-cb" checked name="setpoint" id="ssv-setpoint-cb" value=""> set point</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamepoint" class="ssv-cb" checked name="gamepoint" id="ssv-gamepoint-cb" value=""> game point</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-team" class="ssv-cb" checked name="team" id="ssv-team-cb" value=""> team</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score2" class="ssv-cb" checked name="score2" id="ssv-score2-cb" value=""> score 2</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score5" class="ssv-cb" checked name="score5" id="ssv-score5-cb" value=""> score 5</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setscore" class="ssv-cb" checked name="setscore" id="ssv-setscore-cb" value=""> set score</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamescore" class="ssv-cb" checked name="gamescore" id="ssv-gamescore-cb" value=""> game score</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-player" class="ssv-cb" checked name="player" id="ssv-player-cb" value=""> player</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score3" class="ssv-cb" checked name="score3" id="ssv-score3-cb" value=""> score 3</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score6" class="ssv-cb" checked name="score6" id="ssv-score6-cb" value=""> score 6</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-timer" class="ssv-cb" checked name="timer" id="ssv-timer-cb" value=""> timer</td>
                                        <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-desc" class="ssv-cb" checked name="description" id="ssv-description-cb" value=""> description</td>
                                    </tr> -->
                                </table>
                                <!-- / scoreboard-style-visibility -->
                            </div>
                        </div>
                        <!-- / form-scoreboard-style-visibility -->
                    </form>
                    <!-- / form-scoreboard-style -->
                    <div id="scoreboard-style-preview" class="hide mt-4">
                        <h6 class="text-info">Preview</h6>
                        <table></table>
                    </div>
                    <!-- / form-scoreboard-style -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / scoreboard-style -->

<!-- <div id="config-section" class="container mb-3">
    <div class="row">
        <div class="col-lg-9 px-2">
            <div class="card bg-gray-2" style="min-height:320px">
                <div class="card-header border-bottom border-gray-5">
                    <h5 class="text-warning">Preview</h5>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td><span class="text-gray-4 small">Title Link</span></td>
                            <td><i class="text-warning fas fa-caret-right mx-2" aria-hidden="true"></i></td>
                            <td><a class="text-info" href="<?php //echo 'http://'.gethostbyname(gethostname()).'/scoreboard/title.php'; ?>" target="_blank" rel="noopener noreferrer"><?php echo 'http://'.gethostbyname(gethostname()).'/scoreboard/title.php'; ?></a></td>
                        </tr>
                        <tr>
                            <td><span class="text-gray-4 small">Active Mode</span></td>
                            <td><i class="text-warning fas fa-caret-right mx-2" aria-hidden="true"></i></td>
                            <td><span class="font-weight-bold text-info" id="activated-mode"></span></td>
                        </tr>
                    </table>
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
                        </table> -->
                        <!-- / table-prev-scoreboard -->
                    <!-- </div> -->
                    <!-- <img id="config-img" class="img-fluid" src="images/mode_2.png"> -->
                <!-- </div>
            </div>
        </div>
        <div class="col-lg-3 px-2">
            <div class="card bg-gray-2" style="min-height:320px">
                <div class="card-header border-bottom border-gray-5">
                    <h5 class="text-warning">Config</h5>
                </div>
                <div class="card-body">
                    <form action="controller.php" method="post" id="form-config">
                        <div class="form-group">
                            <label for="config-time-interval" class="text-gray-4 small">Time Interval:</label>
                            <input readonly type="number" name="config_time_interval" id="config-time-interval" class="form-control border-gray-5 bg-gray-3 text-gray-4 shadow-none" min="100" max="60000" value="100">
                        </div> -->
                        <!-- <div class="form-group">
                            <label for="config-point-per-set" class="text-info">Point per Set (max.6)</label>
                            <input type="number" name="config_point_per_set" id="config-point-per-set" class="form-control border-primary bg-dark text-light" min="3" max="6" value="3">
                        </div> -->
                        <!-- <div class="form-group">
                            <label for="config-active-mode" class="text-gray-4 small">Mode:</label>
                            <select name="config_active_mode" id="config-active-mode" class="form-control border-gray-5 bg-gray-4 text-gray-4 shadow-none">
                                <option value="0">Invisible</option>
                                <option value="1">1 - Default</option>
                                <option value="2">2 - Default</option>
                                <option value="3">3 - Default</option>
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
                    </form> -->
                    <!-- / form-config -->
                <!-- </div>
            </div>
        </div>
    </div>
</div> -->
<!-- / config-section -->

<div id="game-wrapper" class="container mb-3">
    <div class="row">
        <!-- <div class="col-lg-12">
            <h4 class="text-warning font-weight-light"><i class="fas fa-trophy text-secondary mr-2" aria-hidden="true"></i>GAME</h4>
        </div> -->
        <div class="col-lg-6 px-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <h5 class="text-warning">Game Draw</h5>
                    <button type="button" id="create-gamedraw-button" class="btn btn-sm btn-link-gray-4">
                        <i class="fas fa-plus-square mr-1"></i>
                        New Draw
                    </button>
                    <!-- / create-gamedraw-button -->
                </div>
                <div class="card-body pb-5">
                    <div class="customscroll border-top border-bottom border-gray-3" style="overflow: auto; height:300px">
                        <table id="gamedraw-table" class="table table-sm table-hover">
                            <thead>
                                <tr class='bg-gray-3'>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Game</th>
                                    <th class='text-gray-4 font-weight-normal border-0'>#</th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Draw</th>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- / gamedraw-table -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 px-2">
            <div class="card  bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <h5 class="text-warning">Game Set</h5>
                    <button type="button" id="create-gameset-button" class="btn btn-sm btn-link-gray-4">
                        <i class="fas fa-plus-square mr-1"></i>
                        New Set
                    </button>
                    <!-- / create-gameset-button -->
                </div>
                <div class="card-body pb-5">
                    <div class="customscroll border-top border-bottom border-gray-3" style="overflow: auto; height:300px">
                        <table id="gameset-table" class="table table-sm table-hover">
                            <thead>
                                <tr class='bg-gray-3'>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Game</th>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Set</th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- / gameset-table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / game-wrapper -->

<div id="contestant-wrapper" class="container mb-3">
    <div class="row">
        <div class="col-lg-4 px-2">
        </div>
        <div class="col-lg-4 px-2">
            <div class="card bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <h5 class="text-warning">Team</h5>
                    <button type="button" id="create-team-button" class="btn btn-sm btn-link-gray-4">
                        <i class="fas fa-plus-square mr-1"></i>
                        New Team
                    </button>
                    <!-- / create-team-button -->
                </div>
                <div class="card-body pb-5">
                    <div class="customscroll border-top border-bottom border-gray-3" style="overflow: auto; height:300px">
                        <table id="team-table" class="table table-sm table-hover">
                            <thead>
                                <tr class='bg-gray-3'>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Logo</th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Name</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- / team-table -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 px-2">
            <div class="card  bg-gray-2">
                <div class="card-header border-bottom border-gray-5 pb-1">
                    <h5 class="text-warning">Player</h5>
                    <button type="button" id="create-player-button" class="btn btn-sm btn-link-gray-4">
                        <i class="fas fa-plus-square mr-1"></i>
                        New Player
                    </button>
                    <!-- / create-player-button -->
                </div>
                <div class="card-body pb-5">
                    <div class="customscroll border-top border-bottom border-gray-3" style="overflow: auto; height:300px">
                        <table id="player-table" class="table table-sm table-hover">
                            <thead>
                                <tr class='bg-gray-3'>
                                    <th class='text-gray-4 font-weight-normal border-0'></th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Team</th>
                                    <th class='text-gray-4 font-weight-normal border-0'>Name</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- / player-table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / contestant-wrapper -->

<div id="footer-section" class="container-fluid py-5 bg-gray-2">
    <div class="row">
        <div class="col-lg-12 text-gray-4">
            <p class="text-center">Copyright&copy;2019 - <span class="text-success">Scoreboard v<?php echo SCOREBOARD_VERSION; ?></span> | <a class="text-warning" href="http://cbusmultimedia.com/" target="_blank">CBUS Multimedia</a></p>
        </div>
    </div>
</div>
<!-- / footer-section -->

<?php include 'templates/team/modal-form.php'; ?>
<!-- / form-team-modal -->

<?php include 'templates/player/modal-form.php'; ?>
<!-- / form-player-modal -->

<?php include 'templates/gamedraw/modal-form.php'; ?>
<!-- / form-gamedraw-modal -->

<?php include 'templates/gameset/modal-form.php'; ?>
<!-- / form-gameset-modal -->

<?php include 'templates/gamedraw/modal-info.php'; ?>
<!-- / gamedraw-info-modal -->

<?php include 'templates/gameset/modal-info.php'; ?>
<!-- / gameset-info-modal -->

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
<script src="js/print.min.js"></script>
</body>
</html>