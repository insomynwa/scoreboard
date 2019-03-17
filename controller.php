<?php

include_once 'config/database.php';
include_once 'objects/team.php';
include_once 'objects/player.php';
include_once 'objects/gamemode.php';
// include_once 'objects/contestant.php';
include_once 'objects/gamedraw.php';
include_once 'objects/gamestatus.php';
include_once 'objects/gameset.php';
include_once 'objects/score.php';
/* include_once 'objects/game.php'; */

// Get Scoreboard
if (isset( $_GET['GetGameSet']) && $_GET['GetGameSet'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameSet'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $tempRes = $gameset->GetGameSets();
        $database->conn->close();

        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            $result['gamesets'] = $tempRes['gamesets'];
        }
    }
    echo json_encode($result);
}

// Get Score
if (isset( $_GET['Score']) && $_GET['Score'] != '' && isset( $_GET['draw']) && $_GET['draw'] != '' && isset( $_GET['set']) && $_GET['set'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['Score'] == 'get') {
        $result['score'] = null;
        $gamedraw_id = isset($_GET['draw']) ? $_GET['draw'] : 0;
        $gameset_id = isset($_GET['set']) ? $_GET['set'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $gameset->SetID( $gameset_id );
        $tempRes = $gameset->GetGameSetByID();

        if( $tempRes['status'] ){
            $result['score']['gameset'] = $tempRes['gameset'];

            $contestant_a = $tempRes['gameset']['gamedraw']['contestant_a']['id'];
            $contestant_b = $tempRes['gameset']['gamedraw']['contestant_b']['id'];
            $score = new Score($db);
            $score->SetGameSetID( $gameset_id );
            $score->SetContestantID( $contestant_a );
            $tempRes = $score->GetScoreByGameSetAndContestant();
            if( $tempRes['status'] ){
                $result['score']['gameset']['score_a'] = $tempRes['score'];
            }else{
                $result['message'] = 'ERROR: Load Score';
            }
            $score->SetContestantID( $contestant_b );
            $tempRes = $score->GetScoreByGameSetAndContestant();
            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['score']['gameset']['score_b'] = $tempRes['score'];
            }else{
                $result['message'] = 'ERROR: Load Score';
            }
        }else{
            $result['message'] = 'ERROR: Load Game Set';
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Get Game Draws
if (isset( $_GET['getGameDraw']) && $_GET['getGameDraw'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getGameDraw'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $teams = new GameDraw($db);
        $tempRes = $teams->GetGameDraws();

        $database->conn->close();

        if( $tempRes['status'] ){
            $result['gamedraws'] = $tempRes['gamedraws'];
            $result['status'] = $tempRes['status'];
        }else{
            $result['message'] = 'ERROR: Load Game Draws';
        }

    }
    echo json_encode($result);
}

// Create Scoreboard
if ( isset( $_POST['gameset_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['gameset_action'] == 'create') {

        $scoreboarddata['gamedraw_id'] = isset($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
        $scoreboarddata['setnum'] = isset($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;

        $database = new Database();
        $db = $database->getConnection();

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gameset = new GameSet( $db );
        $gameset->SetGameDrawID( $scoreboarddata['gamedraw_id'] );
        $gameset->SetNum( $scoreboarddata['setnum'] );
        $tempRes = $gameset->CreateSet();

        if( $tempRes['status'] ){
            $scoreboarddata['gameset_id'] = $tempRes['latest_id'];

            $gamedraw = new GameDraw( $db );
            $gamedraw->SetID( $scoreboarddata['gamedraw_id'] );
            $tempRes = $gamedraw->GetGameDrawByID();//var_dump($tempRes);echo json_encode($tempRes['gamestatus']);

            if( $tempRes['status'] ){
                $tempContestantAID = $tempRes['gamedraw']['contestant_a']['id'];
                $tempContestantBID = $tempRes['gamedraw']['contestant_b']['id'];
                $score = new Score( $db );
                $score->SetGameSetID( $scoreboarddata['gameset_id'] );
                $score->SetContestantID( $tempContestantAID );
                $tempRes = $score->CreateScore();
                $score->SetContestantID( $tempContestantBID );
                $tempRes = $score->CreateScore();

                if( $tempRes['status'] ){
                    $result['status'] = $tempRes['status'];
                }else{
                    $result['message'] = 'ERROR: Create Score';
                }
            }else{
                $result['message'] = 'ERROR: Load Game Draw';
            }

        }else{
            $result['message'] = 'ERROR: Create Game Set';
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Get Teams
if (isset( $_GET['getTeam']) && $_GET['getTeam'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getTeam'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $teams = new Team($db);
        $tempRes = $teams->GetTeams();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['teams'] = $tempRes['teams'];
        }
    }
    echo json_encode($result);
}

// Get Game Mode
if (isset( $_GET['getGameMode']) && $_GET['getGameMode'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getGameMode'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gamemode = new GameMode($db);
        $tempRes = $gamemode->GetGameModes();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['gamemodes'] = $tempRes['gamemodes'];
        }
    }
    echo json_encode($result);
}

// Get Teams By id [IMPORTANT EXAMPLE FOR VMIX]
if (isset( $_GET['getTeamById']) && $_GET['getTeamById'] != '') {
    $team = getTeamById($_GET['getTeamById']);
    echo "[". json_encode($team['team']) . "]";
}

// Get Team Players
if (isset( $_GET['GetPlayersByTeam']) && $_GET['GetPlayersByTeam'] != '') {
    $result = array(
        'status'    => false,
    );
    $players = getPlayersByTeam($_GET['GetPlayersByTeam']);
    if( $players['status'] ){
        $result = $players;
    }
    echo json_encode($result);
}

// Get Game Set by Game Draw
if (isset( $_GET['GetGameSetsByGameDraw']) && $_GET['GetGameSetsByGameDraw'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gamedraw_id = isset($_GET['GetGameSetsByGameDraw']) ? $_GET['GetGameSetsByGameDraw'] : 0;
    if($gamedraw_id > 0){

        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $gameset->SetGameDrawID($gamedraw_id);
        $tempRes = $gameset->GetGameSetsByGameDraw();

        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            $result['gamesets'] = $tempRes['gamesets'];
        }else{
            $result['message'] = "ERROR: Load Game Set";
        }
        $database->conn->close();
    }else{
        $result['message'] = "ERROR: Game Draw ID = 0";
    }
    echo json_encode($result);
}

// Create Game Draw
if ( isset( $_POST['gamedraw_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['gamedraw_action'] == 'create') {

        $contestant_a_id = 0;
        $contestant_b_id = 0;
        $gamemode_id = isset($_POST['gamedraw_gamemode']) ? $_POST['gamedraw_gamemode'] : 0;

        /*
        * TO-DO:
        * Harus dinamis
        * Game Status
        */
        if( $gamemode_id == 1 ) {   // ID Beregu
            $contestant_a_id = isset($_POST['gamedraw_team_a']) ? $_POST['gamedraw_team_a'] : 0;
            $contestant_b_id = isset($_POST['gamedraw_team_b']) ? $_POST['gamedraw_team_b'] : 0;
        }else if( $gamemode_id == 2) {// ID Individu
            $contestant_a_id = isset($_POST['gamedraw_player_a']) ? $_POST['gamedraw_player_a'] : 0;
            $contestant_b_id = isset($_POST['gamedraw_player_b']) ? $_POST['gamedraw_player_b'] : 0;
        }

        if( $contestant_a_id != 0 && $contestant_b_id != 0 ){

            $database = new Database();
            $db = $database->getConnection();

            /* $contestant = new Contestant($db);
            $contestant->SetContestantAID($contestant_a_id);
            $contestant->SetContestantBID($contestant_b_id);
            $tempRes = $contestant->CreateContestant();

            if( $tempRes['status'] ){

                $game_data = array(
                    'num'  => isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 0,
                    'contestant_id'    => $tempRes['latest_id'],
                    'gamemode_id'  => $gamemode_id
                ); */

                $gamenum = isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;

                $gamedraw = new GameDraw($db);
                $gamedraw->SetNum($gamenum);
                $gamedraw->SetGameModeID($gamemode_id);
                // $contestant->SetContestantID($game_data['contestant_id']);
                $gamedraw->SetContestantAID($contestant_a_id);
                $gamedraw->SetContestantBID($contestant_b_id);
                $tempRes = $gamedraw->CreateGameDraw();

                if( $tempRes['status'] ){
                    $result['next_num'] = $gamenum + 1;
                    $result['status'] = $tempRes['status'];
                }else{
                    $result['message'] = "ERROR: Create Game Draw";
                }

            /* }else{
                $result['message'] = "ERROR: Create Contestant";
            } */

            $database->conn->close();

        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    echo json_encode($result);
}

// Get Players
if (isset( $_GET['getPlayer']) && $_GET['getPlayer'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ""
    );
    if( $_GET['getPlayer'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $players = new Player($db);
        $tempRes = $players->GetPlayers();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['players'] = $tempRes['players'];
            $result['message'] = "success";
        }else{
            $result['message'] = "ERROR: load players";
        }
    }
    echo json_encode($result);
}

// Create Player
if ( isset( $_POST['player_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['player_action'] == 'create') {

        $playerData = array(
            'name'   => isset($_POST['player_name']) ? $_POST['player_name'] : '',
            'team_id'       =>  isset($_POST['player_team']) ? $_POST['player_team'] : 0
        );

        if( $playerData['name'] == '' ){
            $result['message'] = "ERROR: player name is required!";
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetName( $playerData['name'] );
            $player->SetTeamId( $playerData['team_id'] );
            $tempRes = $player->CreatePlayer();
            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
            }

            $database->conn->close();
        }

    }
    echo json_encode($result);
}

// Update Score
if ( isset( $_POST['score_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['score_action'] != ''){
        $gamedraw_id = 0;
        $gameset_id = 0;
        $score_id = 0;
        $timer = 120;
        $setpoints = 0;
        $desc = "";
        $score_data = array();
        if( $_POST['score_action'] == 'update-a'){

            $gamedraw_id = isset($_POST['score_a_gamedraw_id']) ? $_POST['score_a_gamedraw_id'] : 0;
            $gameset_id = isset($_POST['score_a_gameset_id']) ? $_POST['score_a_gameset_id'] : 0;
            $score_id = isset($_POST['score_a_id']) ? $_POST['score_a_id'] : 0;
            $timer = isset($_POST['score_a_timer']) ? str_replace("s","",$_POST['score_a_timer']) : 0;
            $setpoints = isset($_POST['score_a_setpoints']) ? $_POST['score_a_setpoints'] : 0;
            $desc = isset($_POST['score_a_desc']) ? $_POST['score_a_desc'] : "";

            $score_data = array(
                'pts1'  =>  isset($_POST['score_a_pt1']) ? $_POST['score_a_pt1'] : 0,
                'pts2'  =>  isset($_POST['score_a_pt2']) ? $_POST['score_a_pt2'] : 0,
                'pts3'  =>  isset($_POST['score_a_pt3']) ? $_POST['score_a_pt3'] : 0,
                'pts4'  =>  isset($_POST['score_a_pt4']) ? $_POST['score_a_pt4'] : 0,
                'pts5'  =>  isset($_POST['score_a_pt5']) ? $_POST['score_a_pt5'] : 0,
                'pts6'  =>  isset($_POST['score_a_pt6']) ? $_POST['score_a_pt6'] : 0,
            );

        }else if( $_POST['score_action'] == 'update-b'){

            $gamedraw_id = isset($_POST['score_b_gamedraw_id']) ? $_POST['score_b_gamedraw_id'] : 0;
            $gameset_id = isset($_POST['score_b_gameset_id']) ? $_POST['score_b_gameset_id'] : 0;
            $score_id = isset($_POST['score_b_id']) ? $_POST['score_b_id'] : 0;
            $timer = isset($_POST['score_b_timer']) ? str_replace("s","",$_POST['score_b_timer']) : 0;
            $setpoints = isset($_POST['score_b_setpoints']) ? $_POST['score_b_setpoints'] : 0;
            $desc = isset($_POST['score_b_desc']) ? $_POST['score_b_desc'] : "";

            $score_data = array(
                'pts1'  =>  isset($_POST['score_b_pt1']) ? $_POST['score_b_pt1'] : 0,
                'pts2'  =>  isset($_POST['score_b_pt2']) ? $_POST['score_b_pt2'] : 0,
                'pts3'  =>  isset($_POST['score_b_pt3']) ? $_POST['score_b_pt3'] : 0,
                'pts4'  =>  isset($_POST['score_b_pt4']) ? $_POST['score_b_pt4'] : 0,
                'pts5'  =>  isset($_POST['score_b_pt5']) ? $_POST['score_b_pt5'] : 0,
                'pts6'  =>  isset($_POST['score_b_pt6']) ? $_POST['score_b_pt6'] : 0,
            );
        }

        $database = new Database();
        $db = $database->getConnection();

        $score = new Score($db);
        $score->SetID($score_id);
        $score->SetTimer($timer);
        $score->SetScore1($score_data['pts1']);
        $score->SetScore2($score_data['pts2']);
        $score->SetScore3($score_data['pts3']);
        $score->SetScore4($score_data['pts4']);
        $score->SetScore5($score_data['pts5']);
        $score->SetScore6($score_data['pts6']);
        $score->SetPoint($setpoints);
        $score->SetDesc($desc);
        $tempRes = $score->UpdateScore();

        if( $tempRes['status'] ){

            // $gameset = new GameSet($db);
            // $gameset->SetID($gameset_id);
            // $gameset->SetPoint($setpoints);
            // $tempRes = $gameset->UpdateGameSet();

            // if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
            // }else{
            //     $result['message'] = "ERROR: Update Game Set";
            // }
        }else{
            $result['message'] = "ERROR: Update Score";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

function getTeamById( $teamid ){

    $database = new Database();
    $db = $database->getConnection();

    $o_team = new Team($db);
    $team = $o_team->getTeamById( $teamid);

    $database->conn->close();
    return $team;
}

function getPlayersByTeam($teamid){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $players = $o_player->getPlayersByTeam($teamid);

    $database->conn->close();
    return $players;
}

/* function getTeamsByGame($gameid){

    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $teams = $o_game->getTeamsByGame($gameid);

    $database->conn->close();
    return $teams;
}

function getGameSets(){

    $database = new Database();
    $db = $database->getConnection();

    $o_gameset = new GameSet($db);
    $gameset = $o_gameset->getGameSets();

    $database->conn->close();
    return $gameset;
} */

?>