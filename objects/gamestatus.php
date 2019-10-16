<?php

class GameStatus{

    private $conn;
    private $table_name = "gamestatus";

    private $id;     //_[int]
    private $name;   //_[string]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    /* public function GetGameStatuses(){
        $res['status'] = false;
        $query = "SELECT * FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $i = 0;
                $gamestatuses = array();
                while($row = $result->fetch_assoc()) {
                    $gamestatuses[$i]['id'] = $row['gamestatus_id'];
                    $gamestatuses[$i]['name'] = $row['gamestatus_name'];

                    $i++;
                }
                $res['gamestatuses'] = $gamestatuses;
            }
        }

        return $res;
    } */

    public function get_gamestatus_list(){
        $res = array( 'status' => false );

        $query =
        "SELECT gamestatus_id, gamestatus_name
        FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows>0;
            $res['gamestatuses'] = array();
            if($res['has_value']){
                $i = 0;
                $gamestatuses = array();
                while($row = $result->fetch_assoc()) {
                    $gamestatuses[$i]['id'] = $row['gamestatus_id'];
                    $gamestatuses[$i]['name'] = $row['gamestatus_name'];

                    $i++;
                }

                $res['gamestatuses'] = $gamestatuses;
            }
        }

        return $res;
    }

    public function GetGameStatusByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gamestatus_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $gamestatus = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamestatus['id'] = $row['gamestatus_id'];
            $gamestatus['name'] = $row['gamestatus_name'];

            $res['status'] = true;
            $res['gamestatus'] = $gamestatus;
        }

        return $res;
    }

    public function CreateDefaultGameStatus(){
        $sql = "INSERT INTO {$this->table_name} (gamestatus_id,gamestatus_name) VALUES ({$this->id}, '{$this->name}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountGameStatus(){
        $sql = "SELECT COUNT(*) as nGameStatus FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameStatus'];
    }

}
?>