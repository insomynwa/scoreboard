<?php

include_once 'config/database.php';
include_once 'objects/team.php';
include_once 'objects/gameset.php';
include_once 'objects/game.php';
include_once 'objects/score.php';
include_once 'objects/player.php';

if( isset( $_GET['game']) && $_GET['game'] != '' && isset( $_GET['gameset']) && $_GET['gameset'] != ''/*  && isset( $_GET['timA']) && isset( $_GET['timB']) && $_GET['timA'] != '' &&$_GET['timB'] != '' */){
    $game = getGame($_GET['game']);/* var_dump($game); */
    $timA = getTeam( $game['tim_a_id']);
    $timB = getTeam( $game['tim_b_id']);
    $gameset = getGameSet( $game['game_id'], $_GET['gameset']);/* var_dump($gameset); */
    $scoreA = getScore($gameset['gameset_id'], $game['tim_a_id']);
    $scoreB = getScore($gameset['gameset_id'], $game['tim_b_id']);

    $match = array(
        'game'      => $game['game_num'],
        'game_id'      => $game['game_id'],
        'set'       => $gameset['gameset_num'],
        'set_id'       => $gameset['gameset_id'],
        'teamA'     => [
            'id'    => $timA['tim_id'],
            'name'  => $timA['tim_name'],
            'logo'  => $timA['tim_logo'],
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
            'id'    => $timB['tim_id'],
            'name'  => $timB['tim_name'],
            'logo'  => $timB['tim_logo'],
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
    if( $_GET['getTeam'] == 'all') {
        $teams = getTeams();
        echo json_encode($teams);
    }
    /* else if( $_GET['getTeam'] == 'a' ) {
        $team = getTeamByCat('a');
        echo json_encode($team);
    } */
}

// Get Teams By id [IMPORTANT EXAMPLE FOR VMIX]
if (isset( $_GET['getTeamById']) && $_GET['getTeamById'] != '') {
    $team = getTeamById($_GET['getTeamById']);
    echo "[". json_encode($team['team']) . "]";
}

// Get Players
if (isset( $_GET['getPlayer']) && $_GET['getPlayer'] != '') {
    if( $_GET['getPlayer'] == 'all') {
        $players = getPlayers();
        for($i=0; $i<sizeof($players['players']); $i++){
            $team = getTeam( $players['players'][$i]['team_id']);
            $players['players'][$i]['team_name'] = $team['tim_name'];
        }
        // $teams = getTeam( $game['tim_a_id']);
        echo json_encode($players);
    }
}

// Get Team Players
if (isset( $_GET['getPlayersByTeam']) && $_GET['getPlayersByTeam'] != '') {
    // if( $_GET['getPlayer'] == 'all') {
        $players = getPlayersByTeam($_GET['getPlayersByTeam']);
        // for($i=0; $i<sizeof($players['players']); $i++){
        //     $team = getTeam( $players['players'][$i]['team_id']);
        //     $players['players'][$i]['team_name'] = $team['tim_name'];
        // }
        // $teams = getTeam( $game['tim_a_id']);
        echo json_encode($players);
    // }
}

// Get Games
if (isset( $_GET['getGames']) && $_GET['getGames'] != '') {
    if( $_GET['getGames'] == 'all') {
        $games = getGames();
        for($i=0; $i<sizeof($games['games']); $i++){
            $games['games'][$i]['teamA_name'] = "";
            $games['games'][$i]['teamB_name'] = "";
            if($games['games'][$i]['teamA']!=0 && $games['games'][$i]['teamB'] != 0 ) {
                $teamA = getTeam( $games['games'][$i]['teamA']);
                $teamB = getTeam( $games['games'][$i]['teamB']);
                $games['games'][$i]['teamA_name'] = strtoupper( substr( $teamA['tim_name'], 0, 3) );
                $games['games'][$i]['teamB_name'] = strtoupper( substr( $teamB['tim_name'], 0, 3) );
            }
            $games['games'][$i]['playerA_name'] = "";
            $games['games'][$i]['playerB_name'] = "";
            if($games['games'][$i]['playerA']!=0 && $games['games'][$i]['playerB'] != 0 ) {
                $games['games'][$i]['playerA_name'] = getPlayerById( $games['games'][$i]['playerA'] )['player']['name'];
                $games['games'][$i]['playerB_name'] = getPlayerById( $games['games'][$i]['playerB'] )['player']['name'];
            }
            if( $games['games'][$i]['status'] == 0 ) {
                $games['games'][$i]['status'] = "Wait";
            }else if( $games['games'][$i]['status'] == 1 ) {
                $games['games'][$i]['status'] = "Live";
            }else if( $games['games'][$i]['status'] == 2 ) {
                $games['games'][$i]['status'] = "Finished";
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
            $teamA = getTeam($teams['teams'][0]);
            $teamB = getTeam($teams['teams'][1]);

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
if ( isset( $_POST['game-action']) ) {
    if( $_POST['game-action'] == 'create') {

        $game_data = array(
            'game_num'  => isset($_POST['game-ke']) ? $_POST['game-ke'] : 0,
            'team_a'    => isset($_POST['game-teamA']) ? $_POST['game-teamA'] : 0,
            'team_b'    => isset($_POST['game-teamB']) ? $_POST['game-teamB'] : 0,
            'player_a'    => isset($_POST['game-playerA']) ? $_POST['game-playerA'] : 0,
            'player_b'    => isset($_POST['game-playerB']) ? $_POST['game-playerB'] : 0,
            'player_b'    => isset($_POST['game-playerB']) ? $_POST['game-playerB'] : 0,
            'teamgame'  => isset($_POST['game-teamgame']) ? $_POST['game-teamgame'] : 0
        );

        $result = createGame($game_data);

        if($result['status']){
            $result['next_gamenum'] = $game_data['game_num'] + 1;
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
if ( isset( $_POST['player-action']) ) {
    if( $_POST['player-action'] == 'create') {

        $player_data = array(
            'player_name'   => isset($_POST['player-name']) ? $_POST['player-name'] : '',
            'team_id'       =>  isset($_POST['player-team']) ? $_POST['player-team'] : 0
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

function getGameSet( $gameid, $gameset_num){

    $database = new Database();
    $db = $database->getConnection();

    $o_gameset = new GameSet($db);
    $gameset = $o_gameset->getGameSet( $gameid, $gameset_num);

    $database->conn->close();
    return $gameset;
}

function getTeam( $teamid){

    $database = new Database();
    $db = $database->getConnection();

    $o_team = new Team($db);
    $team = $o_team->getATeam( $teamid);

    $database->conn->close();
    return $team;
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