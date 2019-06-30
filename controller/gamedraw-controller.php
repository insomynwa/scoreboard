<?php

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

            $gamedraw = new GameDraw($db);
            $gamedraw->SetNum($gamedraw_num);
            $gamedraw->SetBowstyleID($bowstyle_id);
            $gamedraw->SetGameModeID($gamemode_id);
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

            $gamedraw = new GameDraw($db);
            $gamedraw->SetID($gamedraw_id);
            $gamedraw->SetNum($gamenum);
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
        }

        $database->conn->close();
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

                    $bowstyle_id = $gamedraws[$i]['bowstyle_id'];
                    $gamemode_id = $gamedraws[$i]['gamemode_id'];
                    $contestant_a_id = $gamedraws[$i]['contestant_a_id'];
                    $contestant_b_id = $gamedraws[$i]['contestant_b_id'];
                    $gamestatus_id = $gamedraws[$i]['gamestatus_id'];

                    $gamedraw->SetBowstyleID($bowstyle_id);
                    $bowstyle = $gamedraw->GetBowstyle();
                    if($bowstyle['id']>0){
                        $result['gamedraws'][$i]['bowstyle'] = $bowstyle;
                    }else{
                        $result['gamedraws'][$i]['bowstyle']['name'] = "-";
                    }

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

                $bowstyle_id = $tempRes['gamedraw']['bowstyle_id'];
                $gamemode_id = $tempRes['gamedraw']['gamemode_id'];
                $contestant_a_id = $tempRes['gamedraw']['contestant_a_id'];
                $contestant_b_id = $tempRes['gamedraw']['contestant_b_id'];
                $gamestatus_id = $tempRes['gamedraw']['gamestatus_id'];

                // Bowstyle
                if($bowstyle_id>0){
                    $bowstyle = new Bowstyle($db);
                    $bowstyle->SetID($bowstyle_id);
                    $resBowstyle = $bowstyle->GetBowstyleByID();
                    if( $resBowstyle['status'] ){
                        $result['gamedraw']['bowstyle'] = $resBowstyle['bowstyle'];
                    }else{
                        $result['gamedraw']['bowstyle']['name'] = "-";
                    }
                }else{
                    $result['gamedraw']['bowstyle']['name'] = "-";
                }
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

                $bowstyle_id = $arr_gamedraw['bowstyle_id'];
                $gamemode_id = $arr_gamedraw['gamemode_id'];
                $contestant_a_id = $arr_gamedraw['contestant_a_id'];
                $contestant_b_id = $arr_gamedraw['contestant_b_id'];
                $gamestatus_id = $arr_gamedraw['gamestatus_id'];

                // Bowstyle
                $gamedraw->SetBowstyleID( $bowstyle_id );
                $resBowstyle = $gamedraw->GetBowstyle();
                $result['gamedraw']['bowstyle'] = $resBowstyle;
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

if (isset( $_GET['GetGameDrawNum']) && $_GET['GetGameDrawNum'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    $database = new Database();
    $db = $database->getConnection();

    $gameset = new GameDraw($db);
    $res = $gameset->CountGameDraw();
    if($res['status']){
        $result['status'] = true;
        $result['nGameDraw'] = $res['count'] + 1;
    }

    $database->conn->close();

    echo json_encode($result);
}
?>