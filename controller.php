<?php

include_once 'config/database.php';
include_once 'objects/team.php';
include_once 'objects/player.php';
include_once 'objects/gamemode.php';
include_once 'objects/gamedraw.php';
include_once 'objects/gamestatus.php';
include_once 'objects/gameset.php';
include_once 'objects/score.php';
include_once 'objects/vmixlive.php';


$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
$path = 'uploads/'; // upload directory
// CUD Team
if(isset( $_POST['team_action'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $team_action = $_POST['team_action'];
    if( $team_action=='create' || $team_action=='update'){
        $upload_status = false;

        $name = $_POST['team_name'];
        $team_initial = strtoupper( substr( $name, 0, 3) );
        $team_desc = $_POST['team_desc'];

        $img = $_FILES['team_logo']['name'];
        $tmp = $_FILES['team_logo']['tmp_name'];
        $is_upload = ($img != "");
        $final_image = "";
        $sql = "";

        if( $is_upload ) {

            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            // can upload same image using rand function
            $final_image = rand(1000,1000000).$img;

            // check's valid format
            if(in_array($ext, $valid_extensions) )
            {
                $path = $path.strtolower($final_image);
                /* $pathB = $path.strtolower($final_imageB);  */

                if(move_uploaded_file($tmp,$path)/*  && move_uploaded_file($tmpB,$pathB) */)
                {
                    $upload_status = true;

                    //include database configuration file
                    // include_once '../config/database.php';

                    $database = new Database();
                    $db = $database->getConnection();
                    $prev_team_logo = "";

                    if( $team_action == 'create' ){
                        $result['action'] = "create";
                        $sql = "INSERT team (team_name,team_logo,team_initial,team_desc) VALUES ('".$name."','". strtolower($final_image) ."', '". $team_initial ."', '". $team_desc ."' )";
                    }else if ( $team_action == 'update'){
                        $team_id = $_POST['team_id'];
                        $teamCls = new Team($db);
                        $teamCls->SetID($team_id);
                        $resTeamLogo = $teamCls->GetLogo();
                        $result['new_logo'] = '';
                        if($resTeamLogo['status'] && $resTeamLogo['has_value']){
                            $prev_team_logo = $resTeamLogo['logo'];
                            $result['new_logo'] = $final_image;
                        }
                        $result['action'] = "update";
                        $sql = "UPDATE team SET team_logo='{$final_image}', team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";
                    }

                    $tempRes = $db->query($sql);
                    $database->conn->close();
                    if ($tempRes){
                        $result['message'] = "Success";
                        $result['status'] = true;
                        if($team_action=='update'){
                            unlink(dirname(__FILE__) . "/uploads/" . $prev_team_logo );
                        }
                    }else{
                        $result['message'] = "ERROR: create/update data";
                    }
                }else{
                    if ( $team_action == 'update'){
                        $result['action'] = "update";
                        $team_id = $_POST['team_id'];
                        $sql = "UPDATE team SET team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";

                        //include database configuration file
                        // include_once '../config/database.php';

                        $database = new Database();
                        $db = $database->getConnection();

                        $tempRes = $db->query($sql);
                        $database->conn->close();
                        if ($tempRes){
                            $result['message'] = "ERROR: update logo, SUCCESS: update data";
                            $result['status'] = true;
                        }else{
                            $result['message'] = "ERROR: update team";
                        }
                    }else{
                        $result['message'] = "ERROR: upload logo";
                    }
                }
            }
            else
            {
                $result['message'] = "ERROR: invalid format";
            }
        }else{
            if ( $team_action == 'update'){
                $team_id = $_POST['team_id'];
                $sql = "UPDATE team SET team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";

                $database = new Database();
                $db = $database->getConnection();

                $tempRes = $db->query($sql);
                $database->conn->close();
                if ($tempRes){
                    $result['message'] = "ERROR: update logo, SUCCESS: update data";
                    $result['status'] = true;
                }else{
                    $result['message'] = "ERROR: no-logo, update team";
                }
            }else{
                $result['message'] = "ERROR: upload logo";
            }
        }
    }
    else if ($team_action == 'delete'){
        $teamid = isset($_POST['team_id']) ? $_POST['team_id'] : 0;

        if( $teamid == 0 ){
            $result['message'] = "ERROR: team id 0";
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $team = new Team($db);
            $team->SetID($teamid);
            $resTeam = $team->GetTeamByID();
            if($resTeam['status']){
                $teamPlayers = $team->GetPlayers();
                for( $i=0; $i<sizeof($teamPlayers); $i++){
                    $teamPlayer = new Player($db);
                    $teamPlayer->SetID($teamPlayers[$i]['id']);
                    $teamPlayerGameDraws = $teamPlayer->GetGameDraws();
                    for( $j=0; $j<sizeof($teamPlayerGameDraws); $j++){
                        $teamPlayerGameDraw = new GameDraw($db);
                        $teamPlayerGameDraw->SetID($teamPlayerGameDraws[$j]['id']);
                        $teamPlayerGameDrawsSets = $teamPlayerGameDraw->GetGameSets();
                        for( $k=0; $k<sizeof($teamPlayerGameDrawsSets); $k++){
                            $teamPlayerGameDrawsSet = new GameSet($db);
                            $teamPlayerGameDrawsSet->SetID($teamPlayerGameDrawsSets[$k]['id']);
                            $teamPlayerGameDrawsSetScores = $teamPlayerGameDrawsSet->GetScores();
                            for( $l=0; $l<sizeof($teamPlayerGameDrawsSetScores); $l++){
                                $teamPlayerGameDrawsSetScore = new Score($db);
                                $teamPlayerGameDrawsSetScore->SetID($teamPlayerGameDrawsSetScores[$l]['id']);
                                $teamPlayerGameDrawsSetScore->DeleteScore();
                            }
                            $teamPlayerGameDrawsSet->DeleteGameSet();
                        }
                        $teamPlayerGameDraw->DeleteGameDraw();
                    }
                    $teamPlayer->DeletePlayer();
                }
                $teamGameDraws = $team->GetGameDraws();
                for( $i=0; $i<sizeof($teamGameDraws); $i++){
                    $teamGamedraw = new GameDraw($db);
                    $teamGamedraw->SetID($teamGameDraws[$i]['id']);
                    $teamGameDrawsSets = $teamGamedraw->GetGameSets();
                    for( $k=0; $k<sizeof($teamGameDrawsSets); $k++){
                        $teamGameDrawsSet = new GameSet($db);
                        $teamGameDrawsSet->SetID($teamGameDrawsSets[$k]['id']);
                        $teamGameDrawsSetScores = $teamGameDrawsSet->GetScores();
                        for( $l=0; $l<sizeof($teamGameDrawsSetScores); $l++){
                            $teamGameDrawsSetScore = new Score($db);
                            $teamGameDrawsSetScore->SetID($teamGameDrawsSetScores[$l]['id']);
                            $teamGameDrawsSetScore->DeleteScore();
                        }
                        $teamGameDrawsSet->DeleteGameSet();
                    }
                    $teamGamedraw->DeleteGameDraw();
                }
            }else{
                $result['message'] = "ERROR: Get Team";
            }
            // Delete LOGO
            unlink(dirname(__FILE__) . "/uploads/" . $resTeam['team']['logo'] );
            $tempRes = $team->DeleteTeam();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'delete';
            }else{
                $result['message'] = "ERROR: Delete Team";
            }

        }
    }
    echo json_encode($result);
}

// CUD Player
if ( isset( $_POST['player_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['player_action'] == 'create') {

        $player_name = isset($_POST['player_name']) ? $_POST['player_name'] : '';
        $team_id = isset($_POST['player_team']) ? $_POST['player_team'] : 0;

        if( $player_name == '' ){
            $result['message'] = "ERROR: player name is required!";
            $result['action'] = 'create';
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetName( $player_name );
            $player->SetTeamId( $team_id );
            $tempRes = $player->CreatePlayer();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'create';
            }else{
                $result['message'] = "ERROR: create player";
            }

        }

    }else if( $_POST['player_action'] == 'update') {

        $name = isset($_POST['player_name']) ? $_POST['player_name'] : '';
        $teamid = isset($_POST['player_team']) ? $_POST['player_team'] : 0;
        $playerid = isset($_POST['player_id']) ? $_POST['player_id'] : 0;

        if( $name == '' || $teamid == 0 || $playerid == 0 ){
            $result['message'] = "ERROR: All fields are required!";
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetID( $playerid );
            $player->SetName( $name );
            $player->SetTeamId( $teamid );
            $tempRes = $player->UpdatePlayer();
            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'update';
            }

            $database->conn->close();
        }

    }else if( $_POST['player_action'] == 'delete') {

        $playerid = isset($_POST['player_id']) ? $_POST['player_id'] : 0;

        if( $playerid == 0 ){
            $result['message'] = "ERROR: player id 0";
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetID( $playerid );
            $resPlayer = $player->GetPlayerByID();
            if($resPlayer['status']){
                $playerGameDraws = $player->GetGameDraws();
                for( $i=0; $i<sizeof($playerGameDraws); $i++){
                    $playerGameDraw = new GameDraw($db);
                    $playerGameDraw->SetID($playerGameDraws[$i]['id']);
                    $playerGameDrawGameSets = $playerGameDraw->GetGameSets();
                    for( $j=0; $j<sizeof($playerGameDrawGameSets); $j++){
                        $playerGameDrawGameSet = new GameSet($db);
                        $playerGameDrawGameSet->SetID($playerGameDrawGameSets[$j]['id']);
                        $playerGameDrawGameSetScores = $playerGameDrawGameSet->GetScores();
                        for( $k=0; $k<sizeof($playerGameDrawGameSetScores); $k++){
                            $playerGameDrawGameSetScore = new Score($db);
                            $playerGameDrawGameSetScore->SetID($playerGameDrawGameSetScores[$k]['id']);
                            $playerGameDrawGameSetScore->DeleteScore();
                        }
                        $playerGameDrawGameSet->DeleteGameSet();
                    }
                    $playerGameDraw->DeleteGameDraw();
                }
            }else{
                $result['message'] = "ERROR: Load Player";
            }

            $tempRes = $player->DeletePlayer();
            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'delete';
            }else{
                $result['message'] = "ERROR: Delete Player " . $playerid;
            }

            $database->conn->close();
        }

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
        if( $gamemode_id == 1 ) {// ID Beregu
            $contestant_a_id = isset($_POST['gamedraw_team_a']) ? $_POST['gamedraw_team_a'] : 0;
            $contestant_b_id = isset($_POST['gamedraw_team_b']) ? $_POST['gamedraw_team_b'] : 0;
        }else if( $gamemode_id == 2) {// ID Individu
            $contestant_a_id = isset($_POST['gamedraw_player_a']) ? $_POST['gamedraw_player_a'] : 0;
            $contestant_b_id = isset($_POST['gamedraw_player_b']) ? $_POST['gamedraw_player_b'] : 0;
        }

        if( $contestant_a_id > 0 && $contestant_b_id > 0 ){

            $database = new Database();
            $db = $database->getConnection();

            $gamedraw_num = isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;

            $gamedraw = new GameDraw($db);
            $gamedraw->SetNum($gamedraw_num);
            $gamedraw->SetGameModeID($gamemode_id);
            // $contestant->SetContestantID($game_data['contestant_id']);
            $gamedraw->SetContestantAID($contestant_a_id);
            $gamedraw->SetContestantBID($contestant_b_id);
            $tempRes = $gamedraw->CreateGameDraw();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['next_num'] = $gamedraw_num + 1;
                $result['action'] = 'create';
                $result['status'] = $tempRes['status'];
            }else{
                $result['message'] = "ERROR: Create Game Draw";
            }

        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    else if( $_POST['gamedraw_action'] == 'update') {

        $contestant_a_id = 0;
        $contestant_b_id = 0;
        $gamemode_id = isset($_POST['gamedraw_gamemode']) ? $_POST['gamedraw_gamemode'] : 0;
        $gamedraw_id = isset($_POST['gamedraw_id']) ? $_POST['gamedraw_id'] : 0;

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

            $gamenum = isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;

            $gamedraw = new GameDraw($db);
            $gamedraw->SetID($gamedraw_id);
            $gamedraw->SetNum($gamenum);
            // $gamedraw->SetGameModeID($gamemode_id);
            // $contestant->SetContestantID($game_data['contestant_id']);
            // $gamedraw->SetContestantAID($contestant_a_id);
            // $gamedraw->SetContestantBID($contestant_b_id);
            $tempRes = $gamedraw->UpdateGameDraw();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['action'] = 'update';
                $result['status'] = $tempRes['status'];
            }else{
                $result['message'] = "ERROR: Update Game Draw";
            }


        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    else if( $_POST['gamedraw_action'] == 'delete') {
        $gamedraw_id = isset($_POST['gamedraw_id']) ? $_POST['gamedraw_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw( $db );
        $gamedraw->SetID( $gamedraw_id );
        $resGameDraw = $gamedraw->GetGameDrawByID();

        if ($resGameDraw['status']){
            $gameDrawGameSets = $gamedraw->GetGameSets();
            for( $i=0; $i<sizeof($gameDrawGameSets); $i++){
                $gameDrawGameSet = new GameSet($db);
                $gameDrawGameSet->SetID($gameDrawGameSets[$i]['id']);
                $gameDrawGameSetScores = $gameDrawGameSet->GetScores();
                for( $k=0; $k<sizeof($gameDrawGameSetScores); $k++){
                    $gameDrawGameSetScore = new Score($db);
                    $gameDrawGameSetScore->SetID($gameDrawGameSetScores[$k]['id']);
                    $gameDrawGameSetScore->DeleteScore();
                }
                $gameDrawGameSet->DeleteGameSet();
            }
        }else{
            $result['message'] = 'ERROR: Get Game Draw';
        }
        $tempRes = $gamedraw->DeleteGameDraw();
        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            $result['action'] = 'delete';
        }else{
            $result['message'] = "ERROR: Delete Game Draw";
        }

        $database->conn->close();

        /* if( $tempRes['status'] ){
            $gameset = new GameSet( $db );
            $gameset->SetGameDrawID( $gamedraw_id );
            $countGameSetsByGameDraw = $gameset->CountGameSetsByGameDraw();

            if($countGameSetsByGameDraw>0){
                $resGameSets = $gameset->GetGameSetsByGameDraw();

                if($resGameSets['status']){
                    $sizeGameSet = sizeof($resGameSets['gamesets']);
                    $tempStatus = true;
                    for($i=0; $i<$sizeGameSet; $i++){
                        $tempGameset = $resGameSets['gamesets'][$i];
                        $gameset->SetID($tempGameset['id']);
                        $tempResGS = $gameset->DeleteGameSet();

                        $score = new Score($db);
                        $score->SetGameSetID( $tempGameset['id']);
                        $tempResScore = $score->DeleteScoreByGameSetID();

                        $tempStatus = $tempStatus && $tempResGS['status'] && $tempResScore['status'];
                    }

                    if( $tempStatus ){
                        $result['status'] = $tempStatus;
                        $result['action'] = 'delete';
                    }else{
                        $result['message'] = 'ERROR: Delete Game Set / Score';
                    }

                }else{
                    $result['message'] = 'ERROR: Load Game Set by Game Draw';
                }
            }else{
                $result['status'] = true;
                $result['action'] = 'delete';
                $result['message'] = 'Has no game set';
            }
        }else{
            $result['message'] = 'ERROR: Delete Game Draw';
        } */
    }
    echo json_encode($result);
}

