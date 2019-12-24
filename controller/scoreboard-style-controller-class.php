<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\model\Scoreboard_Style_Model_Class;
use \scoreboard\includes\Tools;

class Scoreboard_Style_Controller_Class extends Controller_Class {

    protected $connection;

    private $model = null;

    private $json_key;
    private $bowstyle_option_json_key;
    private $info_json_key;
    private $config_json_key;

    private $preview_template_name;
    private $preview_template_loc;
    private $preview_json_key;

    private $checkbox_template_name;
    private $checkbox_template_loc;
    private $checkboxes_json_key;

    private $style_option_template_name;
    private $style_option_template_loc;
    private $style_option_json_key;

    /**
     * Class Constructor
     *
     * @param obj $connection
     * @return void
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Scoreboard_Style_Model_Class($connection);
        $this->init_json_key();
        $this->init_templates();
    }

    /**
     * Init JSON key
     *
     * @return void
     */
    private function init_json_key() {
        $this->json_key = 'scoreboard_styles';
        $this->preview_json_key = 'preview';
        $this->bowstyle_option_json_key = 'bowstyle';
        $this->info_json_key = 'info';
        $this->style_option_json_key = 'option';
        $this->checkboxes_json_key = 'checkbox';
        $this->config_json_key = 'config';
    }

    /**
     * Init Templates
     *
     * @return void
     */
    private function init_templates() {
        $template_location = TEMPLATE_DIR . 'scoreboard/style/';
        $this->preview_template_name = 'preview';
        $this->checkbox_template_name = 'checkbox';
        $this->style_option_template_name = 'option';

        $this->preview_template_loc = $template_location . $this->preview_template_name . '.php';
        $this->checkbox_template_loc = $template_location . $this->checkbox_template_name . '.php';
        $this->style_option_template_loc = $template_location . $this->style_option_template_name . '.php';

    }

    /**
     * Create Default Scoreboard Style
     *
     * @return boolean
     */
    public function create_default() {

        if (!$this->model->has_default()) {

            $default_data = [
                'id' => 1,
                'bowstyle_id' => 1,
                'name' => 'Style 1',
                'config' => Tools::get_default_scoreboard_style_config(1, 1),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 2,
                'bowstyle_id' => 1,
                'name' => 'Style 2',
                'config' => Tools::get_default_scoreboard_style_config(1, 2),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 3,
                'bowstyle_id' => 1,
                'name' => 'Style 3',
                'config' => Tools::get_default_scoreboard_style_config(1, 3),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 4,
                'bowstyle_id' => 2,
                'name' => 'Style 1',
                'config' => Tools::get_default_scoreboard_style_config(2, 1),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 5,
                'bowstyle_id' => 2,
                'name' => 'Style 2',
                'config' => Tools::get_default_scoreboard_style_config(2, 2),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 6,
                'bowstyle_id' => 2,
                'name' => 'Style 3',
                'config' => Tools::get_default_scoreboard_style_config(2, 3),
            ];
            $this->model->create_default($default_data);
        }
    }

    /**
     * Create New
     *
     * @param array $data
     * @param boolean $return_latest_id
     * @return array
     */
    public function create($data=null,$return_latest_id=false) {
        $result = [
            'action'    => 'create',
            'status'    => false
        ];
        if (is_null($data)){
            $result['message'] = 'ERROR: create';
            return $result;
        }

        $action_om = $this->model->insert($data, $return_latest_id);
        $result['status'] = $action_om['status'];
        if($return_latest_id) $result['latest_id'] = $action_om['latest_id'];

        return $result;
    }

    /**
     * Update Data
     *
     * @param integer $style_id
     * @param array $data
     * @return array
     */
    public function update($style_id=0,$data=null){
        $result = [
            'action'    => 'update',
            'status'    => false
        ];
        if (is_null($data)){
            $result['message'] = 'ERROR: update';
            return $result;
        }

        $result['status'] = $this->model->update_data($style_id, $data);

        return $result;
    }

