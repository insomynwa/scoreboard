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

    public function GetGameStatuses(){
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

}
?>