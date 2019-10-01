<?php

class Config{

    private $conn;
    private $table_name = "config";

    private $id;     //_[int]
    private $name;   //_[string]
    private $value;   //_[json_encode]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetValue( $value ){
        $this->value = $value;
    }

    public function GetConfigs(){
        $query = "SELECT config_id, config_name, config_value FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'configs' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $configs = null;
            while($row = $result->fetch_assoc()) {
                $configs[$i]['id'] = $row['config_id'];
                $configs[$i]['name'] = $row['config_name'];
                $configs[$i]['value'] = $row['config_value'];

                $i++;
            }

            $res = array(
                'configs'      => $configs,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetConfigByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE config_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $config = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $config['id'] = $row['config_id'];
            $config['name'] = $row['config_name'];
            $config['value'] = $row['config_value'];

            $res['status'] = true;
            $res['config'] = $config;
        }

        return $res;
    }

    public function CreateDefaultConfig(){
        $sql = "INSERT INTO {$this->table_name} (config_id,config_name,config_value) VALUES ({$this->id}, '{$this->name}', '{$this->value}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountConfig(){
        $sql = "SELECT COUNT(*) as nConfig FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nConfig'];
    }
}
?>