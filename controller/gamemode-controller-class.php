<?php
namespace scoreboard\controller;

use scoreboard\model\Gamemode_Model_Class;

class Gamemode_Controller_Class extends Controller_Class {

    protected $connection;

    private $model;

    private $radio_key;

    private $root_key;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Gamemode_Model_Class($connection);
        $this->root_key = 'game_mode';
        $this->radio_key = 'radio';
    }

    /**
     * Get Radio Data
     *
     * @return array
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
    public function get_data( $req_data=array( 'radio' )){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        if( empty($req_data) ){
            return $result;
        }

        $data = null;

        if(in_array($this->radio_key,$req_data)){
            $data = $this->get_radio_data();
            $root_res[$this->radio_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
    }

    /**
     * Create Default Game Mode
     *
     * @return boolean
     */
    public function create_default() {
        if (!$this->model->has_default()) {
            $default_data = [
                'id' => 1,
                'name' => 'Beregu',
                'desc' => 'team vs team',
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 2,
                'name' => 'Individu',
                'desc' => 'individu vs individu',
            ];
            $this->model->create_default($default_data);
        }
    }
}
?>