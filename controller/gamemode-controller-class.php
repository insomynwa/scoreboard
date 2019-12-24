<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gamemode_Model_Class;

class Gamemode_Controller_Class extends Controller_Class {

    protected $connection;

    private $model;

    private $radio_template_name;
    private $radio_template_loc;
    private $radios_json_key;

    private $json_key;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Gamemode_Model_Class($connection);
        $this->json_key = 'game_modes';
        $this->radios_json_key = 'radios';
        $this->init_templates();
    }

    /**
     * Get Elements
     * 'radio'
     *
     * @param array $elements Array Element
     * @return array
     */
    public function get_elements($elements=array()){
        $result = array();
        if(in_array('radio',$elements)){
            $result['game_mode']['radio'] = $this->get_radio('radio')['radio'];
        }
        return $result;
    }

    /**
     * Init Template
     *
     * @return void
     */
    private function init_templates() {
        $this->radio_template_name = 'radio';
        $this->radio_template_loc = TEMPLATE_DIR . "gamemode/{$this->radio_template_name}.php";
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

    /**
     * Get Radio
     *
     * @param integer $selected_item Selected Item. Default: 0
     * @param string $ext_json_key External Invoke
     * @return array
     */
    public function get_radio($radio_key='', $list = null, $selected_item=0){
        $gamemode_list = is_null($list) ? $this->model->get_list() : $list;
        $key = $radio_key != '' ? $radio_key : $this->radios_json_key;
        return Tools::create_loop_element(
            $gamemode_list,
            'gamemodes',
            $key,
            $this->radio_template_loc,
            '',
            $selected_item
        );
    }
}
// Get Game Mode
if (isset($_GET['gamemode_get']) && $_GET['gamemode_get'] != '') {
    $result = array(
        'status' => false,
        'message' => '',
    );
    if ($_GET['gamemode_get'] == 'list') {

        $database = new Database();
        $db = $database->getConnection();

        $gamemode = new Gamemode_Model_Class($db);
        $tempRes = $gamemode->get_gamemode_list();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if ($result['status']) {
            $result['gamemodes'] = $tempRes['gamemodes'];
        }
    }
    echo json_encode($result);
}
?>