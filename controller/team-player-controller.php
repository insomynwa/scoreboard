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
    }else if ($_GET['team_get'] == 'single') {
        $teamid = isset($_GET['id']) ? $_GET['id'] : 0;
        if(is_numeric($teamid) > 0){

            $database = new Database();
            $db = $database->getConnection();

            $obj_team = new Team($db);
            $response = $obj_team->get_by_id($teamid);
            $database->conn->close();

            if( $response['status'] ){
                $result['status'] = $response['status'];
                $result['team'] = $response['team'];
            }else{
                $result['message'] = "ERROR: Load Team";
            }

        }else{
            $result['message'] = "ERROR: id must be numeric";
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
    }else if ($_GET['player_get'] == 'single') {
        $playerid = isset($_GET['id']) ? $_GET['id'] : 0;
        if(is_numeric($playerid) > 0){

            $database = new Database();
            $db = $database->getConnection();

            $obj_player = new Player($db);
            $response = $obj_player->get_by_id($playerid);

            if( $response['status'] ){
                $result['status'] = $response['status'];
                if( is_null( $response['player']['team_id'] ) ){
                    $response['player']['team_id'] = 0;
                    $response['player']['team_name'] = 'Individu';
                }

                $result['player'] = $response['player'];
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

        $team_data = array(
            'id'            => 0,
            'name'          => $name,
            'logo'          => '',
            'initial'       => $team_initial,
            'description'   => $team_desc
        );

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
                        $team_data['logo'] = strtolower($final_image);
                        $team = new Team($db);
                        $is_success = $team->set_data($team_data)->create();
                        $database->conn->close();

                        if($is_success){
                            $result['message'] = 'Success';
                            $result['status'] = $is_success;
                        }else{
                            $result['message'] = 'ERROR: create a team';
                        }

                        // $sql = "INSERT team (team_name,team_logo,team_initial,team_desc) VALUES ('".$name."','". strtolower($final_image) ."', '". $team_initial ."', '". $team_desc ."' )";
                    }else if ( $team_action == 'update'){
                        $result['action'] = 'update';
                        $result['new_logo'] = '';
                        $team_id = $_POST['team_id'];

                        $team = new Team($db);
                        $result_query = $team->id($team_id)->get_logo();
                        if($result_query['status']){
                            $prev_team_logo = $result_query['logo'];
                            $result['new_logo'] = $final_image;

                            $team_data['id'] = $team_id;
                            $team_data['logo'] = $final_image;

                            $is_success = $team->set_data($team_data)->update();
                            $database->conn->close();

                            if( $is_success){
                                $result['message'] = 'success';
                                $result['status'] = $is_success;
                                if($prev_team_logo != "no-image.png"){
                                    unlink( UPLOAD_DIR . $prev_team_logo );
                                }
                            }else{
                                $result['message'] = "ERROR: update TEAM";
                            }
                        }else{
                            $result['message'] = "ERROR: get TEAM logo";
                        }
                    }
                }else{
                    if ( $team_action == 'update'){
                        $result['action'] = "update";
                        $team_id = $_POST['team_id'];

                        $database = new Database();
                        $db = $database->getConnection();

                        $team = new Team($db);
                        $result_query = $team->id($team_id)->get_logo();
                        if($result_query['status']){
                            $prev_team_logo = $result_query['logo'];
                            $result['new_logo'] = $prev_team_logo;

                            $team_data['id'] = $team_id;
                            $team_data['logo'] = $prev_team_logo;

                            $is_success = $team->set_data($team_data)->update();
                            $database->conn->close();

                            if( $is_success){
                                $result['message'] = 'ERROR: update logo, SUCCESS: update data';
                                $result['status'] = $is_success;
                            }else{
                                $result['message'] = "ERROR: update TEAM";
                            }
                        }else{
                            $result['message'] = "ERROR: get TEAM logo";
                        }


                        /* $result['action'] = "update";
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
                        } */
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
                $result['action'] = "update";
                $team_id = $_POST['team_id'];

                $database = new Database();
                $db = $database->getConnection();

                $team = new Team($db);
                $result_query = $team->id($team_id)->get_logo();
                if($result_query['status']){
                    $prev_team_logo = $result_query['logo'];
                    $result['new_logo'] = $prev_team_logo;

                    $team_data['id'] = $team_id;
                    $team_data['logo'] = $prev_team_logo;

                    $is_success = $team->set_data($team_data)->update();
                    $database->conn->close();

                    if( $is_success){
                        $result['message'] = 'success';
                        $result['status'] = $is_success;
                    }else{
                        $result['message'] = "ERROR: update TEAM";
                    }
                }else{
                    $result['message'] = "ERROR: get TEAM logo";
                }
            }else if( $team_action == 'create'){
                $result['action'] = "create";

                $database = new Database();
                $db = $database->getConnection();

                $team = new Team($db);
                $is_success = $team->set_data($team_data)->create();
                $database->conn->close();

                if($is_success){
                    $result['message'] = 'Success';
                    $result['status'] = $is_success;
                }else{
                    $result['message'] = 'ERROR: create a team';
                }
            }else{
                $result['message'] = "ERROR: team_action";
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

            $score = new Score($db);
            $score_resul_query = $score->delete_team_related_score($teamid);
            $result['status'] = $score_resul_query['status'];
            if($score_resul_query['status']){

                $livegame = new Live_Game($db);
                $is_live = $livegame->is_team_playing($teamid);
                if($is_live){
                    $result_livegame_query = $livegame->set_live(0);
                    if($result_livegame_query['status'] != true){
                        $result['message'] = "ERROR: set TEAM LIVEGAME";
                    }
                }else{
                    $result['message'] = "ERROR: no TEAM LIVEGAME/error";
                }

                $gameset = new GameSet($db);
                $gameset_result_query = $gameset->delete_team_related_gameset($teamid);
                $result['status'] = $result['status'] && $gameset_result_query['status'];
                if($gameset_result_query['status']){

                    $gamedraw = new GameDraw($db);
                    $gamedraw_result_query = $gamedraw->delete_team_related_gamedraw($teamid);
                    $result['status'] = $result['status'] && $gamedraw_result_query['status'];
                    if($gamedraw_result_query['status']){
                        $player = new Player($db);
                        $player_result_query = $player->delete_team_related_player($teamid);
                        $result['status'] = $result['status'] && $player_result_query['status'];
                        if($player_result_query['status']){
                            $team = new Team($db);
                            $result_team_logo_query = $team->id($teamid)->get_logo();
                            if($result_team_logo_query['status']){
                                if($result_team_logo_query['logo'] != "no-image.png"){
                                    unlink( UPLOAD_DIR . $result_team_logo_query['logo'] );
                                }else{
                                    // $result['message'] = "TEAM has no logo";
                                }
                            }else{
                                $result['message'] = "ERROR: get TEAM logo filename";
                            }
                            $result_team_query = $team->id($teamid)->delete();
                            $result['status'] = $result['status'] && $result_team_query['status'];
                            if($result['status']){
                                $result['action'] = 'delete';
                            }else{
                                $result['message'] = "ERROR: delete TEAM";
                            }
                        }else{
                            $result['message'] = "ERROR: delete TEAM related PLAYER";
                        }
                    }else{
                        $result['message'] = "ERROR: delete TEAM related GAMEDRAW";
                    }
                }else{
                    $result['message'] = "ERROR: delete TEAM related GAMESET";
                }
            }else{
                $result['message'] = "ERROR: delete TEAM related SCORE";
            }

            $database->conn->close();

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

            $player_data = array(
                'id'            => 0,
                'name'          => $player_name,
                'team_id'          => $team_id
            );

            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $is_success = $player->set_data($player_data)->create();
            $database->conn->close();

            if( $is_success){
                $result['status'] = $is_success;
                $result['action'] = 'create';
                $result['message'] = "success";
            }else{
                $result['message'] = "ERROR: create PLAYER";
            }

        }

    }else if( $_POST['player_action'] == 'update') {

        $name = isset($_POST['player_name']) ? $_POST['player_name'] : '';
        $teamid = isset($_POST['player_team']) ? $_POST['player_team'] : 0;
        $playerid = isset($_POST['player_id']) ? $_POST['player_id'] : 0;

        if( $name == '' || $playerid == 0 ){
            $result['message'] = "ERROR: All fields are required!";
        }else{

            $player_data = array(
                'id'            => $playerid,
                'name'          => $name,
                'team_id'          => $teamid
            );

            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $is_success = $player->set_data($player_data)->update();
            $database->conn->close();

            if( $is_success){
                $result['status'] = $is_success;
                $result['action'] = 'update';
                $result['message'] = "success";
            }else{
                $result['message'] = "ERROR: update PLAYER";
            }
        }

    }else if( $_POST['player_action'] == 'delete') {

        $playerid = isset($_POST['player_id']) ? $_POST['player_id'] : 0;

        if( $playerid == 0 ){
            $result['message'] = "ERROR: player id 0";
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $score = new Score($db);
            $score_success_delete = $score->delete_player_related_score($playerid);
            $result['status'] = $score_success_delete;
            if($score_success_delete){

                $livegame = new Live_Game($db);
                $is_live = $livegame->is_player_playing($playerid);
                if($is_live){
                    $result_livegame_query = $livegame->set_live(0);
                    if($result_livegame_query['status'] != true){
                        $result['message'] = "ERROR: set PLAYER LIVEGAME";
                    }
                }else{
                    $result['message'] = "ERROR: no PLAYER LIVEGAME/error";
                }

                $gameset = new GameSet($db);
                $gameset_success_delete = $gameset->delete_player_related_gameset($playerid);
                $result['status'] = $result['status'] && $gameset_success_delete;
                if($gameset_success_delete){

                    $gamedraw = new GameDraw($db);
                    $gamedraw_success_delete = $gamedraw->delete_player_related_gamedraw($playerid);
                    $result['status'] = $result['status'] && $gamedraw_success_delete;
                    if($gamedraw_success_delete){
                        $player = new Player($db);
                        $player_success_delete = $player->id($playerid)->delete();
                        $result['status'] = $result['status'] && $player_success_delete;
                        if($player_success_delete){
                            if($result['status']){
                                $result['action'] = 'delete';
                            }else{
                                $result['message'] = "ERROR: delete PLAYER";
                            }
                        }else{
                            $result['message'] = "ERROR: delete PLAYER";
                        }
                    }else{
                        $result['message'] = "ERROR: delete PLAYER related GAMEDRAW";
                    }
                }else{
                    $result['message'] = "ERROR: delete PLAYER related GAMESET";
                }
            }else{
                $result['message'] = "ERROR: delete PLAYER related SCORE";
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