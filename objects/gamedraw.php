<?php

class GameDraw{

    private $conn;
    private $table_name = "gamedraw";

    private $id;            //_[int]
    private $num;           //_[int]
    private $gamemode_id;      //_id_[int]
    private $contestant_id;   //_id_[int]
    private $gamestatus_id;    //_id_[int]
    private $contestant_a_id;   //_id_[int]
    private $contestant_b_id;   //_id_[int]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetNum( $num ){
        $this->num = $num;
    }

    public function SetGameModeID( $gamemode_id ){
        $this->gamemode_id = $gamemode_id;
    }

    public function SetContestantID( $contestant_id ){
        $this->contestant_id = $contestant_id;
    }

    public function SetContestantAID( $contestant_a_id ){
        $this->contestant_a_id = $contestant_a_id;
    }

    public function SetContestantBID( $contestant_b_id ){
        $this->contestant_b_id = $contestant_b_id;
    }

    public function CreateGameDraw(){
        $sql = "INSERT INTO " . $this->table_name . " (gamedraw_num, gamemode_id, contestant_a_id, contestant_b_id) VALUES ('{$this->num}', '{$this->gamemode_id}', '{$this->contestant_a_id}', '{$this->contestant_b_id}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDraws(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamedraws' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamedraws = null;
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];

                $gamemode = new GameMode($this->conn);
                $gamemode->SetID( $row['gamemode_id'] );
                $tempRes = $gamemode->GetGameModeByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamemode'] = $tempRes['gamemode'];
                }

                /*
                * TO-DO: Harus Dinamis
                */
                /* $contestant = new Contestant($this->conn);
                $contestant->SetID( $row['contestant_id'] );
                $contestant->SetGameMode( $row['gamemode_id'] );
                $tempRes = $contestant->GetContestantByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant'] = $tempRes['contestant'];
                } */

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gamestatus_id'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamestatus'] = $tempRes['gamestatus'];
                }

                /*
                * TO-DO: Harus Dinamis
                */
                if( $row['gamemode_id'] == 1 ){ // Beregu

                    $team = new Team($this->conn);
                    $team->SetID( $row['contestant_a_id'] );
                    $tempRes = $team->GetTeamByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_a'] = $tempRes['team'];
                    }

                    $team->SetID( $row['contestant_b_id'] );
                    $tempRes = $team->GetTeamByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_b'] = $tempRes['team'];
                    }
                }else if( $row['gamemode_id'] == 2 ){ // Individu

                    $player = new Player($this->conn);
                    $player->SetID( $row['contestant_a_id'] );
                    $tempRes = $player->GetPlayerByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_a'] = $tempRes['player'];
                    }

                    $player->SetID( $row['contestant_b_id'] );
                    $tempRes = $player->GetPlayerByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_b'] = $tempRes['player'];
                    }
                }

                $i++;
            }

            $res = array(
                'gamedraws'      => $gamedraws,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDrawByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE gamedraw_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'gamedraw' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $gamedraw = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamedraw['id'] = $row['gamedraw_id'];
            $gamedraw['num'] = $row['gamedraw_num'];

            $gamemode = new GameMode($this->conn);
            $gamemode->SetID( $row['gamemode_id'] );
            $tempRes = $gamemode->GetGameModeByID();
            if( $tempRes['status'] ){
                $gamedraw['gamemode'] = $tempRes['gamemode'];
            }

            $gamestatus = new GameStatus($this->conn);
            $gamestatus->SetID( $row['gamestatus_id'] );
            $tempRes = $gamestatus->GetGameStatusByID();
            if( $tempRes['status'] ){
                $gamedraw['gamestatus'] = $tempRes['gamestatus'];
            }

            /*
            * TO-DO: Harus Dinamis
            */
            if( $row['gamemode_id'] == 1 ){ // Beregu

                $team = new Team($this->conn);
                $team->SetID( $row['contestant_a_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_a'] = $tempRes['team'];
                }

                $team->SetID( $row['contestant_b_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_b'] = $tempRes['team'];
                }
            }else if( $row['gamemode_id'] == 2 ){ // Individu

                $player = new Player($this->conn);
                $player->SetID( $row['contestant_a_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_a'] = $tempRes['player'];
                }

                $player->SetID( $row['contestant_b_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_b'] = $tempRes['player'];
                }
            }

            $res = array(
                'gamedraw'  => $gamedraw,
                'status'    => true
            );
        }

        return $res;
    }