    /**
     * Delete Data
     *
     * @param integer $style_id
     * @return array
     */
    public function delete($style_id=0){
        $result = [
            'action'    => 'delete',
            'status'    => false
        ];
        if ($style_id==0){
            $result['message'] = 'ERROR: delete';
            return $result;
        }

        if($result['status'] = $this->model->delete_data($style_id)){
            $live_game_oc = new Live_Game_Controller_Class($this->connection);
            $live_style_id = $live_game_oc->style_id();
            $result['selected'] = $live_style_id;
            if( $style_id == $live_style_id ){
                $live_game_oc->remove_live_style();
                $result['selected'] = 0;
            }
        }

        return $result;
    }

    /**
     * Get Bowstyle ID
     *
     * @param integer $style_id Style ID
     * @return integer Bowstyle ID
     */
    public function get_bowstyle_id($style_id=0){
        return $this->model->bowstyle_id($style_id);
    }

    /**
     * Create Bowstyle Options
     *
     * @param integer $selected_item Live Style
     * @return array
     */
    private function create_bowstyle_option($selected_item = 0, $value_only = false) {
        $bowstyles_oc = new Bowstyle_Controller_Class($this->connection);
        return $bowstyles_oc->get_elements(['option'],'',$selected_item, $value_only);
    }

    /**
     * Create Style Info
     *
     * @param integer $style_id
     * @return array
     */
    private function create_info($style_id = 0) {
        $bowstyle_name = '';
        $style_name = '';
        if ($style_id > 0) {
            $action_m = $this->model->get_info($style_id);
            $bowstyle_name = $action_m['status'] ? $action_m['bowstyle_name'] : '';
            $style_name = $action_m['status'] ? $action_m['style_name'] : '';
        }
        return Tools::render_result($this->info_json_key, ['bowstyle' => $bowstyle_name, 'style' => $style_name], true)[$this->info_json_key];
    }

    /**
     * Create Config
     *
     * @param integer $style_id
     * @return array
     */
    private function create_config($style_id = 0) {
        $config = [
            'activate_btn' => '',
            'save_btn' => 'hide',
            'cancel_btn' => 'hide',
            'new_btn' => '',
            'edit_btn' => '',
            'delete_btn' => '',
        ];
        if ($style_id == 0) {

            $config = [
                'activate_btn' => 'hide',
                'save_btn' => 'hide',
                'cancel_btn' => 'hide',
                'new_btn' => 'hide',
                'edit_btn' => 'hide',
                'delete_btn' => 'hide',
            ];
        }
        return Tools::render_result($this->config_json_key, ['visibility_class' => $config], true)[$this->config_json_key];
    }

    /**
     * Create Preview Scoreboard
     *
     * @param integer $style_id Style ID
     * @param string $preview_key
     * @return array
     */
    public function create_preview($style_id = 0, $custom_preview_key='', $default=false) {

        $key = $this->preview_json_key;
        if( $custom_preview_key != '') $key = $custom_preview_key;
        $action_m = null;
        if( $default ) {
            $action_m = [ 'status'=>true, 'style_config' => Tools::get_default_style_config()];
        }else{
            $action_m = $this->model->get_config($style_id);
        }
        // $action_m = $this->model->get_config($style_id);
        $style_preview = '';
        if ($action_m['status']) {
            $preview_data = array();
            $preview_data['game_data'] = NULL;
            $preview_data['style_config'] = ! $default ? json_decode($action_m['style_config'], true) : $action_m['style_config']; //var_dump($style_config);

            $live_game_oc = new Live_Game_Controller_Class($this->connection);
            $live_game_bowstyle_id = $live_game_oc->get_game_bowstyle_id();

            if ($live_game_oc->has_live_game()) {
                $score_oc = new Score_Controller_Class($this->connection);
                $live_game_data = $score_oc->get_scoreboard_form_data($live_game_bowstyle_id);
                $preview_data['game_data'] = is_null($live_game_data) ? NULL : $live_game_data;
            }

            $style_preview = Tools::template($this->preview_template_loc, $preview_data);
        }
        return Tools::render_result($key, $style_preview, true)[$key];
    }

