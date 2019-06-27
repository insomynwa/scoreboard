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