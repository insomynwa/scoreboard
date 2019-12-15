<?php

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
            /* $gamesetCls = new GameSet($db);
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
            } */
            $result['status'] = true;
        }else{
            $result['message'] = "ERROR: Update Score";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

if ( isset( $_GET[ 'score_get' ] ) && $_GET[ 'score_get' ] != '' ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET[ 'score_get' ] == 'live' ){
        $database = new Database();
        $db = $database->getConnection();

        $obj_livegame = new Live_Game($db);
        $response = $obj_livegame->get_data();

        $live_gameset_id = 0;
        $live_style_id = 0;
        $live_game_bowstyle_id = 0;
        $live_style_bowstyle_id = 0;
        $live_style_bowstyle_name = '';
        $live_style = '';

        if ( $response['status'] ) {
            $live_gameset_id = $response[ 'gameset_id' ];
            $live_style_id = $response[ 'scoreboard_style_id' ];
            $live_game_bowstyle_id = is_null($response[ 'game_bowstyle_id' ]) ? 0 : $response[ 'game_bowstyle_id' ];
            $live_style_bowstyle_id = is_null($response[ 'style_bowstyle_id' ]) ? 0 : $response[ 'style_bowstyle_id' ];
            $live_style_bowstyle_name = is_null($response[ 'style_bowstyle_name' ]) ? '' : $response[ 'style_bowstyle_name' ];
            $live_style = is_null($response[ 'style' ]) ? '' : $response[ 'style' ];
        }
        $has_live_game = $live_gameset_id > 0;
        $has_live_style = $live_style_id > 0;

        $obj_config = new Config($db);
        $config_data = array();

        $obj_score = new Score($db);
        $response = $obj_score->get_form_live();

        $form_scoreboard = '<h4 class="text-gray-4 text-center font-weight-light">Start Game</h4>';
        $livegame_data = array();
        if( $response[ 'status' ] ){
            $contestant = null;
            if( $response[ 'scores' ][ 'gamemode_id' ] == 1 ) {
                $contestant = new Team($db);
            }else if ( $response[ 'scores' ][ 'gamemode_id' ] == 2 ) {
                $contestant = new Player($db);
            }

            for ($i=0; $i< sizeof($response[ 'scores' ]['contestants']); $i++) {
                $res_con = $contestant->get_live( $response[ 'scores' ]['contestants'][$i]['id'] );
                $response[ 'scores' ]['contestants'][$i]['logo'] = 'uploads/' . $res_con[ 'logo' ];
                $response[ 'scores' ]['contestants'][$i]['team'] = $res_con[ 'team' ];
                $response[ 'scores' ]['contestants'][$i]['player'] = $res_con[ 'player' ];
            }

            $livegame_data['gamemode_id'] = $response[ 'scores' ][ 'gamemode_id' ];
            $livegame_data['sets'] = $response[ 'scores' ][ 'sets' ];
            $livegame_data['contestants'] = $response[ 'scores' ][ 'contestants' ];
            $livegame_data[ 'bowstyle_id' ] = $live_game_bowstyle_id;
            $livegame_data[ 'style_config' ] = json_decode( $obj_config->get_scoreboard_form_style_config(), true)[ $live_game_bowstyle_id ];
            $template = TEMPLATE_DIR . 'scoreboard/form.php';
            $form_scoreboard = '';
            $form_scoreboard .= template( $template, $livegame_data);
        }
        $result['form_scoreboard'] = $form_scoreboard;

        if ( isset( $_GET['preview'] ) ) {

            $obj_scoreboard_style = new Scoreboard_Style($db);

            $scoreboard_styles['options']['bowstyle_selected'] = $live_style_bowstyle_id;
            $scoreboard_styles['options']['style'] = '<option value="0">Choose</option>';

            if ( $has_live_style ) {
                $response = $obj_scoreboard_style->get_list_by_bowstyle_id( $live_style_bowstyle_id );
                $renderitem = '<option value="0">Choose</option>';
                if( $response[ 'status' ] ) {
                    $item_template = TEMPLATE_DIR . 'scoreboard/style/option.php';
                    foreach( $response['styles'] as $item){
                        $item['live'] = $live_style_id;
                        $renderitem .= template( $item_template, $item);
                    }
                }
                $scoreboard_styles['options']['style'] = $renderitem;
            }else{
                $scoreboard_styles['options']['bowstyle_selected'] = $live_game_bowstyle_id;
                if( $has_live_game ){
                    $response = $obj_scoreboard_style->get_list_by_bowstyle_id( $live_game_bowstyle_id );
                    $renderitem = '<option value="0">Choose</option>';
                    if( $response[ 'status' ] ) {
                        $item_template = TEMPLATE_DIR . 'scoreboard/style/option.php';
                        foreach( $response['styles'] as $item){
                            $item['live'] = $live_style_id;
                            $renderitem .= template( $item_template, $item);
                        }
                    }
                    $scoreboard_styles['options']['style'] = $renderitem;
                }
            }
            $scoreboard_styles['info']['bowstyle'] = $live_style_bowstyle_name;
            $scoreboard_styles['info']['style'] = $live_style;
            if ( $live_style_id == 0 ) {
                $scoreboard_styles['config']['visibility_class'] = [
                    'activate_btn'      => 'hide',
                    'save_btn'          => 'hide',
                    'cancel_btn'        => 'hide',
                    'new_btn'           => $has_live_game ? '' : 'hide',
                    'edit_btn'          => 'hide',
                    'delete_btn'        => 'hide'
                ];
            } else {
                $scoreboard_styles['config']['visibility_class'] = [
                    'activate_btn'      => '',
                    'save_btn'          => 'hide',
                    'cancel_btn'        => 'hide',
                    'new_btn'           => '',
                    'edit_btn'          => '',
                    'delete_btn'        => ''
                ];
            }

            $response = $obj_scoreboard_style->get_config($live_style_id);
            $scoreboard_styles['preview']['view'] = '';

            if($response['status']){
                $preview_data = array();
                $preview_data['game_data'] = NULL;
                $preview_data['style_config'] = json_decode($response['style_config'],true);//var_dump($style_config);
                if( $has_live_game ){
                    $livegame_data['style_config'] = NULL;
                    $preview_data['game_data'] = $livegame_data;
                }

                $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
                $style_preview = '';
                $style_preview .= template( $item_template, $preview_data);
                $scoreboard_styles['preview']['view'] = $style_preview;
            }else{
                $result['message'] = 'ERROR: get Scoreboard Style ';
            }
            $result['scoreboard_styles'] = $scoreboard_styles;
        }

        $teams = [ 'table' => '', 'options' => '' ];
        if( isset($_GET['team'] ) ){
            $obj_team = new Team($db);
            $response = $obj_team->get_list();
            if( $response['status'] ){
                $option_template = TEMPLATE_DIR . 'team/option.php';
                $item_template = TEMPLATE_DIR . 'team/item.php';
                $render_option = '<option value="0">Choose</option>';
                $render_item = '';
                foreach( $response['teams'] as $item){
                    $render_option .= template( $option_template, $item);
                    $render_item .= template( $item_template, $item);
                }
                $teams['options'] = $render_option;
                $teams['table'] = $render_item;
            }else{
                $result['message'] = "ERROR: status 0";
            }
            $result['teams'] = $teams;
        }

        $players = [ 'table' => '', 'options' => '' ];
        if( isset($_GET['player'] ) ){
            $obj_player = new Player($db);
            $response = $obj_player->get_list();
            if( $response['status'] ){
                $item_template = TEMPLATE_DIR . 'player/item.php';
                $option_template = TEMPLATE_DIR . 'player/option.php';
                $render_item = '';
                $render_option = '<option value="0">Choose</option>';
                foreach( $response['players'] as $item){
                    $render_item .= template( $item_template, $item);
                    $render_option .= template( $option_template, $item);
                }
                $players['options'] = $render_option;
                $players['table'] = $render_item;

            }else{
                $result['message'] = "ERROR: status 0";
            }
            $result['players'] = $players;
        }

        $gamedraws = [ 'table' => '', 'options' => '' ];
        if( isset($_GET['gamedraw'] ) ){
            $obj_gamedraw = new GameDraw($db);
            $response = $obj_gamedraw->get_list();
            if( $response['status'] ){
                $item_template = TEMPLATE_DIR . 'gamedraw/item.php';
                $render_item = '';
                $option_template = TEMPLATE_DIR . 'gamedraw/option.php';
                $render_option = '<option value="0">Choose</option>';
                foreach( $response['gamedraws'] as $item){
                    $render_item .= template( $item_template, $item);
                    $render_option .= template( $option_template, $item);
                }
                $gamedraws['table'] = $render_item;
                $gamedraws['options'] = $render_option;
            }else{
                $result['message'] = "ERROR: status 0";
            }
            $result['gamedraws'] = $gamedraws;
        }

        $gamesets = [ 'table' => '' ];
        if( isset($_GET['gameset'] ) ){
            $obj_gameset = new GameSet($db);
            $response = $obj_gameset->get_list();
            if( $response['status'] ){
                $item_template = TEMPLATE_DIR . 'gameset/item.php';
                $render_item = '';
                foreach( $response['gamesets'] as $item){
                    $render_item .= template( $item_template, $item);
                }
                $gamesets['table'] = $render_item;
            }else{
                $result['message'] = "ERROR: status 0";
            }
            $result['gamesets'] = $gamesets;
        }

        $database->conn->close();

        $result['status'] = true;

    }

    echo json_encode( $result );
}

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

        $livegame = new Live_Game($db);

        $resLiveGame = $livegame->GetLiveGameID();
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

                // Get CONFIG
                $bowstyle_id = $resGameDraw['bowstyle_id'];
                $app_config = new Config($db);
                $resAppCfg = $app_config->GetConfigs();
                if($resAppCfg['status']){
                    foreach ($resAppCfg['configs'] as $key => $val) {
                        if($val['name']=='scoreboard'){
                            $scoreboard_cfg = json_decode($val['value']);
                            $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
                            break;
                        }
                    }
                }

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

                    // Get Total & Set Points
                    $gamedraw->SetID($resGameDraw['id']);
                    $resTotPoints = $gamedraw->GetTotalSetPoints($resGameDraw['contestant_a_id']);
                    $result['score']['score_a']['game_total_points'] = $resTotPoints['game_total_points'];
                    $result['score']['score_a']['game_points'] = $resTotPoints['game_points'];
                    $resTotPoints = $gamedraw->GetTotalSetPoints($resGameDraw['contestant_b_id']);
                    $result['score']['score_b']['game_total_points'] = $resTotPoints['game_total_points'];
                    $result['score']['score_b']['game_points'] = $resTotPoints['game_points'];

                    $result['status'] = true;
                    // $result['score']['gameset'] = $resGameSet['gameset'];
                }else{
                    $result['message'] = "ERROR: 0 Game Draw";
                }
            }else{
                $bowstyle_id = 0;
                $app_config = new Config($db);
                $resAppCfg = $app_config->GetConfigs();
                if($resAppCfg['status']){
                    foreach ($resAppCfg['configs'] as $key => $val) {
                        if($val['name']=='scoreboard'){
                            $scoreboard_cfg = json_decode($val['value']);
                            $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
                            break;
                        }
                    }
                }
                $result['has_value'] = false;
            }
        }else{
            $result['message'] = "ERROR: Load Game Set";
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

            $vmixlive = new Live_Game($db);
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
?>