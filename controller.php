<?php

define ("BASE_DIR", dirname(__FILE__));
define ("UPLOAD_DIR", dirname(__FILE__) . "/uploads/");
define("TEMPLATE_DIR", BASE_DIR . '/templates/');

include_once 'config/database.php';
include 'includes/tools.php';
include_once 'objects/config.php';
// include_once 'objects/class-contestant.php';
include_once 'objects/team.php';
include_once 'objects/player.php';
include_once 'objects/gamemode.php';
include_once 'objects/gamedraw.php';
include_once 'objects/gamestatus.php';
include_once 'objects/gameset.php';
include_once 'objects/score.php';
include_once 'objects/livegame.php';
// include_once 'objects/vmixlive.php';
include_once 'objects/webconfig.php';
include_once 'objects/scoreboard.php';
include_once 'objects/bowstyle.php';
include_once 'objects/class-scoreboard-style.php';

// CONTROLLER
include_once 'controller/class-config-controller.php';
// include_once 'controller/contestant-controller.php';
include_once 'controller/team-player-controller.php';
include_once 'controller/gamedraw-controller.php';
include_once 'controller/gamemode-controller.php';
include_once 'controller/score-controller.php';
include_once 'controller/gameset-controller.php';
include_once 'controller/class-bowstyle-controller.php';
include_once 'controller/class-gamestatus-controller.php';
include_once 'controller/scoreboard-style-controller.php';
include_once 'controller/livegame-controller.php';