/*
    public function getGame( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE gamedraw_num = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getDraws(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $draws;
        $res = null;

        if( $result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $draws[$i]['id'] = $row['gamedraw_id'];
                $draws[$i]['num'] = $row['gamedraw_num'];
                $draws[$i]['teamA'] = $row['tim_a_id'];
                $draws[$i]['teamB'] = $row['tim_b_id'];
                $draws[$i]['playerA'] = $row['player_a_id'];
                $draws[$i]['playerB'] = $row['player_b_id'];
                $draws[$i]['status'] = $row['game_status'];
                $i++;
            }

            $res = array(
                'draws'      => $draws,
                'status'    => true
            );

            return $res;
        }
        $res = array( 'draws' => array(), 'status' => false );

        return $res;
    }

    public function getGames(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $games;
        $res = null;

        if( $result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $games[$i]['id'] = $row['game_id'];
                $games[$i]['num'] = $row['game_num'];
                $games[$i]['teamA'] = $row['tim_a_id'];
                $games[$i]['teamB'] = $row['tim_b_id'];
                $games[$i]['playerA'] = $row['player_a_id'];
                $games[$i]['playerB'] = $row['player_b_id'];
                $games[$i]['status'] = $row['game_status'];
                $i++;
            }

            $res = array(
                'games'      => $games,
                'status'    => true
            );

            return $res;
        }
        $res = array( 'games' => array(), 'status' => false );

        return $res;
    }

    public function countGames(){
        $sql = "SELECT COUNT(*) as nGames FROM " . $this->table_name;

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGames'];
    }

    public function createGame( $game_data){
        $sql = "INSERT INTO " . $this->table_name . " (game_num, tim_a_id, tim_b_id, player_a_id, player_b_id, game_teamgame) VALUES ('{$game_data['num']}', '{$game_data['team_a']}', '{$game_data['team_b']}', '{$game_data['player_a']}', '{$game_data['player_b']}', '{$game_data['teamgame']}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );

            return $res;
        }

        return $res;
    }

    public function getTeamsByGame($gameid){
        $query = "SELECT * FROM " . $this->table_name ." WHERE game_id={$gameid}";

        $result = $this->conn->query( $query );

        $res = array( 'teams' => array(), 'status' => false );

        // var_dump($result);
        if( $result->num_rows > 0){
            $teams = null;
            // $i = 0;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // var_dump($row);die;
            // while($row = $result->fetch_assoc()) {
                $teams[0] = $row['tim_a_id'];
                $teams[1] = $row['tim_b_id'];
                // $i++;
            // }

            $res = array(
                'teams'      => $teams,
                'status'    => 'true'
            );

            return $res;
        }

        return $res;
    }

    public function getContestantByGame($gameid){
        $query = "SELECT * FROM " . $this->table_name ." WHERE game_id={$gameid}";

        $result = $this->conn->query( $query );

        $res = array( 'contestants' => array(), 'status' => false );

        // var_dump($result);
        if( $result->num_rows > 0){
            $contestants = null;
            // $i = 0;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // var_dump($row);die;
            // while($row = $result->fetch_assoc()) {
                $contestants[0] = $row['tim_a_id'];
                $contestants[1] = $row['tim_b_id'];
                // $i++;
            // }

            $res = array(
                'contestants'      => $contestants,
                'status'    => 'true'
            );

            return $res;
        }

        return $res;
    } */
}
?>