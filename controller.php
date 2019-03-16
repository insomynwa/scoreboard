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

// Get Game Draws
if (isset( $_GET['getGameDraw']) && $_GET['getGameDraw'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getGameDraw'] == 'all') {

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

        /* $draws = array(
            'count'     => 0,
            'status'    => false
        );
        $nDraws = countGames();
        if( $nDraws > 0 ){
            $draws['count'] = $nDraws;
            $draws['status'] = true;

            $draws = getGameDraws();
            if( $draws['status'] ){
                for($i=0; $i<sizeof($draws['draws']); $i++){

                    $draws['draws'][$i]['teamA_name'] = "";
                    $draws['draws'][$i]['teamB_name'] = "";
                    $draws['draws'][$i]['playerA_name'] = "";
                    $draws['draws'][$i]['playerB_name'] = "";

                    $teamAId = $draws['draws'][$i]['teamA'];
                    $teamBId = $draws['draws'][$i]['teamB'];
                    $playerAId = $draws['draws'][$i]['playerA'];
                    $playerBId = $draws['draws'][$i]['playerB'];

                    if( $teamAId > 0 ){
                        $teamA = getTeamById( $draws['draws'][$i]['teamA']);
                        if( $playerAId > 0 ){
                            $playerA = getPlayerById( $draws['draws'][$i]['playerA'] );
                            $draws['draws'][$i]['teamA_name'] = "(" . strtoupper( substr( $teamA['team']['name'], 0, 3) ) . ")";
                            $draws['draws'][$i]['playerA_name'] = $playerA['player']['name'];
                        }else{
                            $draws['draws'][$i]['teamA_name'] = $teamA['team']['name'];
                        }
                    }else{
                        if( $playerAId > 0 ){
                            $draws['draws'][$i]['playerA_name'] = $playerA['player']['name'];
                        }else{
                            $draws['draws'][$i]['teamA_name'] = "Unknown";
                            $draws['draws'][$i]['playerA_name'] = "Unknown";
                        }
                    }

                    if( $teamBId > 0 ){
                        $teamB = getTeamById( $draws['draws'][$i]['teamB']);
                        if( $playerBId > 0 ){
                            $playerB = getPlayerById( $draws['draws'][$i]['playerB'] );
                            $draws['draws'][$i]['teamB_name'] = "(" . strtoupper( substr( $teamB['team']['name'], 0, 3) ) . ")";
                            $draws['draws'][$i]['playerB_name'] = $playerB['player']['name'];
                        }else{
                            $draws['draws'][$i]['teamB_name'] = $teamB['team']['name'];
                        }
                    }else{
                        if( $playerBId > 0 ){
                            $draws['draws'][$i]['playerB_name'] = $playerB['player']['name'];
                        }else{
                            $draws['draws'][$i]['teamB_name'] = "Unknown";
                            $draws['draws'][$i]['playerB_name'] = "Unknown";
                        }
                    }

                    if( $draws['draws'][$i]['status'] == 0 ) {
                        $draws['draws'][$i]['status'] = "Wait";
                    }else if( $draws['draws'][$i]['status'] == 1 ) {
                        $draws['draws'][$i]['status'] = "Live";
                    }else if( $draws['draws'][$i]['status'] == 2 ) {
                        $draws['draws'][$i]['status'] = "Finished";
                    }
                }
            }
        } */

    }
    echo json_encode($result);
}

// Create Scoreboard
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

        /* $result = array( 'status_init' => false );
        $nGames = countGames();
        $scoreboardData = array(
            'set_num'  => isset($_POST['scoreboard_set']) ? $_POST['scoreboard_set'] : 0,
            'gamedraw_id'    => isset($_POST['scoreboard_team_a']) ? $_POST['scoreboard_games'] : 0
        );

        $resGameSet = createGameset( $scoreboardData );
        $result['status_gameset'] = $resGameSet['status'];

        if( $result['status_gameset'] ){

            $teams = getTeamsByGame($scoreboardData['gamedraw_id']);
            $resTeams['status_teams'] = sizeof( $teams ) > 0;

            if( $resTeams['status_teams'] ){

                $score_data = array(
                    'gameset_id'    => $resGameSet['latest_id'],
                    'teams'         => $teams
                );
                $c_score = createScore($score_data);

                $result['status'] = $c_score['status'];

            }
        }

        $result = createGame($game_data);

        if($result['status']){
            $result['next_gamenum'] = $game_data['game_num'] + 1;
        } */
    }
    echo json_encode($result);
}

