<?php

class GameMode{

    private $conn;
    private $table_name = "gamemode";

    private $id;     //_[int]
    private $name;   //_[string]
    private $desc;   //_[string]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function GetGameModes(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamemodes' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamemodes = null;
            while($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $gamemodes[$i]['desc'] = $row['gamemode_desc'];

                $i++;
            }

            $res = array(
                'gamemodes'      => $gamemodes,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameModeByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $gamemode = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamemode['id'] = $row['gamemode_id'];
            $gamemode['name'] = $row['gamemode_name'];
            $gamemode['desc'] = $row['gamemode_desc'];

            $res['status'] = true;
            $res['gamemode'] = $gamemode;
        }

        return $res;
    }
/*
    public function getAPlayer( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE gamemode_id = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getGameModes(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamemodes' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $i = 0;
            $gamemodes = null;
            while($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                $gamemodes[$i]['team_id'] = $row['team_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $i++;
            }

            $res = array(
                'gamemodes'      => $gamemodes,
                'status'    => true
            );
        }

        return $res;
    }

    public function countGameModes(){
        $sql = "SELECT COUNT(*) as nGameModes FROM " . $this->table_name;

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameModes'];
    }

    public function getPlayerById($gamemodeid){
        $query = "SELECT * FROM " . $this->table_name . " WHERE gamemode_id={$gamemodeid}";

        $result = $this->conn->query( $query );

        $gamemode = null;
        $res = null;

        // var_dump($result);
        if( $result->num_rows > 0){
            // $i = 0;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // var_dump($row);die;
            // while($row = $result->fetch_assoc()) {
            $gamemode = array(
                'id'    => $row['gamemode_id'],
                'name'    => $row['gamemode_name'],
            );
                // $i++;
            // }

            $res = array(
                'gamemode'      => $gamemode,
                'status'    => 'true'
            );

            return $res;
        }
        $res = array( 'gamemode' => array(), 'status' => false );

        return $res;
    }

    public function getGameModesByTeam($team_id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE team_id={$team_id}";

        $result = $this->conn->query( $query );

        $res = array( 'gamemodes' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $gamemodes = null;
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                // $gamemodes[$i]['team_id'] = $row['team_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $i++;
            }

            $res = array(
                'gamemodes'      => $gamemodes,
                'status'    => true
            );
        }

        return $res;
    }

    public function createPlayer( $gamemode_data){
        $sql = "INSERT INTO " . $this->table_name . " (gamemode_name, team_id) VALUES ('{$gamemode_data['gamemode_name']}', '{$gamemode_data['team_id']}')";

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