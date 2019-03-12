<?php

class GameSet{

    private $conn;
    private $table_name = "gameset";

    public $id;
    public $name;
    public $logo;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function getGameSet( $gameid , $num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE gameset_num = {$num} AND game_id = {$gameid}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getGameSets(){
        $query = "SELECT * FROM " . $this->table_name ;

        $result = $this->conn->query( $query );

        $gameset = null;
        $res = null;

        if( $result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $gameset[$i]['id'] = $row['gameset_id'];
                $gameset[$i]['game_id'] = $row['game_id'];
                $gameset[$i]['num'] = $row['gameset_num'];
                $gameset[$i]['status'] = $row['gameset_status'];
                $i++;
            }

            $res = array(
                'gameset'      => $gameset,
                'status'    => 'true'
            );

            return $res;
        }
        $res = array( 'gameset' => array(), 'status' => false );

        return $res;
    }

    public function createGameset( $gameset_data){
        $sql = "INSERT INTO " . $this->table_name . " (game_id, gameset_num) VALUES ('{$gameset_data['game_id']}', '{$gameset_data['num']}')";

        $res = null;
        if($this->conn->query($sql) === TRUE) {
            $last_id = $this->conn->insert_id;
            $res = array(
                'status'    => true,
                'latest_id' => $last_id
            );

            return $res;
        }
        $res = array( 'status' => false );

        return $res;
    }
}
?>