if( isset( $_GET['game']) && $_GET['game'] != '' && isset( $_GET['gameset']) && $_GET['gameset'] != ''/*  && isset( $_GET['timA']) && isset( $_GET['timB']) && $_GET['timA'] != '' &&$_GET['timB'] != '' */){
    $game = getGame($_GET['game']);/* var_dump($game); */
    $timA = getTeamById( $game['tim_a_id']);
    $timB = getTeamById( $game['tim_b_id']);
    $gameset = getGameSet( $game['game_id'], $_GET['gameset']);/* var_dump($gameset); */
    $scoreA = getScore($gameset['gameset_id'], $game['tim_a_id']);
    $scoreB = getScore($gameset['gameset_id'], $game['tim_b_id']);

    $match = array(
        'game'      => $game['game_num'],
        'game_id'      => $game['game_id'],
        'set'       => $gameset['gameset_num'],
        'set_id'       => $gameset['gameset_id'],
        'teamA'     => [
            'id'    => $timA['team']['id'],
            'name'  => $timA['team']['name'],
            'logo'  => $timA['team']['logo'],
            'time'  => $scoreA['timer'],
            'points'    => [
                'score_id'  => $scoreA['score_id'],
                'pt1' => $scoreA['score_1'],
                'pt2' => $scoreA['score_2'],
                'pt3' => $scoreA['score_3'],
                'pt4' => $scoreA['score_4'],
                'pt5' => $scoreA['score_5'],
                'pt6' => $scoreA['score_6'],
            ],
            /* 'total_pts' => $scoreA['score_1'] + $scoreA['score_2'] + $scoreA['score_3'] + $scoreA['score_4'] + $scoreA['score_5'] + $scoreA['score_6'], */
            'set_pts'  => $scoreA['set_points'],
            'pts_stat'  => $scoreA['score_status'],
        ],
        'teamB'     => [
            'id'    => $timB['team']['id'],
            'name'  => $timB['team']['name'],
            'logo'  => $timB['team']['logo'],
            'time'  => $scoreB['timer'],
            'points'    => [
                'score_id'  => $scoreB['score_id'],
                'pt1' => $scoreB['score_1'],
                'pt2' => $scoreB['score_2'],
                'pt3' => $scoreB['score_3'],
                'pt4' => $scoreB['score_4'],
                'pt5' => $scoreB['score_5'],
                'pt6' => $scoreB['score_6'],
            ],
            /* 'total_pts' => $scoreA['score_1'] + $scoreA['score_2'] + $scoreA['score_3'] + $scoreA['score_4'] + $scoreA['score_5'] + $scoreA['score_6'], */
            'set_pts'  => $scoreB['set_points'],
            'pts_stat'  => $scoreB['score_status'],
        ]
    );
    $match['teamA']['total_pts'] = array_sum($match['teamA']['points']);
    $match['teamB']['total_pts'] = array_sum($match['teamB']['points']);
    //$result = $_GET['game']();
    // echo $gameset;
    echo json_encode($match);
}

// Get Teams
if (isset( $_GET['getTeam']) && $_GET['getTeam'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getTeam'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $teams = new Team($db);
        $tempRes = $teams->GetTeams();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['teams'] = $tempRes['teams'];
        }

        /* $count = countTeams();
        $result['count'] = $count;
        if( $count > 0){
            $result['status'] = true;
            $teams = getTeams();
            if( $teams['status'] ){
                $result['status'] = $teams['status'];
                $result['teams'] = $teams['teams'];
            }
        } */
    }
    echo json_encode($result);
}

// Get Game Mode
if (isset( $_GET['getGameMode']) && $_GET['getGameMode'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['getGameMode'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gamemode = new GameMode($db);
        $tempRes = $gamemode->GetGameModes();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['gamemodes'] = $tempRes['gamemodes'];
        }

        /* $count = countTeams();
        $result['count'] = $count;
        if( $count > 0){
            $result['status'] = true;
            $teams = getTeams();
            if( $teams['status'] ){
                $result['status'] = $teams['status'];
                $result['teams'] = $teams['teams'];
            }
        } */
    }
    echo json_encode($result);
}