    /**
     * Create Checkboxes
     *
     * @param integer $style_id Style ID
     * @param string $checkbox_key
     * @param boolean $default
     * @return array
     */
    public function create_checkboxes($style_id = 0, $checkbox_key='', $default=false){

        $key = $this->checkboxes_json_key;
        if( $checkbox_key != '') $key = $checkbox_key;
        $action_m = null;
        if( $default ) {
            $action_m = [ 'status'=>true, 'style_config' => Tools::get_default_style_config()];
        }else{
            $action_m = $this->model->get_config($style_id);
        }
        // $action_m = $this->model->get_config($style_id);
        $checkboxes = '';
        if ($action_m['status']) {
            $style_config = ! $default ? json_decode($action_m['style_config'], true) : $action_m['style_config'];

            $checkboxes = Tools::template($this->checkbox_template_loc, [ 'config' => $style_config]);
        }
        return Tools::render_result($key, $checkboxes, true)[$key];
    }

    /**
     * Render Element
     *
     * @param string $element_type Element Type
     * @param string $custom_key Key for JSON
     * @param integer $selected_item Selected Item
     * @param boolean $value_only If it's TRUE, return string. Otherwise return Array[$key]
     * @return mixed string | array
     */
    private function render_loop_element($element_type = '', $custom_key = '', $selected_item = 0, $value_only = false, $bowstyle_id=0) {
        $data_list = $this->model->list($bowstyle_id);
        $key = '';
        $element_pretext = '';
        $template_loc = '';
        if ($custom_key != '') {
            $key = $custom_key;
        } else {
            if ($element_type == $this->style_option_json_key) {
                $key = $this->style_option_json_key;
                $element_pretext = '<option value="0">Choose</option>';
                $template_loc = $this->style_option_template_loc;

                if(empty($data_list)) return $element_pretext;
            }
        }
        $element_value = Tools::create_loop_element(
            $data_list, 'styles', $key, $template_loc, $element_pretext, $selected_item
        );
        if ($value_only) {
            return $element_value[$key];
        }
        return $element_value;
    }

