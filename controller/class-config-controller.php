<?php

// Get Config
if (isset( $_GET['config_get']) && $_GET['config_get'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['config_get'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $config = new Web_Config($db);
        $result_config_query = $config->get();

        $bowstyle_id=0;
        $livegame = new Live_Game($db);
        $result_query = $livegame->get_live_bowstyle_id();
        if($result_query['status']){
            $bowstyle_id = $result_query['bowstyle_id'];
        }
        /* $resLiveGame = $livegame->GetLiveGameID();

        if($resLiveGame['status']){
            $gameset_id = $resLiveGame['live_game'];

            if($gameset_id!=0){
                $gameset = new GameSet($db);
                $gameset->SetID( $gameset_id );
                $tempGSRes = $gameset->GetGameSetByID();

                if( $tempGSRes['status'] ){
                    $gamedraw_id = $tempGSRes['gameset']['gamedraw_id'];
                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetID($gamedraw_id);
                    $tempGDRes = $gamedraw->GetGameDrawByID();
                    if($tempGDRes['status']){
                        $bowstyle_id = $tempGDRes['gamedraw']['bowstyle_id'];
                    }
                }
            }
        } */

        $app_config = new Config($db);
        // $tempAppCfgRes = $app_config->GetConfigs();
        $result_scoreboard_config = $app_config->get_scoreboard_config();

        $database->conn->close();

        $result['status'] = $result_config_query['status'] && $result_scoreboard_config['status'];

        if( $result['status'] ){
            $result['config'] = $result_config_query['config'];
            $scoreboard_cfg = json_decode($result_scoreboard_config['scoreboard_config']);
            $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
            $active_mode = $result['config']['active_mode'];
            $result['config']['preview_scoreboard']['mode'] = $active_mode;
            switch ($active_mode) {
                case 1: case 2: case 3:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[0];
                    break;
                case 4: case 5: case 6:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[1];
                    break;
                case 7: case 8: case 9:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[2];
                    break;

                default:
                    $result['config']['preview_scoreboard']['cfg'] = null;
                    break;
            }
            /* foreach ($tempAppCfgRes['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
                }
            } */
        }
    }
    else if( $_GET['config_get'] == 'scoreboard' && isset( $_GET['mode']) && is_numeric( $_GET['mode']) && $_GET['mode'] > 0){

        $mode = $_GET['mode'];

        $database = new Database();
        $db = $database->getConnection();

        $app_config = new Config($db);
        // $resAppCfg = $app_config->GetConfigs();
        $result_scoreboard_config = $app_config->get_scoreboard_config();

        $database->conn->close();

        if($result_scoreboard_config['status']){
            $scoreboard_cfg = json_decode($result_scoreboard_config['scoreboard_config']);

            switch ($mode) {
                case 1: case 2: case 3:
                    $result['config'] = $scoreboard_cfg[0];
                    break;
                case 4: case 5: case 6:
                    $result['config'] = $scoreboard_cfg[1];
                    break;
                case 7: case 8: case 9:
                    $result['config'] = $scoreboard_cfg[2];
                    break;
                default:
                    $result['config'] = null;
                    break;
            }
            $result['status'] = true;
        }else{
            $result['message'] = "Can't load config.";
        }

        /* $database->conn->close();

        if($resAppCfg['status']){
            foreach ($resAppCfg['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config'] = $scoreboard_cfg;
                    $result['status'] = true;
                    break;
                }
            }
        }else{
            $result['message'] = "Can't load config.";
        } */
    }
    echo json_encode($result);
}
if ( isset( $_POST['config_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['config_action'] == 'update') {

        $config_id = isset($_POST['config_id']) ? $_POST['config_id'] : 0;
        $time_interval = isset($_POST['config_time_interval']) ? $_POST['config_time_interval'] : 1000;
        $active_mode = isset($_POST['config_active_mode']) ? $_POST['config_active_mode'] : 0;

        if( $config_id == 0 || $time_interval == 0 ){
            $result['message'] = "ERROR: All fields are required!";
        }else{

            $config_data = array(
                'id'            => $config_id,
                'time_interval' => $time_interval,
                'active_mode'   => $active_mode
            );
            $database = new Database();
            $db = $database->getConnection();

            $config = new Web_Config($db);
            $is_success = $config->set_data($config_data)->update();
            $database->conn->close();
            if( $is_success ){
                $result['status'] = $is_success;
                $result['action'] = 'update';
                $result['activated_mode'] = $active_mode;
            }

        }

    }
    echo json_encode($result);
}

//==================================================================================================
// Get Config
/* if (isset( $_GET['GetConfig']) && $_GET['GetConfig'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetConfig'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $config = new Web_Config($db);
        $tempRes = $config->get();

        $bowstyle_id=0;
        $livegame = new Live_Game($db);
        $resLiveGame = $livegame->GetLiveGameID();

        if($resLiveGame['status']){
            $gameset_id = $resLiveGame['live_game'];

            if($gameset_id!=0){
                $gameset = new GameSet($db);
                $gameset->SetID( $gameset_id );
                $tempGSRes = $gameset->GetGameSetByID();

                if( $tempGSRes['status'] ){
                    $gamedraw_id = $tempGSRes['gameset']['gamedraw_id'];
                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetID($gamedraw_id);
                    $tempGDRes = $gamedraw->GetGameDrawByID();
                    if($tempGDRes['status']){
                        $bowstyle_id = $tempGDRes['gamedraw']['bowstyle_id'];
                    }
                }
            }
        }

        $app_config = new Config($db);
        $tempAppCfgRes = $app_config->GetConfigs();

        $database->conn->close();

        $result['status'] = $tempRes['status'] && $tempAppCfgRes['status'];

        if( $result['status'] ){
            $result['config'] = $tempRes['config'];
            foreach ($tempAppCfgRes['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
                }
            }
        }
    }
    else if( $_GET['GetConfig'] == 'scoreboard'){

        $database = new Database();
        $db = $database->getConnection();

        $app_config = new Config($db);
        $resAppCfg = $app_config->GetConfigs();

        $database->conn->close();

        if($resAppCfg['status']){
            foreach ($resAppCfg['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config'] = $scoreboard_cfg;
                    $result['status'] = true;
                    break;
                }
            }
        }else{
            $result['message'] = "Can't load config.";
        }
    }
    echo json_encode($result);
} */

?>