<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\controller\Controller_Class;
use scoreboard\includes\Tools;
use scoreboard\model\Gamestatus_Model_Class;

class Gamestatus_Controller_Class extends Controller_Class {

    protected $connection;

    private $model;

    private $option_key;
    private $root_key;

    /**
     * Gamestatus Controller Class Construct
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Gamestatus_Model_Class($connection);
        $this->root_key = 'game_status';
        $this->option_key = 'option';
    }

    /**
     * Create Default Gamestatus
     *
     * @return array
     */
    public function create_default() {

        if (!$this->model->has_default()) {
            $this->model->create_default();
        }
        return Tools::render_result($this->root_key, '', '', true);
    }

    /**
     * Get Option Data
     *
     * @return array
     */
    private function get_option_data(){
        return $this->model->option_data();
    }

    /**
     * Get Data
     *
     * @param array $req_data Requested Data
     * @return array
     */
    public function get_data( $req_data=array( 'option')){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        if( empty($req_data) ){
            return $result;
        }

        $data = null;

        if(in_array($this->option_key,$req_data)){
            $data = $this->get_option_data();
            $root_res[$this->option_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
    }
}
?>