// Get Teams By id [IMPORTANT EXAMPLE FOR VMIX]
if (isset( $_GET['getTeamById']) && $_GET['getTeamById'] != '') {
    $team = getTeamById($_GET['getTeamById']);
    echo "[". json_encode($team['team']) . "]";
}

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

// Get Gameset
if (isset( $_GET['getGameset']) && $_GET['getGameset'] != '') {

    if( $_GET['getGameset'] == 'all') {

        $gameset = getGameSets();

        for($i=0; $i<sizeof($gameset['gameset']); $i++){

            $teams = getTeamsByGame($gameset['gameset'][$i]['game_id']);
            $teamA = getTeamById($teams['teams'][0]);
            $teamB = getTeamById($teams['teams'][1]);

            $gameset['gameset'][$i]['game'] = "{$teamA['tim_name']} vs {$teamB['tim_name']}";
            if($gameset['gameset'][$i]['status']==0){
                $gameset['gameset'][$i]['status_desc'] = "Belum mulai";
            }
            else if($gameset['gameset'][$i]['status']==1){
                $gameset['gameset'][$i]['status_desc'] = "Playing";
            }
            else if($gameset['gameset'][$i]['status']==2){
                $gameset['gameset'][$i]['status_desc'] = "Selesai";
            }
        }

        echo json_encode($gameset);
    }
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

            /* $contestant = new Contestant($db);
            $contestant->SetContestantAID($contestant_a_id);
            $contestant->SetContestantBID($contestant_b_id);
            $tempRes = $contestant->CreateContestant();

            if( $tempRes['status'] ){

                $game_data = array(
                    'num'  => isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 0,
                    'contestant_id'    => $tempRes['latest_id'],
                    'gamemode_id'  => $gamemode_id
                ); */

                $gamenum = isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;

                $gamedraw = new GameDraw($db);
                $gamedraw->SetNum($gamenum);
                $gamedraw->SetGameModeID($gamemode_id);
                // $contestant->SetContestantID($game_data['contestant_id']);
                $gamedraw->SetContestantAID($contestant_a_id);
                $gamedraw->SetContestantBID($contestant_b_id);
                $tempRes = $gamedraw->CreateGameDraw();

                if( $tempRes['status'] ){
                    $result['next_num'] = $gamenum + 1;
                    $result['status'] = $tempRes['status'];
                }else{
                    $result['message'] = "ERROR: Create Game Draw";
                }

            /* }else{
                $result['message'] = "ERROR: Create Contestant";
            } */

            $database->conn->close();

        }else{
            $result['message'] = "ERROR: Contestant 0";
        }
    }
    echo json_encode($result);
}

// Create Gameset
/* if ( isset( $_POST['gameset-action']) ) {
    if( $_POST['gameset-action'] == 'create') {

        $gameset_data = array(
            'num'  => isset($_POST['gameset-set']) ? $_POST['gameset-set'] : 0,
            'game_id'    => isset($_POST['gameset-games']) ? $_POST['gameset-games'] : 0
        );

        $result = createGameset($gameset_data);

        if( $result['status'] ){
            $teams = getTeamsByGame($gameset_data['game_id']);

            $score_data = array(
                'gameset_id'    => $result['latest_id'],
                'teams'         => $teams
            );
            $c_score = createScore($score_data);

            $result['status'] = $c_score['status'];
        }

        echo json_encode($result);
    }
} */

// Get Players
if (isset( $_GET['getPlayer']) && $_GET['getPlayer'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ""
    );
    if( $_GET['getPlayer'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $players = new Player($db);
        $tempRes = $players->GetPlayers();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['players'] = $tempRes['players'];
            $result['message'] = "success";
        }else{
            $result['message'] = "ERROR: load players";
        }

        /* $count = countPlayers();
        $result['count'] = $count;
        if( $count > 0){
            $result['status'] = true;
            $players = getPlayers();
            if( $players['status'] ){
                $result['status'] = $players['status'];
                for($i=0; $i<sizeof($players['players']); $i++){
                    $teamname = "";
                    if( $players['players'][$i]['team_id'] > 0 ){
                        $team = getTeamById( $players['players'][$i]['team_id']);

                        if( ! $team['status'] ){
                            $result['status'] = $team['status'];
                            break;
                        }
                        $teamname = $team['team']['name'];
                    }
                    $players['players'][$i]['team_name'] = $teamname;
                }
                $result['players'] = $players['players'];
            }
        } */
        // $teams = getTeamById( $game['tim_a_id']);
        // echo json_encode($players);
    }
    echo json_encode($result);
}

