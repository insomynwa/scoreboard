<?php

class Game{

    private $conn;
    private $table_name = "game";

    public $id;
    public $name;
    public $logo;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function getGame( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE game_num = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
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

    public function createGame( $game_data){
        $sql = "INSERT INTO " . $this->table_name . " (game_num, tim_a_id, tim_b_id, player_a_id, player_b_id, game_teamgame) VALUES ('{$game_data['game_num']}', '{$game_data['team_a']}', '{$game_data['team_b']}', '{$game_data['player_a']}', '{$game_data['player_b']}', '{$game_data['teamgame']}')";

        $res = null;
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );

            return $res;
        }
        $res = array( 'status' => false );

        return $res;
    }

    public function getTeamsByGame($gameid){
        $query = "SELECT * FROM " . $this->table_name ." WHERE game_id={$gameid}";

        $result = $this->conn->query( $query );

        $teams = null;
        $res = null;

        // var_dump($result);
        if( $result->num_rows > 0){
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
        $res = array( 'teams' => '', 'status' => false );

        return $res;
    }
}
?>