<?php

class GameSet{

    private $conn;
    private $table_name = "gameset";

    private $id;
    private $gamedraw_id;
    private $num;
    private $point;
    private $desc;
    private $status;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetGameDrawID($gamedraw_id){
        $this->gamedraw_id = $gamedraw_id;
    }

    public function SetNum($num){
        $this->num = $num;
    }

    public function SetPoint($point){
        $this->point = $point;
    }

    public function SetDesc($desc){
        $this->desc = $desc;
    }

    public function SetStatus($status){
        $this->status = $status;
    }

    public function CreateSet(){
        $sql = "INSERT INTO " . $this->table_name . " (gamedraw_id, gameset_num) VALUES ('{$this->gamedraw_id}', '{$this->num}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $latest_id = $this->conn->insert_id;

            $res = array(
                'status'    => true,
                'latest_id' => $latest_id
            );
        }

        return $res;
    }

    /* public function UpdateGameSet(){
        $sql = "UPDATE " . $this->table_name . " SET gameset_point={$this->point} WHERE gameset_id={$this->id}";

        $res = array( 'status' => false );//var_dump($this->timer, $this->point, $this->desc, $this->id);
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    } */

    public function GetGameSetsByGameDraw(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE gamedraw_id={$this->gamedraw_id}";

        $result = $this->conn->query( $query );

        $res = array( 'gamesets' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamesets = null;
            while($row = $result->fetch_assoc()) {
                $gamesets[$i]['id'] = $row['gameset_id'];
                $gamesets[$i]['num'] = $row['gameset_num'];
                // $gamesets[$i]['point'] = $row['gameset_point'];
                $gamesets[$i]['desc'] = $row['gameset_desc'];

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gameset_status'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamesets[$i]['gamestatus'] = $tempRes['gamestatus'];
                }

                $i++;
            }

            $res = array(
                'gamesets'      => $gamesets,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameSets(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamesets' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamesets = null;
            while($row = $result->fetch_assoc()) {
                $gamesets[$i]['id'] = $row['gameset_id'];
                $gamesets[$i]['num'] = $row['gameset_num'];
                // $gamesets[$i]['point'] = $row['gameset_point'];
                $gamesets[$i]['desc'] = $row['gameset_desc'];

                $gamedraw = new GameDraw($this->conn);
                $gamedraw->SetID( $row['gamedraw_id'] );
                $tempRes = $gamedraw->GetGameDrawByID();
                if( $tempRes['status'] ){
                    $gamesets[$i]['gamedraw'] = $tempRes['gamedraw'];
                }else{
                    // var_dump($row);
                }

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gameset_status'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamesets[$i]['gamestatus'] = $tempRes['gamestatus'];
                }

                $i++;
            }

            $res = array(
                'gamesets'      => $gamesets,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameSetByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE gameset_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'gameset' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $gameset = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gameset['id'] = $row['gameset_id'];
            $gameset['num'] = $row['gameset_num'];
            // $gameset['point'] = $row['gameset_point'];
            $gameset['desc'] = $row['gameset_desc'];

            $gamedraw = new GameDraw($this->conn);
            $gamedraw->SetID( $row['gamedraw_id'] );
            $tempRes = $gamedraw->GetGameDrawByID();
            if( $tempRes['status'] ){
                $gameset['gamedraw'] = $tempRes['gamedraw'];
            }else{
                // var_dump($row);
            }

            $gamestatus = new GameStatus($this->conn);
            $gamestatus->SetID( $row['gameset_status'] );
            $tempRes = $gamestatus->GetGameStatusByID();
            if( $tempRes['status'] ){
                $gameset['gamestatus'] = $tempRes['gamestatus'];
            }

            $res = array(
                'gameset'      => $gameset,
                'status'    => true
            );
        }

        return $res;
    }

    /* public function getGameSet( $gameid , $num){
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
        $sql = "INSERT INTO " . $this->table_name . " (game_id, gameset_num) VALUES ('{$gameset_data['gameset_id']}', '{$gameset_data['set_num']}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $latest_id = $this->conn->insert_id;

            $res = array(
                'status'    => true,
                'latest_id' => $latest_id
            );

            return $res;
        }

        return $res;
    } */
}
?>