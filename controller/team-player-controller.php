<?php

if ( isset( $_GET['team_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['team_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $team = new Team($db);
        $result_query = $team->get_team_list();
        if( $result_query['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_query['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'team/item.php';
                $team_option_template = TEMPLATE_DIR . 'team/option.php';
                $render_item = '';
                $render_option = '<option value="0">Select a team</option>';
                foreach( $result_query['teams'] as $item){
                    $render_item .= template( $item_template, $item);
                    $render_option .= template( $team_option_template, $item);
                }
                $result['teams'] = $render_item;
                $result['team_option'] = $render_option;
            }else{
                $item_template = TEMPLATE_DIR . 'team/no-item.php';
                $render_item = '';
                $render_option = '<option value="0">Select a team</option>';
                $render_item .= template( $item_template, null);
                $result['teams'] = $render_item;
                $result['team_option'] = $render_option;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    /* else if( $_GET['team_get'] == 'option') {
        $database = new Database();
        $db = $database->getConnection();

        $team = new Team($db);
        $result_query = $team->get_team_option();
        if( $result_query['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_query['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'team/option.php';
                $render_item = '<option value="0">Select a team</option>';
                foreach( $result_query['teams'] as $item){
                    $render_item .= template( $item_template, $item);
                }
                $result['teams'] = $render_item;
            }else{
                $render_item = '<option value="0">Select a team</option>';
                $result['teams'] = $render_item;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    } */
    echo json_encode($result);
}

if ( isset( $_GET['player_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['player_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $player = new Player($db);
        $result_query = $player->get_player_list();
        if( $result_query['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_query['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'player/item.php';
                $option_template = TEMPLATE_DIR . 'player/option.php';
                $render_item = '';
                $render_option = '<option value="0">Select a player</option>';
                foreach( $result_query['players'] as $item){
                    $render_item .= template( $item_template, $item);
                    $render_option .= template( $option_template, $item);
                }
                $result['players'] = $render_item;
                $result['player_option'] = $render_option;
            }else{
                $item_template = TEMPLATE_DIR . 'player/no-item.php';
                $render_item = '';
                $render_option = '<option value="0">Select a player</option>';
                $render_item .= template( $item_template, null);
                $result['players'] = $render_item;
                $result['player_option'] = $render_option;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    echo json_encode($result);
}
// CUD Team
if(isset( $_POST['team_action'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $team_action = $_POST['team_action'];
    if( $team_action=='create' || $team_action=='update'){

        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
        $path = BASE_DIR . '/uploads/'; // upload directory

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
            if(in_array($ext, $valid_extensions) ){
                $path = $path.strtolower($final_image);
                /* $pathB = $path.strtolower($final_imageB);  */

                if(move_uploaded_file($tmp,$path)/*  && move_uploaded_file($tmpB,$pathB) */){
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
                            if($prev_team_logo != "no-image.png"){
                                unlink( UPLOAD_DIR . $prev_team_logo );
                            }
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
            }else if( $team_action == 'create'){
                $result['action'] = "create";
                $sql = "INSERT team (team_name,team_logo,team_initial,team_desc) VALUES ('".$name."','no-image.png', '". $team_initial ."', '". $team_desc ."' )";

                $database = new Database();
                $db = $database->getConnection();

                $tempRes = $db->query($sql);
                $database->conn->close();
                if ($tempRes){
                    $result['message'] = "Success";
                    $result['status'] = true;
                }else{
                    $result['message'] = "ERROR: create team";
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
                            $livegameid = GetLiveGameID($db);
                            if($livegameid == $teamPlayerGameDrawsSets[$k]['id']){
                                SetLiveGame($db,0);
                            }
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
                        $livegameid = GetLiveGameID($db);
                        if($livegameid == $teamGameDrawsSets[$k]['id']){
                            SetLiveGame($db,0);
                        }
                    }
                    $teamGamedraw->DeleteGameDraw();
                }
            }else{
                $result['message'] = "ERROR: Get Team";
            }
            // Delete LOGO
            if($resTeam['team']['logo'] != "no-image.png"){
                unlink( UPLOAD_DIR . $resTeam['team']['logo'] );
            }
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

        if( $name == '' || $playerid == 0 ){
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
                        $livegameid = GetLiveGameID($db);
                        if($livegameid == $playerGameDrawGameSets[$j]['id']){
                            SetLiveGame($db,0);
                        }
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
?>