<?php

if ( isset( $_GET['gamedraw_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['gamedraw_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $resGamedraws = $gamedraw->get_gamedraw_list();

        $database->conn->close();

        if( $resGamedraws['status'] ){
            $result['status'] = true;
            $result['has_value'] = $resGamedraws['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'gamedraw/item.php';
                $renderitem = '';
                foreach( $resGamedraws['gamedraws'] as $item){
                    $renderitem .= template( $item_template, $item);
                }
                $result['gamedraws'] = $renderitem;

                $item_template = TEMPLATE_DIR . 'gamedraw/option.php';
                $renderitem = '<option value="0">Select a game draw</option>';
                foreach( $resGamedraws['gamedraw_option'] as $item){
                    $renderitem .= template( $item_template, $item);
                }
                $result['gamedraw_option'] = $renderitem;
            }else{
                $item_template = TEMPLATE_DIR . 'gamedraw/no-item.php';
                $renderitem = '';
                $renderitem .= template( $item_template, NULL);

                $render_gamedraw_option = '<option value="0">Select a game draw</option>';

                $result['gamedraw_option'] = $render_gamedraw_option;
                $result['gamedraws'] = $renderitem;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    else if ( $_GET['gamedraw_get'] == 'new_num'){
        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $res = $gamedraw->count_gamedraw();

        $database->conn->close();

        if($res['status']){
            $result['status'] = true;
            $result['new_num'] = $res['count'] + 1;
        }else{
            $result['message'] = 'ERROR: get GAMEDRAW new number';
        }

    }
    else if ( $_GET['gamedraw_get'] == 'single' && isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0) {
        $gamedraw_id = $_GET['id'];

        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $result_array = $gamedraw->id($gamedraw_id)->action('update')->get_this_gamedraw();
        if($result['status'] = $result_array['status']){
            if($result_array['has_value']){
                $result['gamedraw'] = $result_array['gamedraw'];
            }
        }else{
            $result['message'] = 'ERROR: get GAMEDRAW';
        }

        $database->conn->close();
    }else if( $_GET['gamedraw_get'] == 'summary' && isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0){
        $gamedraw_id = $_GET['id'];

        $database = new Database();
        $db = $database->getConnection();

        $gamedraw = new GameDraw($db);
        $result_query = $gamedraw->id($gamedraw_id)->get_summary();
        $database->conn->close();

        $result['status'] = $result_query['status'];
        if($result['status']){
            $item_template = TEMPLATE_DIR . 'gamedraw/summary.php';
            $renderitem = '';
            $renderitem .= template( $item_template, $result_query);
            $result['summaries'] = $renderitem;
        }else{
            $result['message'] = 'ERROR: get GAMEDRAW summaries';
        }
    }
    echo json_encode($result);
}

// Action Game Draw
if ( isset( $_POST['gamedraw_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['gamedraw_action'] == 'create') {

        $contestant_a_id = 0;
        $contestant_b_id = 0;
        $bowstyle_id = isset($_POST['gamedraw_bowstyle']) ? $_POST['gamedraw_bowstyle'] : 0;
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

            $data = array(
                'id'                => 0,
                'num'               => $gamedraw_num,
                'bowstyle_id'       => $bowstyle_id,
                'gamemode_id'       => $gamemode_id,
                'contestant_a_id'   => $contestant_a_id,
                'contestant_b_id'   => $contestant_b_id
            );

            $gamedraw = new GameDraw($db);
            $result_query = $gamedraw->set_data($data)->create();
            $database->conn->close();

            if( $result_query ){
                $result['next_num'] = $gamedraw_num + 1;
                $result['action'] = 'create';
                $result['status'] = $result_query;
            }else{
                $result['message'] = "ERROR: Create GAME DRAW";
            }

        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    else if( $_POST['gamedraw_action'] == 'update') {

        $contestant_a_id = 0;
        $contestant_b_id = 0;
        $bowstyle_id = isset($_POST['gamedraw_bowstyle']) ? $_POST['gamedraw_bowstyle'] : 0;
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

            $data = array(
                'id'                => $gamedraw_id,
                'num'               => $gamenum,
                'bowstyle_id'       => $bowstyle_id,
                'gamemode_id'       => $gamemode_id,
                'contestant_a_id'   => $contestant_a_id,
                'contestant_b_id'   => $contestant_b_id
            );

            $gamedraw = new GameDraw($db);
            // $gamedraw->SetID($gamedraw_id);
            // $gamedraw->SetNum($gamenum);
            // $tempRes = $gamedraw->UpdateGameDraw();
            $result_query = $gamedraw->set_data($data)->update();
            $database->conn->close();

            if( $result_query ){
                $result['action'] = 'update';
                $result['status'] = $result_query;
            }else{
                $result['message'] = "ERROR: Update GAME DRAW";
            }

            // if( $tempRes['status'] ){
            //     $result['action'] = 'update';
            //     $result['status'] = $tempRes['status'];
            // }else{
            //     $result['message'] = "ERROR: Update Game Draw";
            // }


        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    else if( $_POST['gamedraw_action'] == 'delete') {
        $gamedraw_id = isset($_POST['gamedraw_id']) ? $_POST['gamedraw_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $score = new Score($db);
        $success_delete_score = $score->delete_gamedraw_related_score($gamedraw_id);
        $result['status'] = $success_delete_score;

        if( $success_delete_score){

            $livegame = new Live_Game($db);
            $is_live = $livegame->is_gamedraw_playing($gamedraw_id);
            if($is_live){
                $result_livegame_query = $livegame->set_live(0);
                if($result_livegame_query['status'] != true){
                    $result['message'] = "ERROR: set GAMEDRAW LIVEGAME";
                }
            }else{
                $result['message'] = "ERROR: no GAMEDRAW LIVEGAME/error";
            }

            $gameset = new GameSet($db);
            $success_delete_gameset = $gameset->delete_gamedraw_related_gameset($gamedraw_id);
            $result['status'] = $result['status'] && $success_delete_gameset;
            if($success_delete_gameset){
                $gamedraw = new GameDraw($db);
                $success_delete_gamedraw = $gamedraw->id($gamedraw_id)->delete();
                $result['status'] = $result['status'] && $success_delete_gamedraw;
                if($success_delete_gamedraw){
                    $result['action'] = 'delete';
                }else{
                    $result['message'] = "ERROR: Delete Game Draw";
                }
            }else{
                $result['message'] = "ERROR: delete GAMEDRAW related GAMESET";
            }
        }else{
            $result['message'] = "ERROR: delete GAMEDRAW related SCORE";
        }

        $database->conn->close();

        /* $gamedraw = new GameDraw( $db );
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
                $livegameid = GetLiveGameID($db);
                if($livegameid == $gameDrawGameSets[$i]['id']){
                    SetLiveGame($db,0);
                }
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
        } */

    }
    echo json_encode($result);
}

// Get Game Draws Info
/* if (isset( $_GET['GetGameDrawInfo']) && $_GET['GetGameDrawInfo'] != '') {
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

                $bowstyle_id = $arr_gamedraw['bowstyle_id'];
                $gamemode_id = $arr_gamedraw['gamemode_id'];
                $contestant_a_id = $arr_gamedraw['contestant_a_id'];
                $contestant_b_id = $arr_gamedraw['contestant_b_id'];
                $gamestatus_id = $arr_gamedraw['gamestatus_id'];

                // Bowstyle
                $gamedraw->SetBowstyleID( $bowstyle_id );
                $resBowstyle = $gamedraw->GetBowstyle();
                $result['gamedraw']['bowstyle'] = $resBowstyle;

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

                        $result_query = $team->id($contestant_a['team_id'])->get_logo();
                        if($result_query['status']){
                            $result['gamedraw']['contestant_a']['logo'] = $result_query['logo'];
                        }

                        $result_query = $team->id($contestant_b['team_id'])->get_logo();
                        if($result_query['status']){
                            $result['gamedraw']['contestant_b']['logo'] = $result_query['logo'];
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
} */

/* if (isset( $_GET['GetGameDrawNum']) && $_GET['GetGameDrawNum'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    $database = new Database();
    $db = $database->getConnection();

    $gameset = new GameDraw($db);
    $res = $gameset->count_gamedraw();
    if($res['status']){
        $result['status'] = true;
        $result['nGameDraw'] = $res['count'] + 1;
    }

    $database->conn->close();

    echo json_encode($result);
} */
?>