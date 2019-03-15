<?php

class Player{

    private $conn;
    private $table_name = "player";

    public $id;     //_[int]
    public $name;   //_[string]
    public $team_id;   //_id[int]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetTeamId( $team_id ){
        $this->team_id = $team_id;
    }

    public function CreatePlayer(){
        $sql = "INSERT INTO " . $this->table_name . " (player_name, team_id) VALUES ('{$this->name}', '{$this->team_id}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function GetPlayers(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'players' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $i = 0;
            $players = null;
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                $players[$i]['name'] = $row['player_name'];

                $team = new Team($this->conn);
                $team->SetID( $row['team_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $players[$i]['team'] = $tempRes['team'];
                }

                $i++;
            }

            $res = array(
                'players'      => $players,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetPlayerByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE player_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'player' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $player = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $player['id'] = $row['player_id'];
            $player['name'] = $row['player_name'];

            $team = new Team($this->conn);
            $team->SetID( $row['team_id'] );
            $tempRes = $team->GetTeamByID();
            if( $tempRes['status'] ){
                $player['team'] = $tempRes['team'];
            }

            $res = array(
                'player'      => $player,
                'status'    => true
            );
        }

        return $res;
    }
/*
    public function getAPlayer( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE player_id = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getPlayers(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'players' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $i = 0;
            $players = null;
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
        }

        return $res;
    }

    public function countPlayers(){
        $sql = "SELECT COUNT(*) as nPlayers FROM " . $this->table_name;

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nPlayers'];
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

        $res = array( 'players' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $players = null;
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
        }

        return $res;
    }

    public function createPlayer( $player_data){
        $sql = "INSERT INTO " . $this->table_name . " (player_name, team_id) VALUES ('{$player_data['player_name']}', '{$player_data['team_id']}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }
 */
}
?>