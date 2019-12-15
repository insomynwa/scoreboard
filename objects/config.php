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

    /**
     * Get Scoreboard Config
     *
     * return [ 'scoreboard_config', 'status' ]
     * @return array
     */
    public function get_scoreboard_config(){
        $res = array( 'status' => false );
        $query =
        "SELECT config_value
        FROM {$this->table_name}
        WHERE config_name = 'scoreboard' LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $config = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['scoreboard_config'] = $row['config_value'];
            $res['status'] = ($result->num_rows > 0) && ($res['scoreboard_config'] != NULL);
        }

        return $res;
    }

    /**
     * Get Scoreboard Form Style Config
     *
     * return string, decode it using json_decode()
     * @return string
     */
    public function get_scoreboard_form_style_config(){
        // $res = array( 'status' => false );

        $config_name = 'form_scoreboard';
        $config_value = '';
        $query = "SELECT config_value FROM {$this->table_name} WHERE config_name = '{$config_name}'";

        if( $result = $this->conn->query( $query ) ){
            $config = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if( $row[ 'config_value' ] != NULL && $row[ 'config_value' ] != '' ) {
                $config_value = $row['config_value'];
            }
            // $res['status'] = ($result->num_rows > 0) && ($res['config_value'] != NULL);
        }

        return $config_value;
    }

    /* public function GetConfigByID(){
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
    } */

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

    /**
     * Create Default Config
     * Scoreboard Form Style, Live Scoreboard Time Interval
     *
     * @param string $config_name
     * @param variant $config_value
     * @return boolean
     */
    public function create_default_config( $config_id, $config_name, $config_value ){
        $sql = "INSERT INTO {$this->table_name} (config_id,config_name,config_value) VALUES ({$config_id},'{$config_name}', '{$config_value}')";

        if($this->conn->query($sql) === TRUE) {

            return true;
        }

        return false;
    }

    public function CountConfig(){
        $sql = "SELECT COUNT(*) as nConfig FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nConfig'];
    }

    /**
     * Check if config is defined
     * > default config
     *
     * @return boolean
     */
    public function is_created() {
        $sql = "SELECT COUNT(*) as nConfig FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        if( $row['nConfig'] == 0 ) {
            return false;
        }
        return true;
    }
}
?>