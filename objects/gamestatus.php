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
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamestatuses' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamestatuss = null;
            while($row = $result->fetch_assoc()) {
                $gamestatuss[$i]['id'] = $row['gamestatus_id'];
                $gamestatuss[$i]['name'] = $row['gamestatus_name'];
                $gamestatuss[$i]['desc'] = $row['gamestatus_desc'];

                $i++;
            }

            $res = array(
                'gamestatuss'      => $gamestatuss,
                'status'    => true
            );
        }

        return $res;
    } */

    public function GetGameStatusByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE gamestatus_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'gamestatus' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $gamestatus = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamestatus['id'] = $row['gamestatus_id'];
            $gamestatus['name'] = $row['gamestatus_name'];

            $res = array(
                'gamestatus'      => $gamestatus,
                'status'    => true
            );
        }

        return $res;
    }

}
?>