// Create Game Set
if ( isset( $_POST['gameset_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['gameset_action'] == 'create') {

        $gamedraw_id = isset($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
        $gameset_num = isset($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;

        if($gamedraw_id>0 && $gameset_num>0){

            $database = new Database();
            $db = $database->getConnection();

            $gameset = new GameSet( $db );
            $gameset->SetGameDrawID( $gamedraw_id );
            $gameset->SetNum( $gameset_num );
            $resGameSet = $gameset->CreateSet();

            if( $resGameSet['status'] ){
                $gameset_id = $resGameSet['latest_id'];

                $gamedraw = new GameDraw( $db );
                $gamedraw->SetID( $gamedraw_id );
                $resGameDraw = $gamedraw->GetGameDrawByID();

                if( $resGameDraw['status'] ){
                    $contestant_a_id = $resGameDraw['gamedraw']['contestant_a_id'];
                    $contestant_b_id = $resGameDraw['gamedraw']['contestant_b_id'];
                    $gamemode_id = $resGameDraw['gamedraw']['gamemode_id'];

                    $score = new Score( $db );
                    $score->SetGameSetID( $gameset_id );
                    $score->SetContestantID( $contestant_a_id );
                    $resScore = $score->CreateScore();

                    if( $resScore['status'] ){
                        $result['status'] = $resScore['status'];
                        $result['action'] = 'create';
                    }else{
                        $result['message'] = 'ERROR: Create Score';
                    }
                    $score->SetContestantID( $contestant_b_id );
                    $resScore = $score->CreateScore();

                    if( $resScore['status'] ){
                        $result['status'] = $resScore['status'] && $result['status'];
                        $result['action'] = 'create';
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
        }else{
            $result['message'] = "ERROR: id -> 0";
        }

        /*
        * TO-DO: Set Num verif. & valid.
        */

    }
    else if( $_POST['gameset_action'] == 'update') {

        $gamedraw_id = isset($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
        $gameset_num = isset($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;
        $gameset_id = isset($_POST['gameset_id']) ? $_POST['gameset_id'] : 0;
        $gameset_status = isset($_POST['gameset_status']) ? $_POST['gameset_status'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gameset = new GameSet( $db );
        $gameset->SetID( $gameset_id );
        $gameset->SetNum( $gameset_num );
        $gameset->SetStatus ( $gameset_status );
        $tempRes = $gameset->UpdateGameSet();

        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            $result['action'] = 'update';
        }else{
            $result['message'] = 'ERROR: Update Game Set';
        }
    }
    else if( $_POST['gameset_action'] == 'delete') {
        $gameset_id = isset($_POST['gameset_id']) ? $_POST['gameset_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gameset = new GameSet( $db );
        $gameset->SetID( $gameset_id );
        $resGameSet = $gameset->GetGameSetByID();

        if ($resGameSet['status']){
            $gameSetScores = $gameset->GetScores();
            for( $i=0; $i<sizeof($gameSetScores); $i++){
                $gameSetScore = new Score($db);
                $gameSetScore->SetID( $gameSetScores[$i]['id'] );
                $gameSetScore->DeleteScore();
            }
        }else{
            $result['message'] = "ERROR: Load Game Set";
        }
        $tempRes = $gameset->DeleteGameSet();
        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            $result['action'] = 'delete';
        }else{
            $result['message'] = "ERROR: Delete Game Set";
        }
        $database->conn->close();

        /* if( $tempRes['status'] ){
            $score = new Score( $db );
            $score->SetGameSetID( $gameset_id );
            $tempRes = $score->DeleteScoreByGameSetID();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'delete';
            }else{
                $result['message'] = 'ERROR: Delete Score';
            }
        }else{
            $result['message'] = 'ERROR: Delete Game Set';
        } */
    }
    echo json_encode($result);
}

// Get Score
/* if (isset( $_GET['Score']) && $_GET['Score'] != '' && isset( $_GET['draw']) && $_GET['draw'] != '' && isset( $_GET['set']) && $_GET['set'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['Score'] == 'get') {
        $result['score'] = array();
        $gamedraw_id = isset($_GET['draw']) ? $_GET['draw'] : 0;
        $gameset_id = isset($_GET['set']) ? $_GET['set'] : 0;

        if( $gamedraw_id > 0 && $gameset_id > 0){

            $database = new Database();
            $db = $database->getConnection();

            $vmixlive = new VMIX_LIVE($db);
            $vmixlive->SetGameSetID($gameset_id);
            $resVMIX = $vmixlive->UpdateLiveGame();

            if($resVMIX['status']){

                $gamedraw = new GameDraw($db);
                $gamedraw->SetID($gamedraw_id);
                $resGameDraw = $gamedraw->GetGameDrawByID();
                $contestant_a = array();
                $contestant_b = array();

                if($resGameDraw['status']){
                    $result['score']['gamedraw'] = $resGameDraw['gamedraw'];
                    $contestant_a_id = $resGameDraw['gamedraw']['contestant_a_id'];
                    $contestant_b_id = $resGameDraw['gamedraw']['contestant_b_id'];
                    $gamemode_id = $resGameDraw['gamedraw']['gamemode_id'];

                    // Contestant
                    if($contestant_a_id > 0 && $contestant_b_id > 0){
                        if($gamemode_id==1) { // Beregu
                            $team = new Team($db);
                            $team->SetID($contestant_a_id);
                            $resTeam = $team->GetTeamByID();
                            if($resTeam['status']){
                                $result['status'] = $resTeam['status'] && true;
                                $result['score']['contestant_a'] = $resTeam['team'];
                            }else{
                                $result['score']['contestant_a']['name'] = '-';
                            }
                            $team->SetID($contestant_b_id);
                            $resTeam = $team->GetTeamByID();
                            if($resTeam['status']){
                                $result['status'] = $resTeam['status'] && $result['status'];
                                $result['score']['contestant_b'] = $resTeam['team'];
                            }else{
                                $result['score']['contestant_b']['name'] = '-';
                            }
                        }else if( $gamemode_id ==2 ){ // Individu
                            $player = new Player($db);
                            $player->SetID($contestant_a_id);
                            $resPlayer = $player->GetPlayerByID();
                            if($resPlayer['status']){
                                $result['status'] = $resPlayer['status'] && true;
                                $result['score']['contestant_a'] = $resPlayer['player'];
                            }else{
                                $result['score']['contestant_a']['name'] = '-';
                            }
                            $player->SetID($contestant_b_id);
                            $resPlayer = $player->GetPlayerByID();
                            if($resPlayer['status']){
                                $result['status'] = $resPlayer['status'] && $result['status'];
                                $result['score']['contestant_b'] = $resPlayer['player'];
                            }else{
                                $result['score']['contestant_b']['name'] = '-';
                            }
                        }else{
                            $result['score']['contestant_a']['name'] = '-';
                            $result['score']['contestant_b']['name'] = '-';
                        }
                    }else{
                        $result['score']['contestant_a']['name'] = '-';
                        $result['score']['contestant_b']['name'] = '-';
                    }
                }else{
                    $result['message'] = "ERROR: Load Game Draw";
                }

                $gameset = new GameSet($db);
                $gameset->SetID( $gameset_id );
                $resGameSet = $gameset->GetGameSetByID();

                if($resGameSet['status']){
                    $result['status'] = $resGameSet['status'] && $result['status'];
                    $result['score']['gameset'] = $resGameSet['gameset'];
                }else{
                    $result['message'] = "ERROR: Load Game Draw";
                }

                $score = new Score($db);
                $score->SetGameSetID( $gameset_id );
                $score->SetContestantID( $result['score']['contestant_a']['id'] );
                $resScore = $score->GetScoreByGameSetAndContestant();

                if($resScore['status']){
                    $result['status'] = $resScore['status'] && $result['status'];
                    $result['score']['score_a'] = $resScore['score'];
                }else{
                    $result['message'] = "ERROR: Load Score";
                }

                $score->SetContestantID( $result['score']['contestant_b']['id'] );
                $resScore = $score->GetScoreByGameSetAndContestant();

                if($resScore['status']){
                    $result['status'] = $resScore['status'] && $result['status'];
                    $result['score']['score_b'] = $resScore['score'];
                }else{
                    $result['message'] = "ERROR: Load Score";
                }
            }else{
                $result['message'] = "ERROR: Set Live Game";
            }
        }else{
            $result['message'] = "ERROR: id -> 0";
        }

        $database->conn->close();
    }
    echo json_encode($result);
} */

// Get Live Score
if (isset( $_GET['GetLiveScore']) && $_GET['GetLiveScore'] != '' ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetLiveScore'] == '1') {
        $result['score'] = array();

        $database = new Database();
        $db = $database->getConnection();

        $vmixlive = new VMIX_LIVE($db);
        $resLiveGame = $vmixlive->GetLiveGameID();
        $gameset_id = 0;

        if($resLiveGame['status']){
            $gameset_id = $resLiveGame['live_game'];
        }
        $result['live_game'] = $gameset_id;
        $result['score']['gameset_id'] = $gameset_id;

        $gameset = new GameSet($db);
        $gameset->SetID( $gameset_id );
        $resGameSet = $gameset->GetGameSetByID();
        if($resGameSet['status']){
            $result['status'] = true;
            if($resGameSet['has_value']){
                $result['has_value'] = true;
                $gameset->SetGameDrawID($resGameSet['gameset']['gamedraw_id']);
                $resGameDraw = $gameset->GetGameDraw();
                $result['score']['gamedraw'] = $resGameDraw;

                if($resGameDraw['id']>0){

                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetContestantAID($resGameDraw['contestant_a_id']);
                    $gamedraw->SetContestantBID($resGameDraw['contestant_b_id']);

                    /**
                     * TO-DO: Dinamic Game ID
                     */
                    if($resGameDraw['gamemode_id']==1){
                        $result['score']['contestant_a'] = $gamedraw->GetTeamContestantA();
                        $result['score']['contestant_b'] = $gamedraw->GetTeamContestantB();
                    }else if($resGameDraw['gamemode_id']==2){
                        $result['score']['contestant_a'] = $gamedraw->GetPlayerContestantA();
                        $result['score']['contestant_b'] = $gamedraw->GetPlayerContestantB();
                        $result['scoreboard']['logo_a'] = "no-team.png";
                        $result['scoreboard']['logo_b'] = "no-team.png";
                    }

                    $resScores = $gameset->GetScores();
                    for( $i=0; $i<sizeof($resScores); $i++){
                        if($resScores[$i]['contestant_id']==$resGameDraw['contestant_a_id']){
                            $result['score']['score_a'] = $resScores[$i];
                        }else if($resScores[$i]['contestant_id']==$resGameDraw['contestant_b_id']){
                            $result['score']['score_b'] = $resScores[$i];
                        }
                    }
                    $result['status'] = true;
                    // $result['score']['gameset'] = $resGameSet['gameset'];
                }else{
                    $result['message'] = "ERROR: 0 Game Draw";
                }
            }else{
                $result['has_value'] = false;
            }
        }else{
            $result['message'] = "ERROR: Load Game Set";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Get Game Mode
if (isset( $_GET['GetGameModes']) && $_GET['GetGameModes'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameModes'] == 'all') {

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

// Get Game Status
if (isset( $_GET['GetGameStatus']) && $_GET['GetGameStatus'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameStatus'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gamestatus = new GameStatus($db);
        $resGameStatuses = $gamestatus->GetGameStatuses();

        $database->conn->close();

        $result['status'] = $resGameStatuses['status'];

        if( $result['status'] ){
            $result['gamestatuses'] = array();
            $result['has_value'] = $resGameStatuses['has_value'];
            if($result['has_value']){
                $result['gamestatuses'] = $resGameStatuses['gamestatuses'];
            }
        }
    }
    echo json_encode($result);
}

// Get Game Set by Game Draw
/* if (isset( $_GET['GetGameSetsByGameDraw']) && $_GET['GetGameSetsByGameDraw'] != '') {
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
} */

/* if (isset( $_GET['TestLoadGames']) && $_GET['TestLoadGames'] != "" ){
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if ($_GET['TestLoadGames'] == 'all') {

        $result['gamedraws'] = array();

        $database = new Database();
        $db = $database->getConnection();

        $live_game_id = 0;
        $vmixlive = new VMIX_LIVE($db);
        $resVMIX = $vmixlive->GetLiveGameID();
        if($resVMIX['status']){
            $live_game_id = $resVMIX['live_game'];
        }
        $result['live_game'] = $live_game_id;

        $gamedraw = new GameDraw($db);
        $resGameDraws = $gamedraw->GetGameDraws();

        if( $resGameDraws['status'] ){
            $result['status'] = $resGameDraws['status'];
            $gamedraws = $resGameDraws['gamedraws'];
            for( $i=0; $i<sizeof($gamedraws); $i++){
                $gamemode_id = $gamedraws[$i]['gamemode_id'];
                $contestant_a_id = $gamedraws[$i]['contestant_a_id'];
                $contestant_b_id = $gamedraws[$i]['contestant_b_id'];
                $gamestatus_id = $gamedraws[$i]['gamestatus_id'];

                $gamedraw->SetID($gamedraws[$i]['id']);
                $gamedraw->SetGameModeID($gamemode_id);
                $gamedraws[$i]['gamemode'] = $gamedraw->GetGameMode();
                $gamedraw->SetGameStatusID($gamestatus_id);
                $gamedraws[$i]['gamestatus'] = $gamedraw->GetGameStatus();

                $gamedraw->SetContestantAID($contestant_a_id);
                $gamedraw->SetContestantBID($contestant_b_id);
                $contestant_a = array();
                $contestant_b = array();
                // Contestant
                if($contestant_a_id > 0 && $contestant_b_id > 0){
                    if($gamemode_id==1) { // Beregu
                        $contestant_a = $gamedraw->GetTeamContestantA();
                        $contestant_b = $gamedraw->GetTeamContestantB();
                    }else if( $gamemode_id ==2 ){ // Individu
                        $contestant_a = $gamedraw->GetPlayerContestantA();
                        $contestant_b = $gamedraw->GetPlayerContestantB();
                        if($contestant_a['team_id']>0){
                            $player = new Player($db);
                            $player->SetTeamId($contestant_a['team_id']);
                            $team = $player->GetTeam();
                            $contestant_a['logo'] = $team['logo'];
                        }else{
                            $contestant_a['logo'] = "no-team.png";
                        }
                        if($contestant_b['team_id']>0){
                            $player = new Player($db);
                            $player->SetTeamId($contestant_b['team_id']);
                            $team = $player->GetTeam();
                            $contestant_b['logo'] = $team['logo'];
                        }else{
                            $contestant_b['logo'] = "no-team.png";
                        }
                    }
                }
                $gamedraws[$i]['contestant_a'] = $contestant_a;
                $gamedraws[$i]['contestant_b'] = $contestant_b;

                $gamedraws[$i]['gamesets'] = $gamedraw->GetGameSets();
                for($j=0; $j<sizeof($gamedraws[$i]['gamesets']); $j++){
                    if($gamedraws[$i]['gamesets'][$j]['id'] == $live_game_id){
                        $gamedraws[$i]['gamesets'][$j]['active'] = 'list-group-item-success';
                    }else{
                        $gamedraws[$i]['gamesets'][$j]['active'] = '';
                    }
                }

            }

            $result['gamedraws'] = $gamedraws;
        }else{
            $result['message'] = 'ERROR: Load Game Draws';
        }

        $database->conn->close();
    } else {
        # code...
    }
    echo json_encode($result);
} */

/* if (isset($_GET['LoadData']) && $_GET['LoadData'] != ""){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['LoadData'] == 'all' ){

        $database = new Database();
        $db = $database->getConnection();

        $live_game_id = 0;
        $vmixlive = new VMIX_LIVE($db);
        $resVMIX = $vmixlive->GetLiveGameID();
        if($resVMIX['status']){
            $live_game_id = $resVMIX['live_game'];
        }
        $result['live_game'] = $live_game_id;

        $team = new Team($db);
        $resTeams = $team->GetTeam();

        if($resTeams['status']){
            $teams = $resTeams['teams'];
            $result['teams'] = $teams;
            // for( $i=0; $i<sizeof($teams); $i++){
            //     $team->SetID($teams[$i]['id']);
            //     $players = $team->GetPlayers();
            //     $result['teams'][$i]['players'] = $players;
            //     for( $j=0; $j<sizeof($players); $j++ ){
            //         $player = new Player($db);
            //         $player->SetID($players[$j]['id']);
            //         $gamedraws = $player->GetGameDraws();
            //         $result['teams'][$i]['players'][$j]['gamedraws'] = $gamedraws;
            //         for( $k=0; $k<sizeof($gamedraws); $k++ ){
            //             $gamedraw = new GameDraw($db);
            //             $gamedraw->SetID($gamedraws[$k]['id']);
            //             $gamesets = $gamedraw->GetGameSets();
            //             $result['teams'][$i]['players'][$j]['gamedraws'][$k]['gamesets'] = $gamesets;
            //             for( $l=0; $l<sizeof($gamesets); $l++ ){
            //                 $gameset = new GameSet($db);
            //                 $gameset->SetID($gamesets[$l]['id']);
            //                 $scores = $gameset->GetScores();
            //                 $result['teams'][$i]['players'][$j]['gamedraws'][$k]['gamesets'][$l]['scores'] = $scores;
            //             }
            //         }
            //     }
            //     $teamGameDraws = $team->GetGameDraws();
            //     $result['teams'][$i]['gamedraws'] = $teamGameDraws;
            //     for( $j=0; $j<sizeof($teamGameDraws); $j++ ){
            //         $teamGamedraw = new GameDraw($db);
            //         $teamGamedraw->SetID($teamGameDraws[$j]['id']);
            //         $teamGamesets = $teamGamedraw->GetGameSets();
            //         $result['teams'][$i]['gamedraws'][$j]['gamesets'] = $teamGamesets;
            //         for( $k=0; $k<sizeof($teamGamesets); $k++ ){
            //             $teamGameset = new GameSet($db);
            //             $teamGameset->SetID($teamGamesets[$k]['id']);
            //             $teamScores = $teamGameset->GetScores();
            //             $result['teams'][$i]['gamedraws'][$j]['gamesets'][$k]['scores'] = $teamScores;
            //         }
            //     }
            // }
            $result['status'] = true;
        }else{
            $result['message'][] = "ERROR: Load Team.";
        }

        $player = new Player($db);
        $resPlayers = $player->GetPlayer();

        if($resPlayers['status']){
            $players = $resPlayers['players'];
            $result['players'] = $players;
            for( $i=0; $i<sizeof($players); $i++){
                $player->SetID($players[$i]['id']);
                $player->SetTeamId($players[$i]['team_id']);
                $result['players'][$i]['team'] = $player->GetTeam();
                // $playerGameDraws = $player->GetGameDraws();
                // $result['players'][$i]['gamedraws'] = $playerGameDraws;
                // for( $j=0; $j<sizeof($playerGameDraws); $j++ ){
                //     $playerGamedraw = new GameDraw($db);
                //     $playerGamedraw->SetID($playerGameDraws[$j]['id']);
                //     $playerGamesets = $playerGamedraw->GetGameSets();
                //     $result['players'][$i]['gamedraws'][$j]['gamesets'] = $playerGamesets;
                //     for( $k=0; $k<sizeof($gamesets); $k++ ){
                //         $playerGameset = new GameSet($db);
                //         $playerGameset->SetID($gamesets[$k]['id']);
                //         $playerScores = $playerGameset->GetScores();
                //         $result['players'][$i]['gamedraws'][$j]['gamesets'][$k]['scores'] = $playerScores;
                //     }
                // }
            }
            $result['status'] = true && $result['status'];
        }else{
            $result['message'][] = "ERROR: Load Player.";
        }

        $gamedraw = new GameDraw($db);
        $resGameDraws = $gamedraw->GetGameDraws();

        if($resGameDraws['status']){
            $gamedraws = $resGameDraws['gamedraws'];
            $result['gamedraws'] = $gamedraws;
            for( $i=0; $i<sizeof($gamedraws); $i++){
                $gamedraw->SetID($gamedraws[$i]['id']);
                $gamedraw->SetContestantAID($gamedraws[$i]['contestant_a_id']);
                $gamedraw->SetContestantBID($gamedraws[$i]['contestant_b_id']);
                if($gamedraws[$i]['gamemode_id'] == 1){
                    $result['gamedraws'][$i]['contestant_a'] = $gamedraw->GetTeamContestantA();
                    $result['gamedraws'][$i]['contestant_b'] = $gamedraw->GetTeamContestantB();
                }else if($gamedraws[$i]['gamemode_id'] == 2){
                    $result['gamedraws'][$i]['contestant_a'] = $gamedraw->GetPlayerContestantA();
                    $result['gamedraws'][$i]['contestant_b'] = $gamedraw->GetPlayerContestantB();
                }
                $gamedraw->SetGameModeID($gamedraws[$i]['gamemode_id']);
                $result['gamedraws'][$i]['gamemode'] = $gamedraw->GetGameMode();
                $gamedraw->SetGameStatusID($gamedraws[$i]['gamestatus_id']);
                $result['gamedraws'][$i]['gamestatus'] = $gamedraw->GetGameStatus();
            }
            $result['status'] = true && $result['status'];
        }else{
            $result['message'][] = "ERROR: Load Player.";
        }

        $gameset = new GameSet($db);
        $resGameSets = $gameset->GetGameSets();

        if($resGameSets['status']){
            $gamesets = $resGameSets['gamesets'];
            $result['gamesets'] = $gamesets;
            for( $i=0; $i<sizeof($gamesets); $i++){
                $gameset->SetID($gamesets[$i]['id']);
                $gameset->SetGameDrawID($gamesets[$i]['gamedraw_id']);
                $result['gamesets'][$i]['gamedraw'] = $gameset->GetGameDraw();
                $gamedraw = new GameDraw($db);
                $gamedraw->SetID($result['gamesets'][$i]['gamedraw']['id']);
                $gamedraw->SetContestantAID($result['gamesets'][$i]['gamedraw']['contestant_a_id']);
                $gamedraw->SetContestantBID($result['gamesets'][$i]['gamedraw']['contestant_b_id']);
                
                if($result['gamesets'][$i]['gamedraw']['gamemode_id'] == 1){
                    $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedraw->GetTeamContestantA();
                    $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedraw->GetTeamContestantB();
                }else if($result['gamesets'][$i]['gamedraw']['gamemode_id'] == 2){
                    $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedraw->GetPlayerContestantA();
                    $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedraw->GetPlayerContestantB();
                }
                $gameset->SetStatus($result['gamesets'][$i]['gameset_status']);
                $result['gamesets'][$i]['gamestatus'] = $gameset->GetGameStatus();
            }
            $result['status'] = true && $result['status'];
        }else{
            $result['message'][] = "ERROR: Load Player.";
        }

        $database->conn->close();

    }
    echo json_encode($result);
} */

// R Teams
if (isset( $_GET['GetTeam']) && $_GET['GetTeam'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetTeam'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $teams = new Team($db);
        $resTeams = $teams->GetTeam();

        $database->conn->close();

        if( $resTeams['status'] ){
            $result['status'] = true;
            $result['has_value'] = false;
            if($resTeams['has_value']){
                $result['has_value'] = true;
                $result['teams'] = $resTeams['teams'];
            }
        }else{
            $result['message'] = "ERROR: Load Team";
        }
    }else{
        $teamid = isset($_GET['GetTeam']) ? $_GET['GetTeam'] : 0;
        if(is_numeric($teamid) > 0){

            $database = new Database();
            $db = $database->getConnection();

            $team = new Team($db);
            $team->SetID($teamid);
            $tempRes = $team->GetTeamByID();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['team'] = $tempRes['team'];
            }else{
                $result['message'] = "ERROR: Load Team";
            }

        }else{
            $result['message'] = "ERROR: id must be numeric";
        }
    }
    echo json_encode($result);
}

// R Players
if (isset( $_GET['GetPlayer']) && $_GET['GetPlayer'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ""
    );
    if( $_GET['GetPlayer'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $player = new Player($db);
        $resPlayers = $player->GetPlayer();

        if( $resPlayers['status'] ){
            $result['status'] = true;
            $result['has_value'] = false;
            if($resPlayers['has_value']){
                $result['has_value'] = true;
                $result['players'] = $resPlayers['players'];
                for( $i=0; $i<sizeof($resPlayers['players']); $i++){
                    $team_id = $resPlayers['players'][$i]['team_id'];
                    $player->SetTeamId($team_id);
                    $team = $player->GetTeam();
                    if($team['id']>0){
                        $result['players'][$i]['team'] = $team;
                    }else{
                        $result['players'][$i]['team']['name'] = 'Individu';
                    }
                }
            }
        }else{
            $result['message'] = "ERROR: load players";
        }
        $database->conn->close();

    }else{
        $playerid = isset($_GET['GetPlayer']) ? $_GET['GetPlayer'] : 0;
        if(is_numeric($playerid) > 0){

            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetID($playerid);
            $tempRes = $player->GetPlayerByID();

            if( $tempRes['status'] ){
                $team_id = $tempRes['player']['team_id'];
                $result['status'] = true;
                $result['player'] = $tempRes['player'];
                if($team_id > 0){
                    $team = new Team($db);
                    $team->SetID($team_id);
                    $resTeam = $team->GetTeamByID();
                    if($resTeam['status']){
                        $result['player']['team'] = $resTeam['team'];
                    }else{
                        $result['player']['team']['name'] = 'Individu';
                    }
                }else{
                    $result['player']['team']['name'] = 'Individu';
                    $result['player']['team']['id'] = 0;
                }
            }else{
                $result['message'] = "ERROR: load players";
            }
            $database->conn->close();

        }else{
            $result['message'] = "ERROR: id must be numeric";
        }
    }
    echo json_encode($result);
}

// Get Game Draws
if (isset( $_GET['GetGameDraw']) && $_GET['GetGameDraw'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameDraw'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $resGamedraws = $gamedraw->GetGameDraws();

        if( $resGamedraws['status'] ){
            $result['status'] = true;
            $result['has_value'] = $resGamedraws['has_value'];
            if($result['has_value']){
                $gamedraws = $resGamedraws['gamedraws'];
                $result['gamedraws'] = $gamedraws;

                for( $i=0; $i<sizeof($gamedraws); $i++){

                    $gamemode_id = $gamedraws[$i]['gamemode_id'];
                    $contestant_a_id = $gamedraws[$i]['contestant_a_id'];
                    $contestant_b_id = $gamedraws[$i]['contestant_b_id'];
                    $gamestatus_id = $gamedraws[$i]['gamestatus_id'];

                    $gamedraw->SetGameModeID($gamemode_id);
                    $gamemode = $gamedraw->GetGameMode();
                    if($gamemode['id']>0){
                        $result['gamedraws'][$i]['gamemode'] = $gamemode;
                    }else{
                        $result['gamedraws'][$i]['gamemode']['name'] = "-";
                    }

                    // Contestant
                    $gamedraw->SetContestantAID($contestant_a_id);
                    $gamedraw->SetContestantBID($contestant_b_id);

                    if($gamemode_id==1) { // Beregu
                        $result['gamedraws'][$i]['contestant_a'] = $gamedraw->GetTeamContestantA();
                        $result['gamedraws'][$i]['contestant_b'] = $gamedraw->GetTeamContestantB();
                    }else if( $gamemode_id ==2 ){ // Individu
                        $result['gamedraws'][$i]['contestant_a'] = $gamedraw->GetPlayerContestantA();
                        $result['gamedraws'][$i]['contestant_b'] = $gamedraw->GetPlayerContestantB();
                        if($result['gamedraws'][$i]['contestant_a']['team_id']>0){
                            $player = new Player($db);
                            $player->SetTeamId($result['gamedraws'][$i]['contestant_a']['team_id']);
                            $team = $player->GetTeam();
                            $result['gamedraws'][$i]['contestant_a']['logo'] = $team['logo'];
                        }else{
                            $result['gamedraws'][$i]['contestant_a']['logo'] = "no-team.png";
                        }
                        if($result['gamedraws'][$i]['contestant_b']['team_id']>0){
                            $player = new Player($db);
                            $player->SetTeamId($result['gamedraws'][$i]['contestant_b']['team_id']);
                            $team = $player->GetTeam();
                            $result['gamedraws'][$i]['contestant_b']['logo'] = $team['logo'];
                        }else{
                            $result['gamedraws'][$i]['contestant_b']['logo'] = "no-team.png";
                        }
                    }

                    // Game  Status
                    $gamedraw->SetGameStatusID($gamestatus_id);
                    $gamestatus = $gamedraw->GetGameStatus();
                    $result['gamedraws'][$i]['gamestatus'] = $gamestatus;
                }
            }
        }else{
            $result['message'] = "ERROR: Load Game Draw";
        }
        $database->conn->close();

    }else{
        $gamedraw_id = isset($_GET['GetGameDraw']) ? $_GET['GetGameDraw'] : 0;
        if(is_numeric($gamedraw_id) > 0){
            $database = new Database();
            $db = $database->getConnection();

            $gamedraw = new GameDraw($db);
            $gamedraw->SetID( $gamedraw_id );
            $tempRes = $gamedraw->GetGameDrawByID();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['gamedraw'] = $tempRes['gamedraw'];

                $gamemode_id = $tempRes['gamedraw']['gamemode_id'];
                $contestant_a_id = $tempRes['gamedraw']['contestant_a_id'];
                $contestant_b_id = $tempRes['gamedraw']['contestant_b_id'];
                $gamestatus_id = $tempRes['gamedraw']['gamestatus_id'];
                /**
                 * TO-DO: Game mode dinamis
                 */
                // Game mode
                if($gamemode_id>0){
                    $gamemode = new GameMode($db);
                    $gamemode->SetID($gamemode_id);
                    $resGameMode = $gamemode->GetGameModeByID();
                    if( $resGameMode['status'] ){
                        $result['gamedraw']['gamemode'] = $resGameMode['gamemode'];
                    }else{
                        $result['gamedraw']['gamemode']['name'] = "-";
                    }
                }else{
                    $result['gamedraw']['gamemode']['name'] = "-";
                }

                // Contestant
                if($contestant_a_id > 0 && $contestant_b_id > 0){
                    if($gamemode_id==1) { // Beregu
                        $team = new Team($db);
                        $team->SetID($contestant_a_id);
                        $resTeam = $team->GetTeamByID();
                        if($resTeam['status']){
                            $result['gamedraw']['contestant_a'] = $resTeam['team'];
                        }else{
                            $result['gamedraw']['contestant_a']['name'] = '-';
                        }
                        $team->SetID($contestant_b_id);
                        $resTeam = $team->GetTeamByID();
                        if($resTeam['status']){
                            $result['gamedraw']['contestant_b'] = $resTeam['team'];
                        }else{
                            $result['gamedraw']['contestant_b']['name'] = '-';
                        }
                    }else if( $gamemode_id ==2 ){ // Individu
                        $player = new Player($db);
                        $player->SetID($contestant_a_id);
                        $resPlayer = $player->GetPlayerByID();
                        if($resPlayer['status']){
                            $result['gamedraw']['contestant_a'] = $resPlayer['player'];
                        }else{
                            $result['gamedraw']['contestant_a']['name'] = '-';
                        }
                        $player->SetID($contestant_b_id);
                        $resPlayer = $player->GetPlayerByID();
                        if($resPlayer['status']){
                            $result['gamedraw']['contestant_b'] = $resPlayer['player'];
                        }else{
                            $result['gamedraw']['contestant_b']['name'] = '-';
                        }
                    }else{
                        $result['gamedraw']['contestant_a']['name'] = '-';
                        $result['gamedraw']['contestant_b']['name'] = '-';
                    }
                }else{
                    $result['gamedraw']['contestant_a']['name'] = '-';
                    $result['gamedraw']['contestant_b']['name'] = '-';
                }

                // Game  Status
                if( $gamestatus_id > 0){
                    $gamestatus = new GameStatus($db);
                    $gamestatus->SetID($gamestatus_id);
                    $resGameStatus = $gamestatus->GetGameStatusByID();
                    if( $resGameStatus['status'] ){
                        $result['gamedraw']['gamestatus'] = $resGameStatus['gamestatus'];
                    }else{
                        $result['gamedraw']['gamestatus']['name'] = "-";
                    }
                }else{
                    $result['gamedraw']['gamestatus']['name'] = "-";
                }
            }else{
                $result['message'] = "ERROR: Load Game Draw";
            }
            $database->conn->close();
        }else{
            $result['message'] = "ERROR: ID = 0";
        }
    }
    echo json_encode($result);
}

// Get Game Draws Info
if (isset( $_GET['GetGameDrawInfo']) && $_GET['GetGameDrawInfo'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gamedraw_id = isset($_GET['GetGameDrawInfo']) ? $_GET['GetGameDrawInfo'] : 0;
    if(is_numeric($gamedraw_id) > 0){
        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $gamedraw->SetID( $gamedraw_id );
        $resGameDraw = $gamedraw->GetGameDrawByID();

        if( $resGameDraw['status'] ){
            $result['status'] = $resGameDraw['status'];
            $result['has_value'] = $resGameDraw['has_value'];
            if($result['has_value']){
                $arr_gamedraw = $resGameDraw['gamedraw'];
                $result['gamedraw'] = $arr_gamedraw;

                $gamemode_id = $arr_gamedraw['gamemode_id'];
                $contestant_a_id = $arr_gamedraw['contestant_a_id'];
                $contestant_b_id = $arr_gamedraw['contestant_b_id'];
                $gamestatus_id = $arr_gamedraw['gamestatus_id'];
                /**
                 * TO-DO: Game mode dinamis
                 */
                // Game mode
                $gamedraw->SetGameModeID( $gamemode_id );
                $resGameMode = $gamedraw->GetGameMode();
                $result['gamedraw']['gamemode'] = $resGameMode;

                // Contestant
                $gamedraw->SetContestantAID($contestant_a_id);
                $gamedraw->SetContestantBID($contestant_b_id);
                if($gamemode_id==1) { // Beregu
                    $arr_team_a = $gamedraw->GetTeamContestantA();
                    $arr_team_b = $gamedraw->GetTeamContestantB();
                    $result['gamedraw']['contestant_a'] = $arr_team_a;
                    $result['gamedraw']['contestant_b'] = $arr_team_b;
                }else if( $gamemode_id ==2 ){ // Individu
                    $contestant_a = $gamedraw->GetPlayerContestantA();
                    $contestant_b = $gamedraw->GetPlayerContestantB();

                    $result['gamedraw']['contestant_a'] = $contestant_a;
                    $result['gamedraw']['contestant_b'] = $contestant_b;
                    $result['gamedraw']['contestant_a']['logo'] = "no-team.png";
                    $result['gamedraw']['contestant_b']['logo'] = "no-team.png";

                    if($contestant_a['team_id']>0 || $contestant_b['team_id']>0){
                        $team = new Team($db);
                        $team->SetID($contestant_a['team_id']);
                        $resGetLogo = $team->GetLogo();
                        if($resGetLogo['status']){
                            if($resGetLogo['has_value']){
                                $result['gamedraw']['contestant_a']['logo'] = $resGetLogo['logo'];
                            }
                        }
                        $team->SetID($contestant_b['team_id']);
                        $resGetLogo = $team->GetLogo();
                        if($resGetLogo['status']){
                            if($resGetLogo['has_value']){
                                $result['gamedraw']['contestant_b']['logo'] = $resGetLogo['logo'];
                            }
                        }
                    }
                }else{
                    $result['gamedraw']['contestant_a']['name'] = '-';
                    $result['gamedraw']['contestant_b']['name'] = '-';
                }

                // Game  Status
                $gamedraw->SetGameStatusID($gamestatus_id);
                $result['gamedraw']['gamestatus'] = $gamedraw->GetGameStatus();

                // Game Set
                $arr_gamesets = $gamedraw->GetGameSets();
                $result['gamedraw']['gamesets'] = $arr_gamesets;
                for( $i=0; $i<sizeof($arr_gamesets); $i++){
                    $tempGameSet = $arr_gamesets[$i];
                    $gamesetCls = new GameSet($db);
                    $gamesetCls->SetID($tempGameSet['id']);
                    $arr_scores = $gamesetCls->GetScores();
                    for( $j=0; $j<sizeof($arr_scores); $j++){
                        $tempScore = $arr_scores[$j];
                        if($contestant_a_id == $tempScore['contestant_id']){
                            $result['gamedraw']['gamesets'][$i]['score_a'] = $tempScore;
                        }
                        else if($contestant_b_id == $tempScore['contestant_id']){
                            $result['gamedraw']['gamesets'][$i]['score_b'] = $tempScore;
                        }
                    }
                }

            }
        }else{
            $result['message'] = "ERROR: Load Game Draw";
        }
        $database->conn->close();
    }else{
        $result['message'] = "ERROR: ID = 0";
    }
    echo json_encode($result);
}

// Get Game Set
if (isset( $_GET['GetGameSet']) && $_GET['GetGameSet'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameSet'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $resGameSets = $gameset->GetGameSets();

        if( $resGameSets['status'] ){
            $result['status'] = true;
            $result['has_value'] = $resGameSets['has_value'];
            $result['gamesets'] = $resGameSets['gamesets'];
            if($result['has_value']){
                for( $i=0; $i<sizeof($resGameSets['gamesets']); $i++){
                    $gamedraw_id = $resGameSets['gamesets'][$i]['gamedraw_id'];
                    $gameset_status_id = $resGameSets['gamesets'][$i]['gameset_status'];

                    $gameset->SetGameDrawID($gamedraw_id);
                    $gamedraw = $gameset->GetGameDraw();

                    $result['gamesets'][$i]['gamedraw'] = $gamedraw;
                    if($gamedraw_id>0){
                        $gamemode_id = $gamedraw['gamemode_id'];
                        $contestant_a_id = $gamedraw['contestant_a_id'];
                        $contestant_b_id = $gamedraw['contestant_b_id'];
                        // Contestant
                        $gamedrawCls = new GameDraw($db);
                        $gamedrawCls->SetContestantAID($contestant_a_id);
                        $gamedrawCls->SetContestantBID($contestant_b_id);
                        if($gamemode_id==1) { // Beregu
                            $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedrawCls->GetTeamContestantA();
                            $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedrawCls->GetTeamContestantB();
                        }else if( $gamemode_id ==2 ){ // Individu
                            $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedrawCls->GetPlayerContestantA();
                            $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedrawCls->GetPlayerContestantB();
                            if($result['gamesets'][$i]['gamedraw']['contestant_a']['team_id']>0){
                                $player = new Player($db);
                                $player->SetTeamId($result['gamesets'][$i]['gamedraw']['contestant_a']['team_id']);
                                $team = $player->GetTeam();
                                $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = $team['logo'];
                            }else{
                                $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = "no-team.png";
                            }
                            if($result['gamesets'][$i]['gamedraw']['contestant_b']['team_id']>0){
                                $player = new Player($db);
                                $player->SetTeamId($result['gamesets'][$i]['gamedraw']['contestant_b']['team_id']);
                                $team = $player->GetTeam();
                                $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = $team['logo'];
                            }else{
                                $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = "no-team.png";
                            }
                        }
                    }else{
                        $result['gamesets'][$i]['gamedraw']['num'] = "0";
                    }
                    if($gameset_status_id>0){
                        $gameset->SetStatus($gameset_status_id);
                        $result['gamesets'][$i]['gamestatus'] = $gameset->GetGameStatus();
                    }else{
                        $result['gamesets'][$i]['gamestatus']['name'] = "-";
                    }
                }
            }
        }
        $database->conn->close();
    }else{
        $gameset_id = isset($_GET['GetGameSet']) ? $_GET['GetGameSet'] : 0;
        if(is_numeric($gameset_id)>0){
            $database = new Database();
            $db = $database->getConnection();

            $gameset = new GameSet($db);
            $gameset->SetID( $gameset_id );
            $tempRes = $gameset->GetGameSetByID();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['gameset'] = $tempRes['gameset'];
                $gamedraw_id = $tempRes['gameset']['gamedraw_id'];
                $gameset_status_id = $tempRes['gameset']['gameset_status'];
                if($gamedraw_id>0){
                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetID($gamedraw_id);
                    $resGameDraw = $gamedraw->GetGameDrawByID();
                    if( $resGameDraw['status'] ){
                        $result['gameset']['gamedraw'] = $resGameDraw['gamedraw'];
                        $gamemode_id = $resGameDraw['gamedraw']['gamemode_id'];
                        $contestant_a_id = $resGameDraw['gamedraw']['contestant_a_id'];
                        $contestant_b_id = $resGameDraw['gamedraw']['contestant_b_id'];
                        // Contestant
                        if($contestant_a_id > 0 && $contestant_b_id > 0){
                            if($gamemode_id==1) { // Beregu
                                $team = new Team($db);
                                $team->SetID($contestant_a_id);
                                $resTeam = $team->GetTeamByID();
                                if($resTeam['status']){
                                    $result['gameset']['gamedraw']['contestant_a'] = $resTeam['team'];
                                }else{
                                    $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
                                }
                                $team->SetID($contestant_b_id);
                                $resTeam = $team->GetTeamByID();
                                if($resTeam['status']){
                                    $result['gameset']['gamedraw']['contestant_b'] = $resTeam['team'];
                                }else{
                                    $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
                                }
                            }else if( $gamemode_id ==2 ){ // Individu
                                $player = new Player($db);
                                $player->SetID($contestant_a_id);
                                $resPlayer = $player->GetPlayerByID();
                                if($resPlayer['status']){
                                    $result['gameset']['gamedraw']['contestant_a'] = $resPlayer['player'];
                                }else{
                                    $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
                                }
                                $player->SetID($contestant_b_id);
                                $resPlayer = $player->GetPlayerByID();
                                if($resPlayer['status']){
                                    $result['gameset']['gamedraw']['contestant_b'] = $resPlayer['player'];
                                }else{
                                    $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
                                }
                            }else{
                                $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
                                $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
                            }
                        }else{
                            $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
                            $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
                        }
                    }else{
                        $result['gameset']['gamedraw']['num'] = "0";
                    }
                }else{
                    $result['gameset']['gamedraw']['num'] = "0";
                }
                if($gameset_status_id>0){
                    $gamestatus = new GameStatus($db);
                    $gamestatus->SetID($gameset_status_id);
                    $resGameStatus = $gamestatus->GetGameStatusByID();
                    if( $resGameDraw['status'] ){
                        $result['gameset']['gamestatus'] = $resGameStatus['gamestatus'];
                    }else{
                        $result['gameset']['gamestatus']['name'] = "-";
                    }
                }else{
                    $result['gameset']['gamestatus']['name'] = "-";
                }
            }else{
                $result['message'] = "ERROR: Load Gameset";
            }
        }else{
            $result['message'] = "ERROR: id = 0";
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
        $resUpdate = $score->UpdateScore();

        if( $resUpdate['status'] ){
            $gamesetCls = new GameSet($db);
            $gamesetCls->SetID($gameset_id);
            $resGameSet = $gamesetCls->GetGameSetByID();
            if($resGameSet['status']){
                if($resGameSet['gameset']['gameset_status'] < 3){
                    if ($setpoints > 0){
                        // TO-DO: Dinamic Gameset Status
                        $gamesetCls->SetStatus(3);
                        $resUpdateStatus = $gamesetCls->UpdateStatusGameSet();
                        if($resUpdateStatus['status']){
                            $result['lock_gameset'] = true;
                        }
                    }else{
                        $result['lock_gameset'] = false;
                    }
                }else{
                    $result['lock_gameset'] = true;
                }
            }
            $result['status'] = true;
        }else{
            $result['message'] = "ERROR: Update Score";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Update Timer
if ( isset( $_POST['score_timer_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['score_timer_action'] != ''){
        $score_id = 0;
        $timer = 120;
        if( $_POST['score_timer_action'] == 'update-timer-a'){

            $score_id = isset($_POST['score_a_id']) ? $_POST['score_a_id'] : 0;
            $timer = isset($_POST['timer_a']) ? $_POST['timer_a'] : 0;

        }else if( $_POST['score_timer_action'] == 'update-timer-b'){

            $score_id = isset($_POST['score_b_id']) ? $_POST['score_b_id'] : 0;
            $timer = isset($_POST['timer_b']) ? $_POST['timer_b'] : 0;
        }

        $database = new Database();
        $db = $database->getConnection();

        $score = new Score($db);
        $score->SetID($score_id);
        $score->SetTimer($timer);
        $tempRes = $score->UpdateScoreTimer();

        if( $tempRes['status'] ){
            $result['status'] = true;
        }else{
            $result['message'] = "ERROR: Update Score Timer";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Stop Live Game
if ( isset( $_POST['vmix_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['vmix_action'] != ''){
        $timer = 120;
        if( $_POST['vmix_action'] == 'stop-live-game'){
            $gameset_id = isset($_POST['gamesetid']) ? $_POST['gamesetid'] : 0;

            $database = new Database();
            $db = $database->getConnection();

            $vmix = new VMIX_LIVE($db);
            $resVMIX = $vmix->StopLiveGame();

            if( $resVMIX['status'] ){
                $result['status'] = true;
                $gamesetCls = new GameSet($db);
                $gamesetCls->SetID($gameset_id);
                // TO-DO: Dinamic Gameset Status
                $gamesetCls->SetStatus(1);
                $resUpdateStatus = $gamesetCls->UpdateStatusGameSet();
                if($resUpdateStatus['status']){
                }
            }else{
                $result['message'] = "ERROR: Update Score Timer";
            }

            $database->conn->close();
        }
        else if( $_POST['vmix_action'] == 'set-live-game'){
            $gameset_id = isset($_POST['gamesetid']) ? $_POST['gamesetid'] : 0;
            if(is_numeric($gameset_id)){
                $database = new Database();
                $db = $database->getConnection();

                $vmix = new VMIX_LIVE($db);
                $gamesetCls = new GameSet($db);
                $resPrevLiveGame = $vmix->GetLiveGameID();
                if($resPrevLiveGame['status']){
                    $prev_live_game_id = $resPrevLiveGame['live_game'];
                    if($prev_live_game_id > 0){
                        $gamesetCls->SetID($prev_live_game_id);
                        // TO-DO: Dinamic Gameset Status
                        $gamesetCls->SetStatus(1);
                        $resUpdateStatus = $gamesetCls->UpdateStatusGameSet();
                    }
                }
                $vmix->SetGameSetID($gameset_id);
                $resVMIX = $vmix->UpdateLiveGame();

                if( $resVMIX['status'] ){
                    $result['status'] = true;
                    $gamesetCls->SetID($gameset_id);
                    // TO-DO: Dinamic Gameset Status
                    $gamesetCls->SetStatus(2);
                    $resUpdateStatus = $gamesetCls->UpdateStatusGameSet();
                    if($resUpdateStatus['status']){
                    }
                    $result['live_game'] = $gameset_id;
                }else{
                    $result['message'] = "ERROR: Update Score Timer";
                }
            }
            $database->conn->close();
        }
    }
    echo json_encode($result);
}

// Init Scoreboard
/* if (isset( $_GET['InitScoreboard']) && $_GET['InitScoreboard'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['InitScoreboard'] == 'live') {

        $database = new Database();
        $db = $database->getConnection();

        $vmixlive = new VMIX_LIVE($db);
        $nLiveGame = $vmixlive->CountLiveGame();
        if($nLiveGame==0){
            $vmixlive->SetGameSetID(0);
            $resVMIXCreate = $vmixlive->CreateDefaultLiveGame();
            if($resVMIXCreate['status']){
                $result['status'] = true;
                $result['has_live_game'] = false;
            }else{
                $result['message'] = "ERROR: Create Default Live Game";
            }
        }else{
            $resLiveGame = $vmixlive->GetLiveGameID();
            if($resLiveGame['status']){
                $live_game_id = $resLiveGame['live_game'];
                if($live_game_id>0){
                    $gameset = new GameSet($db);
                    $gameset->SetID($live_game_id);
                    $resGameSet = $gameset->GetGameSetByID();
                    if($resGameSet['status']){
                        $gameset->SetGameDrawID($resGameSet['gameset']['gamedraw_id']);
                        $resGameDraw = $gameset->GetGameDraw();
                        if($resGameDraw['id']>0){
                            $result['status'] = true;
                            $result['gamedraw_id'] = $resGameDraw['id'];
                            $result['gameset_id'] = $live_game_id;
                            $result['has_live_game'] = true;
                        }else{
                            $result['message'] = "ERROR: Game Draw ID = 0";
                        }
                    }else{
                        $result['message'] = "ERROR: Get Game Set";
                    }
                }else{
                    $result['status'] = true;
                    $result['has_live_game'] = false;
                }
            }else{
                $result['message'] = "ERROR: Get Live Game ID";
            }
        }

        $database->conn->close();
    }
    echo json_encode($result);
} */

/**
 * VMIX FUNCTION
 */

// Get Scoreboard
if (isset( $_GET['GetScoreboard']) && $_GET['GetScoreboard'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $scoreboard = $_GET['GetScoreboard'];
    if ( $scoreboard == 'live' ) {
        $database = new Database();
        $db = $database->getConnection();

        $vmixlive = new VMIX_LIVE($db);
        $resVMIXLIVE = $vmixlive->GetLiveGameID();

        $result['scoreboard']['set'] = "Set 0";
        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_a'] = "Player A";
        $result['scoreboard']['timer_a'] = "0s";
        $result['scoreboard']['point_1a'] = 0;
        $result['scoreboard']['point_2a'] = 0;
        $result['scoreboard']['point_3a'] = 0;
        $result['scoreboard']['point_4a'] = 0;
        $result['scoreboard']['point_5a'] = 0;
        $result['scoreboard']['point_6a'] = 0;
        $result['scoreboard']['total_a'] = 0;
        $result['scoreboard']['setpoints_a'] = 0;

        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_b'] = "Player A";
        $result['scoreboard']['timer_b'] = "0s";
        $result['scoreboard']['point_1b'] = 0;
        $result['scoreboard']['point_2b'] = 0;
        $result['scoreboard']['point_3b'] = 0;
        $result['scoreboard']['point_4b'] = 0;
        $result['scoreboard']['point_5b'] = 0;
        $result['scoreboard']['point_6b'] = 0;
        $result['scoreboard']['total_b'] = 0;
        $result['scoreboard']['setpoints_b'] = 0;

        if($resVMIXLIVE['status']){
            $gameset_id = $resVMIXLIVE['live_game'];
            if($gameset_id > 0){

                $gameset = new GameSet($db);
                $gameset->SetID($gameset_id);
                $resLiveGameSet = $gameset->GetGameSetByID();

                if($resLiveGameSet['status']){
                    $result['status'] = true;
                    $liveGameSet = $resLiveGameSet['gameset'];
                    $result['scoreboard']['set'] = "Set " . $liveGameSet['num'];
                    $gameset->SetGameDrawID($liveGameSet['gamedraw_id']);
                    $resGameDraw = $gameset->GetGameDraw();

                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetContestantAID($resGameDraw['contestant_a_id']);
                    $gamedraw->SetContestantBID($resGameDraw['contestant_b_id']);
                    /**
                     * TO-DO: Dinamis Game Mode
                     */
                    if($resGameDraw['gamemode_id'] == 1){
                        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $gamedraw->GetTeamContestantA()['logo'];
                        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $gamedraw->GetTeamContestantB()['logo'];
                        $result['scoreboard']['contestant_a'] = $gamedraw->GetTeamContestantA()['name'];
                        $result['scoreboard']['contestant_b'] = $gamedraw->GetTeamContestantB()['name'];
                    }else if($resGameDraw['gamemode_id'] == 2){
                        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
                        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
                        $contestant_a = $gamedraw->GetPlayerContestantA();
                        $contestant_b = $gamedraw->GetPlayerContestantB();
                        $result['scoreboard']['contestant_a'] = $contestant_a['name'];
                        $result['scoreboard']['contestant_b'] = $contestant_b['name'];
                        if($contestant_a['team_id']>0 || $contestant_b['team_id']>0){
                            $team = new Team($db);
                            $team->SetID($contestant_a['team_id']);
                            $resGetLogo = $team->GetLogo();
                            if($resGetLogo['status']){
                                if($resGetLogo['has_value']){
                                    $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $resGetLogo['logo'];
                                }
                            }
                            $team->SetID($contestant_b['team_id']);
                            $resGetLogo = $team->GetLogo();
                            if($resGetLogo['status']){
                                if($resGetLogo['has_value']){
                                    $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $resGetLogo['logo'];
                                }
                            }
                        }

                    }

                    $score = new Score($db);
                    $score->SetGameSetID($liveGameSet['id']);
                    $score->SetContestantID($resGameDraw['contestant_a_id']);
                    $resScore = $score->GetScoreByGameSetAndContestant();

                    if($resScore['status']){
                        $result['status'] = true && $result['status'];
                        $score_a = $resScore['score'];
                        $result['scoreboard']['timer_a'] = $score_a['timer'] . "s";
                        $result['scoreboard']['point_1a'] = $score_a['score_1'];
                        $result['scoreboard']['point_2a'] = $score_a['score_2'];
                        $result['scoreboard']['point_3a'] = $score_a['score_3'];
                        $result['scoreboard']['point_4a'] = $score_a['score_4'];
                        $result['scoreboard']['point_5a'] = $score_a['score_5'];
                        $result['scoreboard']['point_6a'] = $score_a['score_6'];
                        $total_a = $score_a['score_1'] + $score_a['score_2'] + $score_a['score_3'] + $score_a['score_4'] + $score_a['score_5'] + $score_a['score_6'];
                        $result['scoreboard']['total_a'] = $total_a;
                        $result['scoreboard']['timer_a'] = $score_a['timer'] . "s";
                        $result['scoreboard']['setpoints_a'] = $score_a['point'];
                    }else{
                        $result['message'][] = "ERROR: Load Score A";
                    }

                    $score->SetContestantID($resGameDraw['contestant_b_id']);
                    $resScore = $score->GetScoreByGameSetAndContestant();

                    if($resScore['status']){
                        $result['status'] = true && $result['status'];
                        $score_b = $resScore['score'];
                        $result['scoreboard']['timer_b'] = $score_b['timer'] . "s";
                        $result['scoreboard']['point_1b'] = $score_b['score_1'];
                        $result['scoreboard']['point_2b'] = $score_b['score_2'];
                        $result['scoreboard']['point_3b'] = $score_b['score_3'];
                        $result['scoreboard']['point_4b'] = $score_b['score_4'];
                        $result['scoreboard']['point_5b'] = $score_b['score_5'];
                        $result['scoreboard']['point_6b'] = $score_b['score_6'];
                        $total_b = $score_b['score_1'] + $score_b['score_2'] + $score_b['score_3'] + $score_b['score_4'] + $score_b['score_5'] + $score_b['score_6'];
                        $result['scoreboard']['total_b'] = $total_b;
                        $result['scoreboard']['setpoints_b'] = $score_b['point'];
                    }else{
                        $result['message'] = "ERROR: Load Score B";
                    }
                }else{
                    $result['message'] = "ERROR: LOAD Live Game Set";
                }

            }else{
                $result['message'] = "NO LIVE GAME";
            }
        }else{
            $result['message'] = "ERROR: Load LIVE GAME";
        }
        $database->conn->close();

    }

    echo "[". json_encode($result['scoreboard']) . "]";
    /* if($result['status']){
    }else{
        echo "[". json_encode($result['message']) . "]";
    } */
}
?>