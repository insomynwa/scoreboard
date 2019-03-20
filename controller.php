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
                    if( $team_action == 'create' ){
                        $result['action'] = "create";
                        $sql = "INSERT team (team_name,team_logo,team_initial,team_desc) VALUES ('".$name."','". strtolower($final_image) ."', '". $team_initial ."', '". $team_desc ."' )";
                    }else if ( $team_action == 'update'){
                        $team_id = $_POST['team_id'];
                        $result['action'] = "update";
                        $sql = "UPDATE team SET team_logo='{$final_image}', team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";
                    }

                    //include database configuration file
                    // include_once '../config/database.php';

                    $database = new Database();
                    $db = $database->getConnection();

                    $tempRes = $db->query($sql);
                    $database->conn->close();
                    if ($tempRes){
                        $result['message'] = "Success";
                        $result['status'] = true;
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
            $delTeam = $team->GetTeamByID();
            $teamPlayers = $team->GetPlayers();
            if(sizeof($teamPlayers)>0){
                for( $h=0; $h<sizeof($teamPlayers); $h++){
                    $player = new Player($db);
                    $player->SetID( $teamPlayers[$h]['id'] );
                    $playerGameDraws = $player->GetGameDraws();
                    if(sizeof($playerGameDraws)>0){
                        for( $i=0; $i<sizeof($playerGameDraws); $i++){
                            $gamedraw = new GameDraw($db);
                            $gamedraw->SetID($playerGameDraws[$i]['id']);
                            $gamedrawGameSets = $gamedraw->GetGameSets();
                            if(sizeof($gamedrawGameSets)>0){
                                for( $j=0; $j<sizeof($gamedrawGameSets); $j++){
                                    $gameset = new GameSet($db);
                                    $gameset->SetID($gamedrawGameSets[$j]['id']);
                                    $gamesetScores = $gameset->GetScores();
                                    if(sizeof($gamesetScores)>0){
                                        for( $k=0; $k<sizeof($gamesetScores); $k++){
                                            $score = new Score($db);
                                            $score->SetID($gamesetScores[$k]['id']);
                                            $score->DeleteScore();
                                        }
                                    }else{
                                    }
                                    $gameset->DeleteGameSet();
                                }
                            }else{
                            }
                            $gamedraw->DeleteGameDraw();
                        }
                    }else{
                    }
                    $player->DeletePlayer();
                }
            }else{
                $teamGameDraws = $team->GetGameDraws();
                if(sizeof($teamGameDraws)>0){
                    for( $i=0; $i<sizeof($teamGameDraws); $i++){
                        $gamedraw = new GameDraw($db);
                        $gamedraw->SetID($teamGameDraws[$i]['id']);
                        $gamedrawGameSets = $gamedraw->GetGameSets();
                        if(sizeof($gamedrawGameSets)>0){
                            for( $j=0; $j<sizeof($gamedrawGameSets); $j++){
                                $gameset = new GameSet($db);
                                $gameset->SetID($gamedrawGameSets[$j]['id']);
                                $gamesetScores = $gameset->GetScores();
                                if(sizeof($gamesetScores)>0){
                                    for( $k=0; $k<sizeof($gamesetScores); $k++){
                                        $score = new Score($db);
                                        $score->SetID($gamesetScores[$k]['id']);
                                        $score->DeleteScore();
                                    }
                                }else{
                                }
                                $gameset->DeleteGameSet();
                            }
                        }else{
                        }
                        $gamedraw->DeleteGameDraw();
                    }
                }else{
                }
            }
            // Delete LOGO
            unlink(dirname(__FILE__) . "/uploads/" . $delTeam['team']['logo'] );
            $tempRes = $team->DeleteTeam();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
                $result['action'] = 'delete';
            }else{
                $result['message'] = "ERROR: Delete Player " . $playerid;
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

        $playerData = array(
            'name'   => isset($_POST['player_name']) ? $_POST['player_name'] : '',
            'team_id'       =>  isset($_POST['player_team']) ? $_POST['player_team'] : 0
        );

        if( $playerData['name'] == '' ){
            $result['message'] = "ERROR: player name is required!";
            $result['action'] = 'create';
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetName( $playerData['name'] );
            $player->SetTeamId( $playerData['team_id'] );
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
            $playerGameDraws = $player->GetGameDraws();
            if(sizeof($playerGameDraws)>0){
                for( $i=0; $i<sizeof($playerGameDraws); $i++){
                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetID($playerGameDraws[$i]['id']);
                    $gamedrawGameSets = $gamedraw->GetGameSets();
                    if(sizeof($gamedrawGameSets)>0){
                        for( $j=0; $j<sizeof($gamedrawGameSets); $j++){
                            $gameset = new GameSet($db);
                            $gameset->SetID($gamedrawGameSets[$j]['id']);
                            $gamesetScores = $gameset->GetScores();
                            if(sizeof($gamesetScores)>0){
                                for( $k=0; $k<sizeof($gamesetScores); $k++){
                                    $score = new Score($db);
                                    $score->SetID($gamesetScores[$k]['id']);
                                    $score->DeleteScore();
                                }
                            }else{
                            }
                            $gameset->DeleteGameSet();
                        }
                    }else{
                    }
                    $gamedraw->DeleteGameDraw();
                }
            }else{
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

// Get Game Set By ID
if (isset( $_GET['GetGameSetByID']) && $_GET['GetGameSetByID'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gameset_id = isset($_GET['GetGameSetByID']) ? $_GET['GetGameSetByID'] : 0;

    $database = new Database();
    $db = $database->getConnection();

    $gameset = new GameSet($db);
    $gameset->SetID( $gameset_id );
    $tempRes = $gameset->GetGameSetByID();

    if( $tempRes['status'] ){
        $result['status'] = $tempRes['status'];
        $result['gameset'] = $tempRes['gameset'];
    }else{
        $result['message'] = "ERROR: Load Gameset";
    }
    echo json_encode($result);
}

// Get Game Draw By ID
if (isset( $_GET['GetGameDrawByID']) && $_GET['GetGameDrawByID'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gamedraw_id = isset($_GET['GetGameDrawByID']) ? $_GET['GetGameDrawByID'] : 0;

    $database = new Database();
    $db = $database->getConnection();

    $gamedraw = new GameDraw($db);
    $gamedraw->SetID( $gamedraw_id );
    $tempRes = $gamedraw->GetGameDrawByID();

    if( $tempRes['status'] ){
        $result['status'] = $tempRes['status'];
        $result['gamedraw'] = $tempRes['gamedraw'];
    }else{
        $result['message'] = "ERROR: Load Game Draw";
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

// Create Game Set
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
    }
    else if( $_POST['gameset_action'] == 'update') {

        $gamedraw_id = isset($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
        $gameset_num = isset($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;
        $gameset_id = isset($_POST['gameset_id']) ? $_POST['gameset_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gameset = new GameSet( $db );
        $gameset->SetID( $gameset_id );
        $gameset->SetNum( $gameset_num );
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
        $tempRes = $gameset->DeleteGameSet();

        if( $tempRes['status'] ){
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
        }
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

// Get Teams By id [IMPORTANT EXAMPLE FOR VMIX]
/* if (isset( $_GET['getTeamById']) && $_GET['getTeamById'] != '') {
    $team = getTeamById($_GET['getTeamById']);
    echo "[". json_encode($team['team']) . "]";
} */

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

            $gamenum = isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;

            $gamedraw = new GameDraw($db);
            $gamedraw->SetNum($gamenum);
            $gamedraw->SetGameModeID($gamemode_id);
            // $contestant->SetContestantID($game_data['contestant_id']);
            $gamedraw->SetContestantAID($contestant_a_id);
            $gamedraw->SetContestantBID($contestant_b_id);
            $tempRes = $gamedraw->CreateGameDraw();
            $database->conn->close();

            if( $tempRes['status'] ){
                $result['next_num'] = $gamenum + 1;
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

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gamedraw = new GameDraw( $db );
        $gamedraw->SetID( $gamedraw_id );
        $tempRes = $gamedraw->DeleteGameDraw();

        if( $tempRes['status'] ){
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
        }
    }
    echo json_encode($result);
}

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
        $tempRes = $teams->GetTeam();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['teams'] = $tempRes['teams'];
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

// Get Team By ID
/* if (isset( $_GET['GetTeamById']) && $_GET['GetTeamById'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $teamid = isset($_GET['GetTeamById']) ? $_GET['GetTeamById'] : 0;

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
    echo json_encode($result);
} */

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
        $tempRes = $player->GetPlayer();

        if( $tempRes['status'] ){

            for( $i=0; $i<sizeof($tempRes['players']); $i++){
                $team_id = $tempRes['players'][$i]['team_id'];
                if($team_id > 0){
                    $team = new Team($db);
                    $team->SetID($team_id);
                    $resTeam = $team->GetTeamByID();
                    if($resTeam['status']){
                        $tempRes['players'][$i]['team'] = $resTeam['team'];
                    }else{
                        $tempRes['players'][$i]['team']['name'] = 'Individu';
                    }
                }else{
                    $tempRes['players'][$i]['team']['name'] = 'Individu';
                }
            }
            $result['status'] = $tempRes['status'];
            $result['players'] = $tempRes['players'];
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
                if($team_id > 0){
                    $team = new Team($db);
                    $team->SetID($team_id);
                    $resTeam = $team->GetTeamByID();
                    if($resTeam['status']){
                        $tempRes['player']['team'] = $resTeam['team'];
                    }else{
                        $tempRes['player']['team']['name'] = 'Individu';
                    }
                }else{
                    $tempRes['player']['team']['name'] = 'Individu';
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

// R Player By ID
/* if (isset( $_GET['GetPlayerByID']) && $_GET['GetPlayerByID'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $playerid = isset($_GET['GetPlayerByID']) ? $_GET['GetPlayerByID'] : 0;

    $database = new Database();
    $db = $database->getConnection();

    $player = new Player($db);
    $player->SetID($playerid);
    $tempRes = $player->GetPlayerByID();
    $database->conn->close();

    if( $tempRes['status'] ){
        $result['status'] = $tempRes['status'];
        $result['player'] = $tempRes['player'];
    }else{
        $result['message'] = "ERROR: Load Player";
    }
    echo json_encode($result);
} */

// Get Game Draws
if (isset( $_GET['GetGameDraws']) && $_GET['GetGameDraws'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameDraws'] == 'all') {

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

?>