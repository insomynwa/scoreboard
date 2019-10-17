<?php

class VMIX_LIVE{

    private $conn;
    private $table_name = "vmix_live";

    private $id;            //_[int]
    private $gameset_id;          //_[string]
    private $arr_gameset = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetGameSetID($gameset_id){
        $this->gameset_id = $gameset_id;
    }

    public function GetGameSet(){
        $gameset = new GameSet($this->conn);
        $gameset->SetID( $this->gameset_id);
        $res = $gameset->GetGameSetByID();
        if( $res['status'] ){
            $this->arr_gameset = $res['gameset'];
        }
        return $this->arr_gameset;
    }

    /* public function GetLiveGameID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE vmix_live_id=1 LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if(is_numeric($row['vmix_live_id']) && $row['vmix_live_id'] > 0){

                $res['live_game'] = $row['gameset_id'];
            }
        }

        return $res;
    } */

    public function UpdateLiveGame(){
        $query = "UPDATE {$this->table_name} SET gameset_id={$this->gameset_id} WHERE vmix_live_id=1";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    }

    public function StopLiveGame(){
        $query = "UPDATE {$this->table_name} SET gameset_id=0";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    }

    public function CreateDefaultLiveGame(){
        $sql = "INSERT INTO {$this->table_name} (vmix_live_id,gameset_id) VALUES (1,0)";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountLiveGame(){
        $sql = "SELECT COUNT(*) as nLiveGame FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nLiveGame'];
    }
}
?>