// Create Player
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
        }else{
            $database = new Database();
            $db = $database->getConnection();

            $player = new Player($db);
            $player->SetName( $playerData['name'] );
            $player->SetTeamId( $playerData['team_id'] );
            $tempRes = $player->CreatePlayer();
            if( $tempRes['status'] ){
                $result['status'] = $tempRes['status'];
            }

            $database->conn->close();
        }

        /* $result = createPlayer($player_data); */

    }
    echo json_encode($result);
}

// Update Score
if ( isset( $_POST['score-action']) ) {
    if( $_POST['score-action'] == 'updateA') {

        $score_data = array(
            'scoreid'  => isset($_POST['sb-teamA-scoreid']) ? $_POST['sb-teamA-scoreid'] : 0,
            'timer'  =>  isset($_POST['scoreA-timer']) ? $_POST['scoreA-timer'] : 0,
            'pts1'  =>  isset($_POST['scoreA-pt1']) ? $_POST['scoreA-pt1'] : 0,
            'pts2'  =>  isset($_POST['scoreA-pt2']) ? $_POST['scoreA-pt2'] : 0,
            'pts3'  =>  isset($_POST['scoreA-pt3']) ? $_POST['scoreA-pt3'] : 0,
            'pts4'  =>  isset($_POST['scoreA-pt4']) ? $_POST['scoreA-pt4'] : 0,
            'pts5'  =>  isset($_POST['scoreA-pt5']) ? $_POST['scoreA-pt5'] : 0,
            'pts6'  =>  isset($_POST['scoreA-pt6']) ? $_POST['scoreA-pt6'] : 0,
            'setpts'  =>  isset($_POST['scoreA-setpts']) ? $_POST['scoreA-setpts'] : 0,
            'status'  =>  isset($_POST['scoreA-status']) ? $_POST['scoreA-status'] : 0,
        );

        $database = new Database();
        $db = $database->getConnection();

        $o_score = new Score($db);
        $score = $o_score->updateScore($score_data);//var_dump($score);

        $database->conn->close();
        return $score;
    }
    else if( $_POST['score-action'] == 'updateB') {

        $score_data = array(
            'scoreid'  => isset($_POST['sb-teamB-scoreid']) ? $_POST['sb-teamB-scoreid'] : 0,
            'timer'  =>  isset($_POST['scoreB-timer']) ? $_POST['scoreB-timer'] : 0,
            'pts1'  =>  isset($_POST['scoreB-pt1']) ? $_POST['scoreB-pt1'] : 0,
            'pts2'  =>  isset($_POST['scoreB-pt2']) ? $_POST['scoreB-pt2'] : 0,
            'pts3'  =>  isset($_POST['scoreB-pt3']) ? $_POST['scoreB-pt3'] : 0,
            'pts4'  =>  isset($_POST['scoreB-pt4']) ? $_POST['scoreB-pt4'] : 0,
            'pts5'  =>  isset($_POST['scoreB-pt5']) ? $_POST['scoreB-pt5'] : 0,
            'pts6'  =>  isset($_POST['scoreB-pt6']) ? $_POST['scoreB-pt6'] : 0,
            'setpts'  =>  isset($_POST['scoreB-setpts']) ? $_POST['scoreB-setpts'] : 0,
            'status'  =>  isset($_POST['scoreB-status']) ? $_POST['scoreB-status'] : 0,
        );

        $database = new Database();
        $db = $database->getConnection();

        $o_score = new Score($db);
        $score = $o_score->updateScore($score_data);//var_dump($score);

        $database->conn->close();
        return $score;
    }
}

