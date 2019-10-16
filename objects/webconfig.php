<?php

class Web_Config{

    private $conn;
    private $table_name = "web_config";

    private $id;            //_[int]
    private $time_interval;          //_[string]
    private $active_mode;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function id($id){
        $this->id = $id;

        return $this;
    }

    /**
     * Create Default Config
     *
     * @return boolean
     */
    public function create_default(){
        $sql = "INSERT INTO {$this->table_name} (time_interval,active_mode) VALUES (1000,1)";

        return $this->conn->query($sql);
    }

    /**
     * Count Web Config
     *
     * @return number
     */
    public function count(){
        $sql = "SELECT COUNT(web_config_id) as nConfig FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nConfig'];
    }

    /**
     * Get Web Config
     *
     * return [ 'status', 'config' => ( 'id', 'time_interval', 'active_mode' )]
     * @return array
     */
    public function get(){
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

    /**
     * Set Config Data
     *
     * param [ 'id', 'time_interval', 'active_mode' ]
     * @param array $config_data
     * @return instance
     */
    public function set_data($config_data){
        $data = array(
            'id'            => $config_data['id'] == 0 ? 0 : $config_data['id'],
            'time_interval' => 500,//$config_data['time_interval'] == '' ? 'team name': $config_data['name'],
            'active_mode'   => $config_data['active_mode'] == '' ? 0: $config_data['active_mode']
        );

        $this->id = $data['id'];
        $this->time_interval = $data['time_interval'];
        $this->active_mode = $data['active_mode'];

        return $this;
    }

    /**
     * Update Config
     *
     * @return boolean
     */
    public function update(){
        $sql = "UPDATE {$this->table_name} SET active_mode={$this->active_mode}, time_interval={$this->time_interval} WHERE web_config_id={$this->id}";

        return $this->conn->query($sql);
    }
}
?>