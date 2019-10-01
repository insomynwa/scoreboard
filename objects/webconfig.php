<?php

class Web_Config{

    private $conn;
    private $table_name = "web_config";

    private $id;            //_[int]
    private $time_interval;          //_[string]
    private $active_mode;
    private $arr_gameset = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetTimeInterval($time_interval){
        $this->time_interval = $time_interval;
    }

    public function SetActiveMode($active_mode){
        $this->active_mode = $active_mode;
    }

    public function CreateDefaultConfig(){
        $sql = "INSERT INTO {$this->table_name} (time_interval,active_mode) VALUES (1000,1)";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountRow(){
        $sql = "SELECT COUNT(*) as setting FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['setting'];
    }

    public function GetConfig(){
        $res = array( 'status' => false );
        $query = "SELECT web_config_id, time_interval, active_mode FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $config = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row['web_config_id'] > 0){
                $config['id'] = $row['web_config_id'];
                $config['time_interval'] = $row['time_interval'];
                $config['active_mode'] = $row['active_mode'];

                $res['config'] = $config;
            }
        }

        return $res;
    }

    public function UpdateConfig(){
        $sql = "UPDATE {$this->table_name} SET active_mode={$this->active_mode}, time_interval=500 WHERE web_config_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }
}
?>