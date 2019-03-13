<?php

include_once 'config/database.php';
include_once 'objects/team.php';
include_once 'objects/gameset.php';
include_once 'objects/game.php';
include_once 'objects/score.php';
include_once 'objects/player.php';



// Get Game Draws
if (isset( $_GET['getGameDraws']) && $_GET['getGameDraws'] != '') {
    if( $_GET['getGameDraws'] == 'all') {
        $draws = array(
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
        }

        echo json_encode($draws);
    }
}

// Create Scoreboard
if ( isset( $_POST['scoreboard_action']) ) {
    if( $_POST['scoreboard_action'] == 'create') {

        $inp_status = false;
        $nGames = countGames();
        $game_data = array(
            'set'  => isset($_POST['scoreboard_set']) ? $_POST['scoreboard_set'] : 0,
            'team_a'    => isset($_POST['scoreboard_team_a']) ? $_POST['scoreboard_team_a'] : 0,
            'team_b'    => isset($_POST['scoreboard_team_b']) ? $_POST['scoreboard_team_b'] : 0,
            'player_a'    => isset($_POST['scoreboard_player_a']) ? $_POST['scoreboard_player_a'] : 0,
            'player_b'    => isset($_POST['scoreboard_player_b']) ? $_POST['scoreboard_player_b'] : 0,
            'game_mode'  => isset($_POST['scoreboard_gamemode']) ? $_POST['scoreboard_gamemode'] : 0
        );

        $result = createGame($game_data);

        if($result['status']){
            $result['next_gamenum'] = $game_data['game_num'] + 1;
        }
        echo json_encode($result);
    }
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
    );
    if( $_GET['getTeam'] == 'all') {
        $count = countTeams();
        $result['count'] = $count;
        if( $count > 0){
            $result['status'] = true;
            $teams = getTeams();
            if( $teams['status'] ){
                $result['status'] = $teams['status'];
                $result['teams'] = $teams['teams'];
            }
        }
    }
    echo json_encode($result);
}

// Get Teams By id [IMPORTANT EXAMPLE FOR VMIX]
if (isset( $_GET['getTeamById']) && $_GET['getTeamById'] != '') {
    $team = getTeamById($_GET['getTeamById']);
    echo "[". json_encode($team['team']) . "]";
}

// Get Players
if (isset( $_GET['getPlayer']) && $_GET['getPlayer'] != '') {
    $result = array(
        'status'    => false,
    );
    if( $_GET['getPlayer'] == 'all') {
        $count = countPlayers();
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
        }
        // $teams = getTeamById( $game['tim_a_id']);
        // echo json_encode($players);
    }
    echo json_encode($result);
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

// Get Games
if (isset( $_GET['getGames']) && $_GET['getGames'] != '') {
    if( $_GET['getGames'] == 'all') {
        $games = getGames();
        if( $games['status'] ){
            for($i=0; $i<sizeof($games['games']); $i++){/* 

                $games['games'][$i]['teamA_name'] = "";
                $games['games'][$i]['teamB_name'] = "";
                $games['games'][$i]['playerA_name'] = "";
                $games['games'][$i]['playerB_name'] = "";

                $teamAId = $games['games'][$i]['teamA'];
                $teamBId = $games['games'][$i]['teamB'];
                $playerAId = $games['games'][$i]['playerA'];
                $playerBId = $games['games'][$i]['playerB'];if($i==3) var_dump($teamAId);die;

                if( $teamAId > 0 ){
                    $teamA = getTeamById( $games['games'][$i]['teamA']);
                    if( $playerAId > 0 ){
                        $playerA = getPlayerById( $games['games'][$i]['playerA'] );
                        $games['games'][$i]['teamA_name'] = "(" . strtoupper( substr( $teamA['team']['name'], 0, 3) ) . ")";
                        $games['games'][$i]['playerA_name'] = $playerA['player']['name'];
                    }else{
                        $games['games'][$i]['teamA_name'] = $teamA['team']['name'];
                    }
                }else{
                    if( $playerAId > 0 ){
                        $games['games'][$i]['playerA_name'] = $playerA['player']['name'];
                    }else{
                        $games['games'][$i]['teamA_name'] = "Unknown";
                        $games['games'][$i]['playerA_name'] = "Unknown";
                    }
                }

                if( $teamBId > 0 ){
                    $teamB = getTeamById( $games['games'][$i]['teamB']);
                    if( $playerBId > 0 ){
                        $playerB = getPlayerById( $games['games'][$i]['playerB'] );
                        $games['games'][$i]['teamB_name'] = "(" . strtoupper( substr( $teamB['team']['name'], 0, 3) ) . ")";
                        $games['games'][$i]['playerB_name'] = $playerB['player']['name'];
                    }else{
                        $games['games'][$i]['teamB_name'] = $teamB['team']['name'];
                    }
                }else{
                    if( $playerBId > 0 ){
                        $games['games'][$i]['playerB_name'] = $playerB['player']['name'];
                    }else{
                        $games['games'][$i]['teamB_name'] = "Unknown";
                        $games['games'][$i]['playerB_name'] = "Unknown";
                    }
                }

                if( $games['games'][$i]['status'] == 0 ) {
                    $games['games'][$i]['status'] = "Wait";
                }else if( $games['games'][$i]['status'] == 1 ) {
                    $games['games'][$i]['status'] = "Live";
                }else if( $games['games'][$i]['status'] == 2 ) {
                    $games['games'][$i]['status'] = "Finished";
                } */
            }
        }

        echo json_encode($games);
    }
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

// Create Game
if ( isset( $_POST['gamedraw_action']) ) {
    if( $_POST['gamedraw_action'] == 'create') {
        $result = array(
            'status'    => false,
        );

        $game_data = array(
            'num'  => isset($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 0,
            'team_a'    => isset($_POST['gamedraw_team_a']) ? $_POST['gamedraw_team_a'] : 0,
            'team_b'    => isset($_POST['gamedraw_team_b']) ? $_POST['gamedraw_team_b'] : 0,
            'player_a'    => isset($_POST['gamedraw_player_a']) ? $_POST['gamedraw_player_a'] : 0,
            'player_b'    => isset($_POST['gamedraw_player_b']) ? $_POST['gamedraw_player_b'] : 0,
            'teamgame'  => isset($_POST['gamedraw_gamemode']) ? $_POST['gamedraw_gamemode'] : 0
        );

        $result = createGame($game_data);

        if($result['status']){
            $result['next_num'] = $game_data['num'] + 1;
        }
        echo json_encode($result);
    }
}

// Create Gameset
if ( isset( $_POST['gameset-action']) ) {
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
}

// Create Player
if ( isset( $_POST['player_action']) ) {
    if( $_POST['player_action'] == 'create') {

        $player_data = array(
            'player_name'   => isset($_POST['player_name']) ? $_POST['player_name'] : '',
            'team_id'       =>  isset($_POST['player_team']) ? $_POST['player_team'] : 0
        );

        $result = createPlayer($player_data);

        echo json_encode($result);
    }
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