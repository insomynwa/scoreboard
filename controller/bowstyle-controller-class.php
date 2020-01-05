<?php
namespace scoreboard\controller;

use scoreboard\model\Bowstyle_Model_Class;

class Bowstyle_Controller_Class extends Controller_Class {

    protected $connection;
    private $model;

    private $radio_key;
    private $option_key;
    private $root_key;

    private $id;

    /**
     * Class Constructor
     *
     * @param object $connection Database Connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Bowstyle_Model_Class($connection);
        $this->root_key = 'bowstyle';
        $this->radio_key = 'radio';
        $this->option_key = 'option';
    }

    /**
     * Create Default Game Mode
     *
     * @return boolean
     */
    public function create_default() {

        $gamemode_om = new Bowstyle_Model_Class($this->connection);
        if (!$gamemode_om->has_default()) {
            $gamemode_om->create_default();
        }
    }

    /**
     * Set ID
     *
     * @param integer $id ID
     * @return void
     */
    public function set_id($id=0){
        $this->id = $id;
    }

    /**
     * Get Option Data
     *
     * @return void
     */
    private function get_option_data(){
        return $this->model->option_data($this->id);
    }

    /**
     * Get Option Data
     *
     * @return void
     */
    private function get_radio_data(){
        return $this->model->radio_data();
    }

    /**
     * Get Data
     *
     * @param array $req_data Requested Data
     * @return array
     */
    public function get_data( $req_data=array( 'option', 'radio')){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        if( empty($req_data) ){
            return $result;
        }

        $data = null;

        if(in_array($this->option_key,$req_data)){
            $data = is_null($data) ? $this->get_option_data() : $data;
            $root_res[$this->option_key] = $data;
        }

        if(in_array($this->radio_key,$req_data)){
            $data = $this->get_radio_data();
            $root_res[$this->radio_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
    }
}
?>