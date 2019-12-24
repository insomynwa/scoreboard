<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\controller\Controller_Class;
use scoreboard\includes\Tools;
use scoreboard\model\Gamestatus_Model_Class;

class Gamestatus_Controller_Class extends Controller_Class {

    protected $connection;

    private $model;

    private $option_template_name;
    private $option_template_loc;
    private $options_json_key;

    private $json_key;

    /**
     * Gamestatus Controller Class Construct
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Gamestatus_Model_Class($connection);
        $this->json_key = 'game_statuses';
        $this->options_json_key = 'option';
        $this->init_templates();
    }

    /**
     * Init Template
     *
     * @return void
     */
    private function init_templates() {
        $this->option_template_name = 'option';
        $this->option_template_loc = TEMPLATE_DIR . "gamestatus/{$this->option_template_name}.php";
    }

    /**
     * Get Elements
     * 'option','radio'
     *
     * @param array $elements Array Element
     * @return array
     */
    public function get_elements($elements=array()){
        $result = array();
        if(in_array('option',$elements)){
            $result['game_status']['option'] = $this->get_option('option')['option'];
        }
        return $result;
    }

    /**
     * Create Default Gamestatus
     *
     * @return array
     */
    public function create_default() {

        if (!$this->model->has_default()) {
            $default_data = [
                'id' => 1,
                'name' => 'Stand by',
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 2,
                'name' => 'Live',
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 3,
                'name' => 'Finished',
            ];
            $this->model->create_default($default_data);
        }
        return Tools::render_result($this->json_key, '', '', true);
    }

    /**
     * Get Options
     *
     * @param integer $selected_item Selected Item. Default: 0
     * @param string $ext_json_key External Invoke
     * @return array
     */
    public function get_option($options_key='', $list = null, $selected_item=0){
        $gamestatus_list = is_null($list) ? $this->model->get_list() : $list;
        $key = $options_key != '' ? $options_key : $this->options_json_key;
        return Tools::create_loop_element(
            $gamestatus_list,
            'gamestatuses',
            $key,
            $this->option_template_loc,
            '<option value="0">Choose</option>',
            $selected_item
        );
    }
}
// Get Game Status
if (isset($_GET['gamestatus_get'])) {
    $result = array(
        'status' => false,
        'message' => '',
    );

    if ($_GET['gamestatus_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $gamestatus = new Gamestatus_Model_Class($db);
        $result_array = $gamestatus->get_gamestatus_list();

        $database->conn->close();

        if ($result_array['status']) {
            $result['status'] = true;
            $result['has_value'] = $result_array['has_value'];
            if ($result['has_value']) {
                $item_template = TEMPLATE_DIR . 'gamestatus/option.php';
                $renderitem = '<option value="0">Select a status</option>';
                foreach ($result_array['gamestatuses'] as $item) {
                    $renderitem .= Tools::template($item_template, $item);
                }
                $result['gamestatuses'] = $renderitem;
            } else {
                $renderitem = '<option value="0">Select a status</option>';
                $renderitem .= Tools::template($item_template, NULL);

                $result['gamestatuses'] = $renderitem;
                $result['message'] = "has no value";
            }
        } else {
            $result['message'] = "ERROR: status 0";
        }
    }
    echo json_encode($result);
}
?>