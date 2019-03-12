<?php

class Player{

    private $conn;
    private $table_name = "player";

    public $id;
    public $name;
    public $team;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function getAPlayer( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE player_id = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getPlayers(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $players = null;
        $res = null;

        if( $result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                $players[$i]['team_id'] = $row['team_id'];
                $players[$i]['name'] = $row['player_name'];
                $i++;
            }

            $res = array(
                'players'      => $players,
                'status'    => true
            );

            return $res;
        }
        $res = array( 'players' => array(), 'status' => false );

        return $res;
    }

    public function getPlayerById($playerid){
        $query = "SELECT * FROM " . $this->table_name . " WHERE player_id={$playerid}";

        $result = $this->conn->query( $query );

        $player = null;
        $res = null;

        // var_dump($result);
        if( $result->num_rows > 0){
            // $i = 0;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // var_dump($row);die;
            // while($row = $result->fetch_assoc()) {
            $player = array(
                'id'    => $row['player_id'],
                'name'    => $row['player_name'],
            );
                // $i++;
            // }

            $res = array(
                'player'      => $player,
                'status'    => 'true'
            );

            return $res;
        }
        $res = array( 'player' => array(), 'status' => false );

        return $res;
    }

    public function getPlayersByTeam($team_id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE team_id={$team_id}";

        $result = $this->conn->query( $query );

        $players = null;
        $res = null;

        if( $result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                // $players[$i]['team_id'] = $row['team_id'];
                $players[$i]['name'] = $row['player_name'];
                $i++;
            }

            $res = array(
                'players'      => $players,
                'status'    => true
            );

            return $res;
        }
        $res = array( 'players' => array(), 'status' => false );

        return $res;
    }

    public function createPlayer( $player_data){
        $sql = "INSERT INTO " . $this->table_name . " (player_name, team_id) VALUES ('{$player_data['player_name']}', '{$player_data['team_id']}')";

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
}
?>