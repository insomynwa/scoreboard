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
                            <div id="scoreboard-style-style-select-wrapper" class="col input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="scoreboard-style-style-select">Style</label>
                                </div>
                                <select class="custom-select shadow-none scoreboard-style-select" name="style" id="scoreboard-style-style-select">
                                    <option value="0">Choose</option>
                                </select>
                            </div>
                            <div id="scoreboard-style-style-name-wrapper" class="col input-group hide">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="scoreboard-style-style-name">Name</label>
                                </div>
                                <input class="form-control" type="text" name="style_name" id="scoreboard-style-style-name">
                            </div>
                            <div class="col">
                                <div>
                                    <input type="button" value="Activate" class="btn btn-outline-success hide" id="scoreboard-style-activate">
                                    <input type="hidden" id="form-scoreboard-style-mode" name="" value="view">
                                    <input type="hidden" id="scoreboard-style-action" name="scoreboard_style_action" value="view">
                                    <input type="submit" value="Save" class="btn btn-primary hide" id="scoreboard-style-btn-save">
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


<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="js/print.min.js"></script>
</body>
</html>