function getGameDraws(){
    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $game = $o_game->getDraws();

    $database->conn->close();
    return $game;
}

function getScoreboard(){
    $timA = array(
        'logo' => "",
        'name' => "",
        'timer' => 0,
        'point1'    => 0,
        'point2'    => 0,
        'point3'    => 0,
        'point4'    => 0,
        'point5'    => 0,
        'point6'    => 0,
        'total'    => 0,
        'set_points'    => 0,
        'score_status'    => 0
    );
    $timB = array(
        'logo' => "",
        'name' => "",
        'timer' => 0,
        'point1'    => 0,
        'point2'    => 0,
        'point3'    => 0,
        'point4'    => 0,
        'point5'    => 0,
        'point6'    => 0,
        'total'    => 0,
        'set_points'    => 0,
        'score_status'    => 0
    );

    $scoreboard = array( 'tim_a_score' => $timA, 'tim_b_score' => $timB );
    return $scoreboard;
}

function getGame($game_num){

    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $game = $o_game->getGame($game_num);

    $database->conn->close();
    return $game;
}

function countGames(){

    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $game = $o_game->countGames();

    $database->conn->close();
    return $game;
}

function getGameSet( $gameid, $gameset_num){

    $database = new Database();
    $db = $database->getConnection();

    $o_gameset = new GameSet($db);
    $gameset = $o_gameset->getGameSet( $gameid, $gameset_num);

    $database->conn->close();
    return $gameset;
}

function getTeamById( $teamid ){

    $database = new Database();
    $db = $database->getConnection();

    $o_team = new Team($db);
    $team = $o_team->getTeamById( $teamid);

    $database->conn->close();
    return $team;
}

function getTeams(){

    $database = new Database();
    $db = $database->getConnection();

    $o_teams = new Team($db);
    $teams = $o_teams->getTeams();

    $database->conn->close();
    return $teams;
}

function countTeams(){

    $database = new Database();
    $db = $database->getConnection();

    $o_team = new Team($db);
    $count = $o_team->countTeams();

    $database->conn->close();
    return $count;
}

function countPlayers(){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $count = $o_player->countPlayers();

    $database->conn->close();
    return $count;
}

function getPlayers(){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $players = $o_player->getPlayers();

    $database->conn->close();
    return $players;
}

function getPlayerById($playerid){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $player = $o_player->getPlayerById($playerid);

    $database->conn->close();
    return $player;
}

function getPlayersByTeam($teamid){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $players = $o_player->getPlayersByTeam($teamid);

    $database->conn->close();
    return $players;
}

function createPlayer($playerdata){

    $database = new Database();
    $db = $database->getConnection();

    $o_player = new Player($db);
    $player = $o_player->createPlayer($playerdata);

    $database->conn->close();
    return $player;
}

function getGames(){

    $database = new Database();
    $db = $database->getConnection();

    $o_games = new Game($db);
    $games = $o_games->getGames();

    $database->conn->close();
    return $games;
}

function createGame($gamedata){

    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $game = $o_game->createGame($gamedata);

    $database->conn->close();
    return $game;
}

function getTeamsByGame($gameid){

    $database = new Database();
    $db = $database->getConnection();

    $o_game = new Game($db);
    $teams = $o_game->getTeamsByGame($gameid);

    $database->conn->close();
    return $teams;
}

function getGameSets(){

    $database = new Database();
    $db = $database->getConnection();

    $o_gameset = new GameSet($db);
    $gameset = $o_gameset->getGameSets();

    $database->conn->close();
    return $gameset;
}

function createGameset($gamesetdata){

    $database = new Database();
    $db = $database->getConnection();

    $o_gameset = new GameSet($db);
    $gameset = $o_gameset->createGameset($gamesetdata);

    $database->conn->close();
    return $gameset;
}

function getScore( $gamesetid, $teamid){

    $database = new Database();
    $db = $database->getConnection();

    $o_score = new Score($db);
    $score = $o_score->getScore( $gamesetid, $teamid);

    $database->conn->close();
    return $score;
}

function createScore($scoredata){

    $database = new Database();
    $db = $database->getConnection();

    $o_score = new Score( $db );
    $score = $o_score->createScore( $scoredata );

    $database->conn->close();
    return $score;
}

?>