// Get Init Setup
if (isset( $_GET['InitSetup']) && $_GET['InitSetup'] != '') {
    $result = array(
        'status'    => true,
        'message'   => ''
    );
    if( $_GET['InitSetup'] == '1') {
        $database = new Database();
        $db = $database->getConnection();

        $config = new Config($db);
        $nConfig = $config->CountConfig();
        if($nConfig==0){
            $config->SetID(1);
            $config->SetName('scoreboard');
            // 0 = default ~ 1 = recurve ~ 2 = compound
            $score_config['0']['logo']['visibility'] = true;//false
            $score_config['0']['logo']['text'] = "";
            $score_config['0']['team']['visibility'] = true;
            $score_config['0']['team']['text'] = "";
            $score_config['0']['player']['visibility'] = true;
            $score_config['0']['player']['text'] = "";
            $score_config['0']['timer']['visibility'] = true;//false
            $score_config['0']['timer']['text'] = "";
            $score_config['0']['p1']['visibility'] = true;
            $score_config['0']['p1']['text'] = "";
            $score_config['0']['p2']['visibility'] = true;
            $score_config['0']['p2']['text'] = "";
            $score_config['0']['p3']['visibility'] = true;
            $score_config['0']['p3']['text'] = "";
            $score_config['0']['p4']['visibility'] = true;//false
            $score_config['0']['p4']['text'] = "";
            $score_config['0']['p5']['visibility'] = true;//false
            $score_config['0']['p5']['text'] = "";
            $score_config['0']['p6']['visibility'] = true;//false
            $score_config['0']['p6']['text'] = "";
            $score_config['0']['set_total_points']['visibility'] = true;
            $score_config['0']['set_total_points']['text'] = "";
            $score_config['0']['game_total_points']['visibility'] = true;//false
            $score_config['0']['game_total_points']['text'] = "Total";
            $score_config['0']['set_points']['visibility'] = true;
            $score_config['0']['set_points']['text'] = "";
            $score_config['0']['game_points']['visibility'] = true;//false
            $score_config['0']['game_points']['text'] = "Set pts";
            $score_config['0']['description']['visibility'] = true;
            $score_config['0']['description']['text'] = "";

            $score_config['1']['logo']['visibility'] = false;//false
            $score_config['1']['logo']['text'] = "";
            $score_config['1']['team']['visibility'] = true;
            $score_config['1']['team']['text'] = "";
            $score_config['1']['player']['visibility'] = true;
            $score_config['1']['player']['text'] = "";
            $score_config['1']['timer']['visibility'] = false;//false
            $score_config['1']['timer']['text'] = "";
            $score_config['1']['p1']['visibility'] = true;
            $score_config['1']['p1']['text'] = "";
            $score_config['1']['p2']['visibility'] = true;
            $score_config['1']['p2']['text'] = "";
            $score_config['1']['p3']['visibility'] = true;
            $score_config['1']['p3']['text'] = "";
            $score_config['1']['p4']['visibility'] = false;//false
            $score_config['1']['p4']['text'] = "";
            $score_config['1']['p5']['visibility'] = false;//false
            $score_config['1']['p5']['text'] = "";
            $score_config['1']['p6']['visibility'] = false;//false
            $score_config['1']['p6']['text'] = "";
            $score_config['1']['set_total_points']['visibility'] = true;
            $score_config['1']['set_total_points']['text'] = "Total";
            $score_config['1']['game_total_points']['visibility'] = false;//false
            $score_config['1']['game_total_points']['text'] = "";
            $score_config['1']['set_points']['visibility'] = true;
            $score_config['1']['set_points']['text'] = "";
            $score_config['1']['game_points']['visibility'] = true;
            $score_config['1']['game_points']['text'] = "Set pts";
            $score_config['1']['description']['visibility'] = false;//false
            $score_config['1']['description']['text'] = "";

            $score_config['2']['logo']['visibility'] = false;//false
            $score_config['2']['logo']['text'] = "";
            $score_config['2']['team']['visibility'] = true;
            $score_config['2']['team']['text'] = "";
            $score_config['2']['player']['visibility'] = true;
            $score_config['2']['player']['text'] = "";
            $score_config['2']['timer']['visibility'] = false;//false
            $score_config['2']['timer']['text'] = "";
            $score_config['2']['p1']['visibility'] = true;
            $score_config['2']['p1']['text'] = "";
            $score_config['2']['p2']['visibility'] = true;
            $score_config['2']['p2']['text'] = "";
            $score_config['2']['p3']['visibility'] = true;
            $score_config['2']['p3']['text'] = "";
            $score_config['2']['p4']['visibility'] = false;//false
            $score_config['2']['p4']['text'] = "";
            $score_config['2']['p5']['visibility'] = false;//false
            $score_config['2']['p5']['text'] = "";
            $score_config['2']['p6']['visibility'] = false;//false
            $score_config['2']['p6']['text'] = "";
            $score_config['2']['set_total_points']['visibility'] = true;
            $score_config['2']['set_total_points']['text'] = "";
            $score_config['2']['game_total_points']['visibility'] = true;
            $score_config['2']['game_total_points']['text'] = "Total";
            $score_config['2']['set_points']['visibility'] = false;//false
            $score_config['2']['set_points']['text'] = "";
            $score_config['2']['game_points']['visibility'] = false;//false
            $score_config['2']['game_points']['text'] = "Set pts";
            $score_config['2']['description']['visibility'] = false;//false
            $score_config['2']['description']['text'] = "";

            $config->SetValue(json_encode($score_config));
            $resCreateConfig = $config->CreateDefaultConfig();
            $result['status'] = $resCreateConfig['status'] & $result['status'];
        }

        $gamemode = new GameMode($db);
        $nGameMode = $gamemode->CountGameMode();
        if($nGameMode==0){
            $gamemode->SetID(1);
            $gamemode->SetName('Beregu');
            $gamemode->SetDesc('team vs team');
            $resCreateGM = $gamemode->CreateDefaultGameMode();
            $result['status'] = $resCreateGM['status'] & $result['status'];
            $gamemode->SetID(2);
            $gamemode->SetName('Individu');
            $gamemode->SetDesc('individu vs individu');
            $resCreateGM = $gamemode->CreateDefaultGameMode();
            $result['status'] = $resCreateGM['status'] & $result['status'];
        }

        $bowstyle = new Bowstyle($db);
        $nBowstyle = $bowstyle->count();
        if($nBowstyle==0){
            $is_success = $bowstyle->set_data(array('id'=>1,'name'=>'Recurve'))->create();
            $result['status'] = $is_success & $result['status'];
            $is_success = $bowstyle->set_data(array('id'=>2,'name'=>'Compound'))->create();
            $result['status'] = $is_success & $result['status'];
        }

        $scoreboard_style = new Scoreboard_Style($db);
        if( $scoreboard_style->is_table_empty()){
            $success_create_default_value = $scoreboard_style->create_default_style_config();
            $result['status'] = $success_create_default_value & $result['status'];
        }

        $gamestatus = new GameStatus($db);
        $nGameStatus = $gamestatus->CountGameStatus();
        if($nGameStatus==0){
            $gamestatus->SetID(1);
            $gamestatus->SetName('Stand by');
            $resCreateGS = $gamestatus->CreateDefaultGameStatus();
            $result['status'] = $resCreateGS['status'] & $result['status'];
            $gamestatus->SetID(2);
            $gamestatus->SetName('Live');
            $resCreateGS = $gamestatus->CreateDefaultGameStatus();
            $result['status'] = $resCreateGS['status'] & $result['status'];
            $gamestatus->SetID(3);
            $gamestatus->SetName('Finished');
            $resCreateGS = $gamestatus->CreateDefaultGameStatus();
            $result['status'] = $resCreateGS['status'] & $result['status'];
        }

        $livegame = new Live_Game($db);
        $nLiveGame = $livegame->CountLiveGame();
        if($nLiveGame==0){
            $livegame->SetGameSetID(0);
            $resLiveGameCreate = $livegame->CreateDefaultLiveGame();
            $result['status'] = $resLiveGameCreate['status'] & $result['status'];
        }

        $config = new Web_Config($db);
        $row = $config->count();
        if($row==0){
            $is_success = $config->create_default();
            $result['status'] = $is_success & $result['status'];
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

// Live Game


/**
 * Function
 */

// Live_Game
/* function SetLiveGame( $db, $livegameid ){
    $livegame = new Live_Game($db);
    $livegame->SetGameSetID($livegameid);
    $resLiveGame = $livegame->UpdateLiveGame();

    return $resLiveGame['status'];
} */

function GetLiveGameID ($db){
    $livegame = new Live_Game($db);
    $resLiveGame = $livegame->GetLiveGameID();

    if($resLiveGame['status']){
        return $resLiveGame['live_game'];
    }
    return 0;
}

if (isset( $_GET['title_get']) && $_GET['title_get'] != '' /* && isset( $_GET['mode']) && $_GET['mode'] != '' */) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $scoreboard = $_GET['title_get'];
    // $mode = is_numeric($_GET['mode']) ? $_GET['mode'] : 0;
    $mode = 0;
    if ( $scoreboard == 'scoreboard' ) {
        $database = new Database();
        $db = $database->getConnection();

        $config = new Web_Config($db);
        $resConfig = $config->get();
        if($resConfig['status']){
            $mode = $resConfig['config']['active_mode'];
            $result['active_mode'] = $mode;
            if($mode==0){
                $result['message'] = "INVISIBLE";
            }else{
                $livegame = new Live_Game($db);
                $resLiveGame = $livegame->GetLiveGameID();

                if($resLiveGame['status']){
                    $result['scoreboard'] = array();
                    $scoreboard = new ScoreBoard();

                    $result['scoreboard'] = $scoreboard->GetDefaultDataMode( $mode);

                    $result['scoreboard']['live_game'] = $resLiveGame['live_game'];

                    $gameset_id = $resLiveGame['live_game'];
                    if($gameset_id > 0){
                        $result['status'] = true;
                        $gameset = new GameSet($db);
                        $gameset->SetID($gameset_id);
                        $resLiveGameSet = $gameset->GetGameSetByID();

                        if($resLiveGameSet['status']){
                            $result['status'] = true;
                            $liveGameSet = $resLiveGameSet['gameset'];
                            $gameset->SetGameDrawID($liveGameSet['gamedraw_id']);
                            $resGameDraw = $gameset->GetGameDraw();
                            $result['scoreboard']['set'] = $liveGameSet['num'];

                            $gamedraw = new GameDraw($db);
                            $gamedraw->SetContestantAID($resGameDraw['contestant_a_id']);
                            $gamedraw->SetContestantBID($resGameDraw['contestant_b_id']);
                            /**
                             * TO-DO: Dinamis Game Mode
                             */
                            // $result['scoreboard']['DEBUG'] = $resGameDraw['contestant_a_id'];

                            $result['scoreboard']['gamemode'] = $resGameDraw['gamemode_id'];
                            if($resGameDraw['gamemode_id'] == 1){
                                $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $gamedraw->GetTeamContestantA()['logo'];
                                $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $gamedraw->GetTeamContestantB()['logo'];
                                $result['scoreboard']['team_a'] = $gamedraw->GetTeamContestantA()['name'];
                                $result['scoreboard']['team_b'] = $gamedraw->GetTeamContestantB()['name'];
                            }else if($resGameDraw['gamemode_id'] == 2){
                                $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-image.png";
                                $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-image.png";
                                $contestant_a = $gamedraw->GetPlayerContestantA();
                                $contestant_b = $gamedraw->GetPlayerContestantB();
                                $result['scoreboard']['player_a'] = $contestant_a['name'];
                                $result['scoreboard']['player_b'] = $contestant_b['name'];
                                $result['scoreboard']['team_a'] = "";
                                $result['scoreboard']['team_b'] = "";
                                if($contestant_a['team_id']>0 || $contestant_b['team_id']>0){
                                    $team = new Team($db);

                                    $result_query_logo = $team->id($contestant_a['team_id'])->get_logo();
                                    $result_query_name = $team->id($contestant_a['team_id'])->get_name();
                                    if($result_query_logo['status']){
                                        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $result_query_logo['logo'];
                                    }

                                    if($result_query_name['status']){
                                        $result['scoreboard']['team_a'] = $result_query_name['name'];
                                    }

                                    $result_query_logo = $team->id($contestant_b['team_id'])->get_logo();
                                    $result_query_name = $team->id($contestant_b['team_id'])->get_name();
                                    if($result_query_logo['status']){
                                        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/" . $result_query_logo['logo'];
                                    }

                                    if($result_query_name['status']){
                                        $result['scoreboard']['team_a'] = $result_query_name['name'];
                                    }
                                }

                            }

                            // Get CONFIG
                            $bowstyle_id = $resGameDraw['bowstyle_id'];
                            $app_config = new Config($db);
                            $resAppCfg = $app_config->GetConfigs();
                            if($resAppCfg['status']){
                                foreach ($resAppCfg['configs'] as $key => $val) {
                                    if($val['name']=='scoreboard'){
                                        $scoreboard_cfg = json_decode($val['value']);
                                        $result['scoreboard']['config'] = $scoreboard_cfg[$bowstyle_id];
                                        break;
                                    }
                                }
                            }

                            $result['scoreboard']['bowstyle_id'] = $bowstyle_id;

                            $gamedraw->SetID($resGameDraw['id']);
                            $resTotPoints = $gamedraw->GetTotalSetPoints($resGameDraw['contestant_a_id']);
                            $result['scoreboard']['game_total_points_a'] = $resTotPoints['game_total_points'];
                            $result['scoreboard']['gamepoints_a'] = $resTotPoints['game_points'];
                            $resTotPoints = $gamedraw->GetTotalSetPoints($resGameDraw['contestant_b_id']);
                            $result['scoreboard']['game_total_points_b'] = $resTotPoints['game_total_points'];
                            $result['scoreboard']['gamepoints_b'] = $resTotPoints['game_points'];

                            $result['scoreboard']['num_set'] = sizeof($gamedraw->GetGameSets());

                            $score = new Score($db);
                            $score->SetGameSetID($liveGameSet['id']);
                            $score->SetContestantID($resGameDraw['contestant_a_id']);
                            $resScore = $score->GetScoreByGameSetAndContestant();

                            $timeout_a = false;
                            $hasDesc_a = false;
                            $timeout_b = false;
                            $hasDesc_b = false;

                            if($resScore['status']){
                                $result['status'] = true && $result['status'];
                                $score_a = $resScore['score'];
                                if($score_a['timer'] < 10){
                                    $timeout_a = true;
                                }
                                if($score_a['desc'] != ''){
                                    $hasDesc_a = true;
                                }
                                $result['scoreboard']['timer_a'] = $score_a['timer'];
                                // if( $mode == 2){
                                    $result['scoreboard']['point_1a'] = $score_a['score_1'];
                                    $result['scoreboard']['point_2a'] = $score_a['score_2'];
                                    $result['scoreboard']['point_3a'] = $score_a['score_3'];
                                    $result['scoreboard']['point_4a'] = $score_a['score_4'];
                                    $result['scoreboard']['point_5a'] = $score_a['score_5'];
                                    $result['scoreboard']['point_6a'] = $score_a['score_6'];
                                // }
                                $result['scoreboard']['desc_a'] = $score_a['desc'];
                                $total_a = $score_a['score_1'] + $score_a['score_2'] + $score_a['score_3'] + $score_a['score_4'] + $score_a['score_5'] + $score_a['score_6'];
                                // if( $mode == 1 || $mode == 2){
                                    $result['scoreboard']['total_a'] = $total_a;
                                // }
                                $result['scoreboard']['setpoints_a'] = $score_a['point'];
                            }else{
                                $result['message'][] = "ERROR: Load Score A";
                            }

                            $score->SetContestantID($resGameDraw['contestant_b_id']);
                            $resScore = $score->GetScoreByGameSetAndContestant();

                            if($resScore['status']){
                                $result['status'] = true && $result['status'];
                                $score_b = $resScore['score'];
                                if($score_b['timer'] < 10){
                                    $timeout_b = true;
                                }
                                if($score_b['desc'] != ''){
                                    $hasDesc_b = true;
                                }
                                $result['scoreboard']['timer_b'] = $score_b['timer'];
                                // if( $mode == 2){
                                    $result['scoreboard']['point_1b'] = $score_b['score_1'];
                                    $result['scoreboard']['point_2b'] = $score_b['score_2'];
                                    $result['scoreboard']['point_3b'] = $score_b['score_3'];
                                    $result['scoreboard']['point_4b'] = $score_b['score_4'];
                                    $result['scoreboard']['point_5b'] = $score_b['score_5'];
                                    $result['scoreboard']['point_6b'] = $score_b['score_6'];
                                // }
                                $result['scoreboard']['desc_b'] = $score_b['desc'];
                                $total_b = $score_b['score_1'] + $score_b['score_2'] + $score_b['score_3'] + $score_b['score_4'] + $score_b['score_5'] + $score_b['score_6'];
                                // if( $mode == 1 || $mode == 2){
                                    $result['scoreboard']['total_b'] = $total_b;
                                // }
                                $result['scoreboard']['setpoints_b'] = $score_b['point'];
                            }else{
                                $result['message'] = "ERROR: Load Score B";
                            }
                            $result['scoreboard']['timeout_a'] = $timeout_a;
                            $result['scoreboard']['has_desc_a'] = $hasDesc_a;
                            $result['scoreboard']['timeout_b'] = $timeout_b;
                            $result['scoreboard']['has_desc_b'] = $hasDesc_b;
                            // if($mode == 1){
                            // }else if($mode == 2 || $mode == 3){
                            //     if($hasDesc_a && !$hasDesc_b){
                            //         $result['scoreboard']['backgroud'] = $scoreboard->GetBackgroundMode( $mode, 1);
                            //     }else if($hasDesc_a && $hasDesc_b){
                            //         $result['scoreboard']['backgroud'] = $scoreboard->GetBackgroundMode( $mode, 2);
                            //     }else if(!$hasDesc_a && $hasDesc_b){
                            //         $result['scoreboard']['backgroud'] = $scoreboard->GetBackgroundMode( $mode, 3);
                            //     }else if(!$hasDesc_a && !$hasDesc_b){
                            //         $result['scoreboard']['backgroud'] = $scoreboard->GetBackgroundMode( $mode, 4);
                            //     }
                            // }
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
        }else{
            $result['message'] = "ERROR: LOAD WEB CONFIG";
        }

    }

    // echo json_encode($result['scoreboard']);
    echo json_encode($result);

}

// Get Game List
/* if ( isset( $_GET['game_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['game_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $class_query = new Class_Query($db);
        $result_query = $class_query->get_game_table();
        if( $result_query['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_query['has_value'];
            if($result['has_value']){
                $gamedraw_item_template = TEMPLATE_DIR . 'gamedraw/item.php';
                $render_gamedraw_item = '';
                foreach( $result_query['gamedraws'] as $item){
                    $render_gamedraw_item .= template( $gamedraw_item_template, $item);
                }

                $gamedraw_option_template = TEMPLATE_DIR . 'gamedraw/option.php';
                $render_gamedraw_option = '<option value="0">Select a game draw</option>';
                foreach( $result_query['gamedraw_option'] as $item){
                    $render_gamedraw_option .= template( $gamedraw_option_template, $item);
                }

                $gameset_item_template = TEMPLATE_DIR . 'gameset/item.php';
                $render_gameset_item = '';
                foreach( $result_query['gamesets'] as $item){
                    $render_gameset_item .= template( $gameset_item_template, $item);
                }

                $result['gamedraws'] = $render_gamedraw_item;
                $result['gamedraw_option'] = $render_gamedraw_option;
                $result['gamesets'] = $render_gameset_item;
            }else{
                $gamedraw_item_template = TEMPLATE_DIR . 'gamedraw/no-item.php';
                $render_gamedraw_item = '';
                $render_gamedraw_item .= template( $gamedraw_item_template, null);
                $result['gamedraws'] = $render_gamedraw_item;

                $gamedraw_option_template = TEMPLATE_DIR . 'gamedraw/option.php';
                $render_gamedraw_option = '<option value="0">Select a game draw</option>';
                $result['gamedraw_option'] = $render_gamedraw_option;

                $gameset_item_template = TEMPLATE_DIR . 'gameset/no-item.php';
                $render_gameset_item = '';
                $render_gameset_item .= template( $gameset_item_template, null);
                $result['gamesets'] = $render_gameset_item;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    echo json_encode($result);
} */

// CUD Config


// Get Game Status
/* if (isset( $_GET['GetGameStatus']) && $_GET['GetGameStatus'] != '') {
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
} */
/* if ( isset( $_GET['gamestatus_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['gamestatus_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $gamestatus = new GameStatus($db);
        $result_array = $gamestatus->get_gamestatus_list();

        $database->conn->close();

        if( $result_array['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_array['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'gamestatus/option.php';
                $renderitem = '<option value="0">Select a status</option>';
                foreach( $result_array['gamestatuses'] as $item){
                    $renderitem .= template( $item_template, $item);
                }
                $result['gamestatuses'] = $renderitem;
            }else{
                $renderitem = '<option value="0">Select a status</option>';
                $renderitem .= template( $item_template, NULL);

                $result['gamestatuses'] = $renderitem;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
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
        $vmixlive = new Live_Game($db);
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
        $vmixlive = new Live_Game($db);
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

// Init Scoreboard
/* if (isset( $_GET['InitScoreboard']) && $_GET['InitScoreboard'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['InitScoreboard'] == 'live') {

        $database = new Database();
        $db = $database->getConnection();

        $vmixlive = new Live_Game($db);
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
?>