<?php

if ( isset( $_GET['gameset_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['gameset_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $result_query = $gameset->get_gameset_list();
        if( $result_query['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_query['has_value'];
            if($result['has_value']){
                /* $gamedraw_list = $resGamedraws['gamedraws'];
                $result['gamedraws'] = $gamedraw_list; */
                // Get the gameset
                /* for( $i=0; $i<sizeof($gamedraw_list); $i++){
                    $gameset = new GameSet($db);
                    $resGameSets = $gameset->GetGameSetListByGameDrawID($gamedraw_list[$i]['id']);
                    if( $resGameSets['status']){
                        if($resGameSets['has_value']){
                            $result['gamedraws'][$i]['gamesets'] = $resGameSets['gamesets'];
                        }else{
                            $result['gamedraws'][$i]['gamesets'] = array();
                        }
                    }else{
                        $result['gamedraws'][$i]['gamesets'] = array();
                    }
                } */
                $item_template = TEMPLATE_DIR . 'gameset/item.php';
                $render_item = '';
                foreach( $result_query['gamesets'] as $item){
                    $render_item .= template( $item_template, $item);
                }
                $result['gamesets'] = $render_item;
            }else{
                $item_template = TEMPLATE_DIR . 'gameset/no-item.php';
                $render_item = '';
                $render_item .= template( $item_template, null);
                $result['gamesets'] = $render_item;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    else if ( $_GET['gameset_get'] == 'new_num' && isset( $_GET['gamedraw_id']) && is_numeric( $_GET['gamedraw_id']) && $_GET['gamedraw_id'] > 0){
        $database = new Database();
        $db = $database->getConnection();

        $gamedraw_id = $_GET['gamedraw_id'];
        $gameset = new GameSet($db);
        $result_array = $gameset->gamedraw_id($gamedraw_id)->get_last_set();

        $database->conn->close();

        if($result['status'] = $result_array['status']){
            if($result_array['last_set'] != NULL){
                $result['new_set'] = $result_array['last_set'] + 1;
            }else{
                $result['new_set'] = 1;
            }

        }

    }
    else if ( $_GET['gameset_get'] == 'single' && isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0) {
        $gameset_id = $_GET['id'];

        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $result_array = $gameset->id($gameset_id)->action('update')->get_this_gameset();
        if($result['status'] = $result_array['status']){
            if($result_array['has_value']){
                $result['gameset'] = $result_array['gameset'];
            }
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

        $gamedraw_id = isset($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
        $gameset_num = isset($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;

        if($gamedraw_id>0 && $gameset_num>0){

            $database = new Database();
            $db = $database->getConnection();

            $data = array(
                'id'            => 0,
                'gamedraw_id'   => $gamedraw_id,
                'num'           => $gameset_num,
                'status_id'     => 0
            );

            $gameset = new GameSet($db);
            $result_query = $gameset->set_data($data)->create();
            if($result_query['status']){
                $latest_gameset_id = $result_query['latest_id'];

                $gamedraw = new GameDraw($db);
                $result_game_contestants_query = $gamedraw->id($gamedraw_id)->get_contestants();
                if($result_game_contestants_query['status']){
                    $score_data['gameset_id'] = $latest_gameset_id;

                    $score = new Score($db);
                    $score_data['contestant_id'] = $result_game_contestants_query['contestant_a_id'];
                    $success_create_score_a = $score->set_data($score_data)->create();

                    $score_data['contestant_id'] = $result_game_contestants_query['contestant_b_id'];
                    $success_create_score_b = $score->set_data($score_data)->create();

                    $result['status'] = $success_create_score_a && $success_create_score_b;
                    if($result['status']){
                        $result['action'] = 'create';
                    }else{
                        $result['message'] = 'ERROR: Create SCORE';
                    }
                }else{
                    $result['message'] = 'ERROR: get CONTESTANT';
                }
            }else{
                $result['message'] = 'ERROR: Create GAMESET';
            }

            $database->conn->close();

            /* $gameset = new GameSet( $db );
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

            $database->conn->close(); */
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
        $gameset_prev_status = isset($_POST['gameset_prev_status']) ? $_POST['gameset_prev_status'] : 1;

        $database = new Database();
        $db = $database->getConnection();

        $gameset_data = array(
            'id'            => $gameset_id,
            'gamedraw_id'   => $gamedraw_id,
            'num'           => $gameset_num,
            'status_id'     => $gameset_status
        );

        /*
        * TO-DO: Set Num verif. & valid.
        */
        $gameset = new GameSet( $db );

        $success_update_gameset = $gameset->set_data($gameset_data)->update();

        if($success_update_gameset){
            $livegame = new Live_Game($db);
            $current_live_gameset_id = $livegame->get_live_gameset_id();
            if($current_live_gameset_id != $gameset_id){
                if($gameset_status==2){
                    $livegame->set_live($gameset_id);
                    if($current_live_gameset_id > 0){
                        $success_update_curr_live_gameset = $gameset->id($current_live_gameset_id)->set_status_standby();
                    }
                }
            }else{
                if($gameset_status!=2){
                    $livegame->set_live(0);
                }
            }
            $result['status'] = $success_update_gameset;
            $result['action'] = 'update';
        }else{
            $result['message'] = 'ERROR: Update GAMESET';
        }

        $database->conn->close();

        /* $gameset->SetID( $gameset_id );
        $gameset->SetNum( $gameset_num );
        $gameset->SetStatus ( $gameset_status );
        $tempRes = $gameset->UpdateGameSet();

        $prev_livegameid = GetLiveGameID($db);

        if( $tempRes['status'] ){
            $result['status'] = $tempRes['status'];
            if($prev_livegameid != $gameset_id){
                if($gameset_status==2){
                    SetLiveGame($db,$gameset_id);
                }
                if($prev_livegameid > 0){
                    $gameset->SetID($prev_livegameid);
                    $gameset->SetStatus(1);
                    $gameset->UpdateGameSet();
                }
            }else{
                if($gameset_status!=2){
                    SetLiveGame($db,0);
                }
            }
            $result['action'] = 'update';
        }else{
            $result['message'] = 'ERROR: Update Game Set';
        }
        $database->conn->close(); */
    }
    else if( $_POST['gameset_action'] == 'delete') {
        $gameset_id = isset($_POST['gameset_id']) ? $_POST['gameset_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $score = new Score($db);
        $success_delete_score = $score->delete_gameset_related_score($gameset_id);
        $result['status'] = $success_delete_score;

        if( $success_delete_score){
            $livegame = new Live_Game($db);
            $is_live = $livegame->is_gameset_playing($gameset_id);
            if($is_live){
                $result_livegame_query = $livegame->set_live(0);
                if($result_livegame_query['status'] != true){
                    $result['message'] = "ERROR: set GAMEDRAW LIVEGAME";
                }
            }else{
                $result['message'] = "ERROR: no GAMEDRAW LIVEGAME/error";
            }

            $gameset = new GameSet($db);
            $success_delete_gameset = $gameset->id($gameset_id)->delete();
            $result['status'] = $result['status'] && $success_delete_gameset;
            if($result['status']){
                $result['action'] = 'delete';
            }else{
                $result['message'] = "ERROR: delete GAMESET";
            }
        }else{
            $result['message'] = "ERROR: delete GAMESET related SCORE";
        }

        $database->conn->close();

        /*
        * TO-DO: Set Num verif. & valid.
        */
        /* $gameset = new GameSet( $db );
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
            $livegameid = GetLiveGameID($db);
            if($livegameid == $gameset_id){
                SetLiveGame($db,0);
            }
            $result['status'] = $tempRes['status'];
            $result['action'] = 'delete';
        }else{
            $result['message'] = "ERROR: Delete Game Set";
        }
        $database->conn->close(); */

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

// Get Game Set Last Num
/* if (isset( $_GET['GetGameSetLastNum']) && $_GET['GetGameSetLastNum'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gamedraw_id = is_numeric($_GET['GetGameSetLastNum']) ? $_GET['GetGameSetLastNum'] : 0;

    $database = new Database();
    $db = $database->getConnection();

    $gameset = new GameSet($db);
    $gameset->SetGameDrawID($gamedraw_id);
    $res = $gameset->GetLastNum();
    if($res['status']){
        $result['status'] = true;
        $result['has_value'] = $res['has_value'];
        if($res['has_value']){
            $result['next_num'] = $res['last_num'] + 1;
        }
    }

    $database->conn->close();

    echo json_encode($result);
} */

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

// Get Game Set Info
if (isset( $_GET['GetGameSetInfo']) && $_GET['GetGameSetInfo'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $gameset_id = isset($_GET['GetGameSetInfo']) ? $_GET['GetGameSetInfo'] : 0;
    if(is_numeric($gameset_id) > 0){
        $database = new Database();
        $db = $database->getConnection();

        $gameset = new GameSet($db);
        $gameset->SetID( $gameset_id );
        $resGameSet = $gameset->GetGameSetByID();

        if( $resGameSet['status'] ){
            $result['status'] = $resGameSet['status'];
            $result['has_value'] = $resGameSet['has_value'];
            if($result['has_value']){
                $arr_gameset = $resGameSet['gameset'];
                $result['gameset'] = $arr_gameset;

                // Game  Status
                $gameset->SetStatus($arr_gameset['gameset_status']);
                $result['gameset']['gamestatus'] = $gameset->GetGameStatus();

                $gameset->SetGameDrawID($arr_gameset['gamedraw_id']);
                $arr_gamedraw = $gameset->GetGameDraw();
                $result['gameset']['gamedraw'] = $arr_gamedraw;

                $contestant_a_id = $arr_gamedraw['contestant_a_id'];
                $contestant_b_id = $arr_gamedraw['contestant_b_id'];
                $gamemode_id = $arr_gamedraw['gamemode_id'];

                // Contestant
                $gamedraw = new GameDraw($db);
                $gamedraw->SetContestantAID($contestant_a_id);
                $gamedraw->SetContestantBID($contestant_b_id);
                if($gamemode_id==1) { // Beregu
                    $arr_team_a = $gamedraw->GetTeamContestantA();
                    $arr_team_b = $gamedraw->GetTeamContestantB();
                    $result['gameset']['contestant_a'] = $arr_team_a;
                    $result['gameset']['contestant_b'] = $arr_team_b;
                }else if( $gamemode_id ==2 ){ // Individu
                    $contestant_a = $gamedraw->GetPlayerContestantA();
                    $contestant_b = $gamedraw->GetPlayerContestantB();

                    $result['gameset']['contestant_a'] = $contestant_a;
                    $result['gameset']['contestant_b'] = $contestant_b;
                    $result['gameset']['contestant_a']['logo'] = "no-team.png";
                    $result['gameset']['contestant_b']['logo'] = "no-team.png";

                    if($contestant_a['team_id']>0 || $contestant_b['team_id']>0){
                        $team = new Team($db);
                        $result_query = $team->id($contestant_a['team_id'])->get_logo();
                        if($result_query['status']){
                            $result['gameset']['contestant_a']['logo'] = $result_query['logo'];
                        }
                        $result_query = $team->id($contestant_b['team_id'])->get_logo();
                        if($result_query['status']){
                            $result['gameset']['contestant_b']['logo'] = $result_query['logo'];
                        }
                    }
                }else{
                    $result['gamedraw']['contestant_a']['name'] = '-';
                    $result['gamedraw']['contestant_b']['name'] = '-';
                }

                $arr_scores = $gameset->GetScores();
                for( $j=0; $j<sizeof($arr_scores); $j++){
                    $tempScore = $arr_scores[$j];
                    if($contestant_a_id == $tempScore['contestant_id']){
                        $result['gameset']['contestant_a']['score'] = $tempScore;
                        $totalscore = $tempScore['score_1']+$tempScore['score_2']+$tempScore['score_3']+$tempScore['score_4']+$tempScore['score_5']+$tempScore['score_6'];
                        $result['gameset']['contestant_a']['score']['total'] = $totalscore;
                    }
                    else if($contestant_b_id == $tempScore['contestant_id']){
                        $result['gameset']['contestant_b']['score'] = $tempScore;
                        $totalscore = $tempScore['score_1']+$tempScore['score_2']+$tempScore['score_3']+$tempScore['score_4']+$tempScore['score_5']+$tempScore['score_6'];
                        $result['gameset']['contestant_b']['score']['total'] = $totalscore;
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
// if (isset( $_GET['GetGameSet']) && $_GET['GetGameSet'] != '') {
//     $result = array(
//         'status'    => false,
//         'message'   => ''
//     );
//     if( $_GET['GetGameSet'] == 'all') {

//         $database = new Database();
//         $db = $database->getConnection();

//         $gameset = new GameSet($db);
//         $resGameSets = $gameset->GetGameSets();

//         if( $resGameSets['status'] ){
//             $result['status'] = true;
//             $result['has_value'] = $resGameSets['has_value'];
//             $result['gamesets'] = $resGameSets['gamesets'];
//             if($result['has_value']){
//                 for( $i=0; $i<sizeof($resGameSets['gamesets']); $i++){
//                     $gamedraw_id = $resGameSets['gamesets'][$i]['gamedraw_id'];
//                     $gameset_status_id = $resGameSets['gamesets'][$i]['gameset_status'];

//                     $gameset->SetGameDrawID($gamedraw_id);
//                     $gamedraw = $gameset->GetGameDraw();

//                     $result['gamesets'][$i]['gamedraw'] = $gamedraw;
//                     if($gamedraw_id>0){
//                         $gamemode_id = $gamedraw['gamemode_id'];
//                         $contestant_a_id = $gamedraw['contestant_a_id'];
//                         $contestant_b_id = $gamedraw['contestant_b_id'];
//                         // Contestant
//                         $gamedrawCls = new GameDraw($db);
//                         $gamedrawCls->SetContestantAID($contestant_a_id);
//                         $gamedrawCls->SetContestantBID($contestant_b_id);
//                         if($gamemode_id==1) { // Beregu
//                             $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedrawCls->GetTeamContestantA();
//                             $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedrawCls->GetTeamContestantB();
//                         }else if( $gamemode_id ==2 ){ // Individu
//                             $result['gamesets'][$i]['gamedraw']['contestant_a'] = $gamedrawCls->GetPlayerContestantA();
//                             $result['gamesets'][$i]['gamedraw']['contestant_b'] = $gamedrawCls->GetPlayerContestantB();
//                             if($result['gamesets'][$i]['gamedraw']['contestant_a']['team_id']>0){
//                                 $player = new Player($db);
//                                 $player->SetTeamId($result['gamesets'][$i]['gamedraw']['contestant_a']['team_id']);
//                                 $team = $player->GetTeam();
//                                 $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = $team['logo'];
//                             }else{
//                                 $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = "no-team.png";
//                             }
//                             if($result['gamesets'][$i]['gamedraw']['contestant_b']['team_id']>0){
//                                 $player = new Player($db);
//                                 $player->SetTeamId($result['gamesets'][$i]['gamedraw']['contestant_b']['team_id']);
//                                 $team = $player->GetTeam();
//                                 $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = $team['logo'];
//                             }else{
//                                 $result['gamesets'][$i]['gamedraw']['contestant_a']['logo'] = "no-team.png";
//                             }
//                         }
//                     }else{
//                         $result['gamesets'][$i]['gamedraw']['num'] = "0";
//                     }
//                     if($gameset_status_id>0){
//                         $gameset->SetStatus($gameset_status_id);
//                         $result['gamesets'][$i]['gamestatus'] = $gameset->GetGameStatus();
//                     }else{
//                         $result['gamesets'][$i]['gamestatus']['name'] = "-";
//                     }
//                 }
//             }
//         }
//         $database->conn->close();
//     }else{
//         $gameset_id = isset($_GET['GetGameSet']) ? $_GET['GetGameSet'] : 0;
//         if(is_numeric($gameset_id)>0){
//             $database = new Database();
//             $db = $database->getConnection();

//             $gameset = new GameSet($db);
//             $gameset->SetID( $gameset_id );
//             $tempRes = $gameset->GetGameSetByID();

//             if( $tempRes['status'] ){
//                 $result['status'] = $tempRes['status'];
//                 $result['gameset'] = $tempRes['gameset'];
//                 $gamedraw_id = $tempRes['gameset']['gamedraw_id'];
//                 $gameset_status_id = $tempRes['gameset']['gameset_status'];
//                 if($gamedraw_id>0){
//                     $gamedraw = new GameDraw($db);
//                     $gamedraw->SetID($gamedraw_id);
//                     $resGameDraw = $gamedraw->GetGameDrawByID();
//                     if( $resGameDraw['status'] ){
//                         $result['gameset']['gamedraw'] = $resGameDraw['gamedraw'];
//                         $gamemode_id = $resGameDraw['gamedraw']['gamemode_id'];
//                         $contestant_a_id = $resGameDraw['gamedraw']['contestant_a_id'];
//                         $contestant_b_id = $resGameDraw['gamedraw']['contestant_b_id'];
//                         // Contestant
//                         if($contestant_a_id > 0 && $contestant_b_id > 0){
//                             if($gamemode_id==1) { // Beregu
//                                 $team = new Team($db);
//                                 $team->SetID($contestant_a_id);
//                                 $resTeam = $team->GetTeamByID();
//                                 if($resTeam['status']){
//                                     $result['gameset']['gamedraw']['contestant_a'] = $resTeam['team'];
//                                 }else{
//                                     $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
//                                 }
//                                 $team->SetID($contestant_b_id);
//                                 $resTeam = $team->GetTeamByID();
//                                 if($resTeam['status']){
//                                     $result['gameset']['gamedraw']['contestant_b'] = $resTeam['team'];
//                                 }else{
//                                     $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
//                                 }
//                             }else if( $gamemode_id ==2 ){ // Individu
//                                 $player = new Player($db);
//                                 $player->SetID($contestant_a_id);
//                                 $resPlayer = $player->GetPlayerByID();
//                                 if($resPlayer['status']){
//                                     $result['gameset']['gamedraw']['contestant_a'] = $resPlayer['player'];
//                                 }else{
//                                     $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
//                                 }
//                                 $player->SetID($contestant_b_id);
//                                 $resPlayer = $player->GetPlayerByID();
//                                 if($resPlayer['status']){
//                                     $result['gameset']['gamedraw']['contestant_b'] = $resPlayer['player'];
//                                 }else{
//                                     $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
//                                 }
//                             }else{
//                                 $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
//                                 $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
//                             }
//                         }else{
//                             $result['gameset']['gamedraw']['contestant_a']['name'] = '-';
//                             $result['gameset']['gamedraw']['contestant_b']['name'] = '-';
//                         }
//                     }else{
//                         $result['gameset']['gamedraw']['num'] = "0";
//                     }
//                 }else{
//                     $result['gameset']['gamedraw']['num'] = "0";
//                 }
//                 if($gameset_status_id>0){
//                     $gamestatus = new GameStatus($db);
//                     $gamestatus->SetID($gameset_status_id);
//                     $resGameStatus = $gamestatus->GetGameStatusByID();
//                     if( $resGameDraw['status'] ){
//                         $result['gameset']['gamestatus'] = $resGameStatus['gamestatus'];
//                     }else{
//                         $result['gameset']['gamestatus']['name'] = "-";
//                     }
//                 }else{
//                     $result['gameset']['gamestatus']['name'] = "-";
//                 }
//             }else{
//                 $result['message'] = "ERROR: Load Gameset";
//             }
//         }else{
//             $result['message'] = "ERROR: id = 0";
//         }
//     }
//     echo json_encode($result);
// }

?>