    /**
     * Get Elements
     * 'radio'
     *
     * @param array $elements Array Element
     * @return array
     */
    public function get_elements($elements=array(),$flag_id=0,$selected_item=0){
        $result = array();

        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        if(in_array($this->bowstyle_option_json_key,$elements)){
            $result[$this->json_key] = $this->create_bowstyle_option($flag_id, true);
        }
        if(in_array($this->style_option_json_key,$elements)){
            $result[$this->json_key][$this->style_option_json_key] = $this->render_loop_element($this->style_option_json_key,'',$selected_item,true,$flag_id);
        }
        if(in_array($this->info_json_key,$elements)){
            $live_style_id = $live_game_oc->style_id();
            $result[$this->json_key][$this->info_json_key] = $this->create_info($live_style_id);
        }
        if(in_array($this->preview_json_key,$elements)){
            $is_default = false;
            if($selected_item==0) $is_default = true;
            $result[$this->json_key][$this->preview_json_key] = $this->create_preview($selected_item,'',$is_default);
        }
        if(in_array($this->checkboxes_json_key,$elements)){
            $is_default = false;
            if($flag_id==0) $is_default = true;
            $result[$this->json_key][$this->checkboxes_json_key] = $this->create_checkboxes($flag_id,'',$is_default);
        }
        if(in_array($this->config_json_key,$elements)){
            $live_style_id = $live_game_oc->style_id();
            $result[$this->json_key][$this->config_json_key] = $this->create_config($live_style_id);
        }
        return $result;
    }

}
if (isset($_GET['scoreboard_style_get']) && $_GET['scoreboard_style_get'] != '') {
    $result = [
        'status' => true,
    ];
    $request_name = 'scoreboard_style_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {

        $database = new Database();
        $connection = $database->getConnection();

        if ($request_value == 'group') {
            if (isset($_GET['bowstyle_id'])) {
                $bowstyle_id = is_numeric($_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                $scoreboard_style_element = $scoreboard_style_oc->get_elements(['option'],$bowstyle_id);
                $result = array_merge(
                    $result,
                    $scoreboard_style_element
                );
            }
        } else if ($request_value == 'single') {
            if (isset($_GET['id'])) {
                $style_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                $scoreboard_style_element = $scoreboard_style_oc->get_elements(['preview'],0,$style_id);
                $result = array_merge(
                    $result,
                    $scoreboard_style_element
                );
            }
        } else if ($request_value == 'new_num') {}
        else if ($request_value == 'new_form_data') {
            if (isset($_GET['bowstyle_id'])) {

                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                $scoreboard_style_element = $scoreboard_style_oc->get_elements(['preview','checkbox']);
                $result = array_merge(
                    $result,
                    $scoreboard_style_element
                );
            }
        }
        else if ($request_value == 'cancel_form') {
            if (isset($_GET['style_id']) && isset($_GET['bowstyle_id'])) {
                $style_id = is_numeric($_GET['style_id']) ? $_GET['style_id'] : 0;
                $bowstyle_id = is_numeric($_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                $scoreboard_style_element = $scoreboard_style_oc->get_elements(['preview','option'],$bowstyle_id,$style_id);
                $result = array_merge(
                    $result,
                    $scoreboard_style_element
                );
            }
        }
        else if ($request_value == 'live') {
            $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
            $live_game_oc = new Live_Game_Controller_Class($connection);
            $style_id = $live_game_oc->style_id();
            // $key = 'scoreboard_style_option';
            // $result[$key] = $scoreboard_style_oc->create_style_option($live_game_oc->style_bowstyle_id(),$key)[$key];
            // $key = 'style_preview';
            // $result[$key] = $scoreboard_style_oc->create_preview($style_id,$key)[$key];
            // $key = 'selected';
            // $result[$key] = $style_id;
            $scoreboard_style_element = $scoreboard_style_oc->get_elements(['preview','option'],$live_game_oc->style_bowstyle_id(),$style_id);
            $result = array_merge(
                $result,
                $scoreboard_style_element,
                ['live_style' => $style_id]
            );
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

if (isset($_GET['style_config_get']) && $_GET['style_config_get'] != '') {
    $result = [
        'status' => true,
    ];
    $request_name = 'style_config_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {

        $database = new Database();
        $connection = $database->getConnection();

        if ($request_value == 'checkbox') {
            if (isset($_GET['style_id'])) {
                $style_id = is_numeric($_GET['style_id']) ? $_GET['style_id'] : 0;

                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                // $key = 'checkbox';
                // $result[$key] = $scoreboard_style_oc->create_checkboxes($style_id, $key)[$key];
                $scoreboard_style_element = $scoreboard_style_oc->get_elements(['checkbox'],$style_id);
                $result = array_merge(
                    $result,
                    $scoreboard_style_element
                );
            }
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

if (isset($_POST['scoreboard_style_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'scoreboard_style_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {

        $style_id = is_numeric($_POST['style']) ? $_POST['style'] : 0;
        $style_config = null;
        $data = array();

        $database = new Database();
        $connection = $database->getConnection();

        if ($request_value == 'create' || $request_value == 'update') {
            $style_config = array(
                'logo' => array(
                    'label' => '',
                    'visibility' => $_POST['logo'] == "true" ? true : false,
                    'visibility_class' => $_POST['logo'] == "true" ? '' : 'hide',
                ),
                'team' => array(
                    'label' => '',
                    'visibility' => $_POST['team'] == "true" ? true : false,
                    'visibility_class' => $_POST['team'] == "true" ? '' : 'hide',
                ),
                'player' => array(
                    'label' => '',
                    'visibility' => $_POST['player'] == "true" ? true : false,
                    'visibility_class' => $_POST['player'] == "true" ? '' : 'hide',
                ),
                'timer' => array(
                    'label' => '',
                    'visibility' => $_POST['timer'] == "true" ? true : false,
                    'visibility_class' => $_POST['timer'] == "true" ? '' : 'hide',
                ),
                'score1' => array(
                    'label' => '',
                    'visibility' => $_POST['score1'] == "true" ? true : false,
                    'visibility_class' => $_POST['score1'] == "true" ? '' : 'hide',
                ),
                'score2' => array(
                    'label' => '',
                    'visibility' => $_POST['score2'] == "true" ? true : false,
                    'visibility_class' => $_POST['score2'] == "true" ? '' : 'hide',
                ),
                'score3' => array(
                    'label' => '',
                    'visibility' => $_POST['score3'] == "true" ? true : false,
                    'visibility_class' => $_POST['score3'] == "true" ? '' : 'hide',
                ),
                'score4' => array(
                    'label' => '',
                    'visibility' => $_POST['score4'] == "true" ? true : false,
                    'visibility_class' => $_POST['score4'] == "true" ? '' : 'hide',
                ),
                'score5' => array(
                    'label' => '',
                    'visibility' => $_POST['score5'] == "true" ? true : false,
                    'visibility_class' => $_POST['score5'] == "true" ? '' : 'hide',
                ),
                'score6' => array(
                    'label' => '',
                    'visibility' => $_POST['score6'] == "true" ? true : false,
                    'visibility_class' => $_POST['score6'] == "true" ? '' : 'hide',
                ),
                'setpoint' => array(
                    'label' => '',
                    'visibility' => $_POST['setpoint'] == "true" ? true : false,
                    'visibility_class' => $_POST['setpoint'] == "true" ? '' : 'hide',
                ),
                'gamepoint' => array(
                    'label' => '',
                    'visibility' => $_POST['gamepoint'] == "true" ? true : false,
                    'visibility_class' => $_POST['gamepoint'] == "true" ? '' : 'hide',
                ),
                'setscore' => array(
                    'label' => '',
                    'visibility' => $_POST['setscore'] == "true" ? true : false,
                    'visibility_class' => $_POST['setscore'] == "true" ? '' : 'hide',
                ),
                'gamescore' => array(
                    'label' => '',
                    'visibility' => $_POST['gamescore'] == "true" ? true : false,
                    'visibility_class' => $_POST['gamescore'] == "true" ? '' : 'hide',
                ),
                'description' => array(
                    'label' => '',
                    'visibility' => $_POST['description'] == "true" ? true : false,
                    'visibility_class' => $_POST['description'] == "true" ? '' : 'hide',
                ),
            );

            $data['style_config'] = json_encode($style_config);
        }
        if ($request_value == 'create'){

            // TO-DO >> prevent null ID

            $bowstyle_id = is_numeric($_POST['bowstyle_id']) ? $_POST['bowstyle_id'] : 0;

            $data['id'] = 0;
            $data['bowstyle_id'] = $bowstyle_id;
            $data['style_name'] = $_POST['style_name'] == '' ? 'My Custom Style' : $_POST['style_name'];

            $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
            $result = $scoreboard_style_oc->create($data,true);
        }
        else if( $request_value == 'update'){

            $data['id'] = $style_id;
            $data['bowstyle_id'] = 0;
            $data['style_name'] = $_POST['style_name'] == '' ? 'My Custom Style' : $_POST['style_name'];

            $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
            $result = $scoreboard_style_oc->update($style_id,$data);
        }
        else if($request_value == 'delete' ) {
            $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
            $result = $scoreboard_style_oc->delete($style_id);
        }
        else if( $request_value == 'set-scoreboard-style'){

            $live_game_oc = new Live_Game_Controller_Class($connection);
            $result = $live_game_oc->set_live_style($style_id);
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

?>