<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\model\Scoreboard_Style_Model_Class;
use \scoreboard\includes\Tools;

class Scoreboard_Style_Controller_Class extends Controller_Class {

    protected $connection;

    private $model = null;

    private $root_key;
    private $bowstyle_key;
    private $info_key;
    private $config_key;

    private $preview_template_name;
    private $preview_template_loc;
    private $preview_key;

    private $checkbox_template_name;
    private $checkbox_template_loc;
    private $checkbox_key;

    private $style_option_template_name;
    private $style_option_template_loc;
    private $style_key;

    private $style_id;
    private $bowstyle_id;

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
        $this->root_key = 'scoreboard_styles';
        $this->preview_key = 'preview';
        $this->bowstyle_key = 'bowstyle';
        $this->info_key = 'info';
        $this->style_key = 'option';
        $this->checkbox_key = 'checkbox';
        $this->config_key = 'config';
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
     * Set Bowstyle ID
     *
     * @param integer $bowstyle_id Bowstyle ID
     * @return void
     */
    public function set_bowstyle_id($bowstyle_id=0){
        $this->bowstyle_id = $bowstyle_id;
    }

    /**
     * Set Style ID
     *
     * @param integer $bowstyle_id Style ID
     * @return void
     */
    public function set_style_id($style_id=0){
        $this->style_id = $style_id;
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
     * Get Bowstyle Option Data
     *
     * @return void
     */
    private function get_bowstyle_option_data(){
        $bowstyle_oc = new Bowstyle_Controller_Class($this->connection);
        $bowstyle_oc->set_id($this->bowstyle_id);

        return $bowstyle_oc->get_data(['option'])['bowstyle'];
    }

    /**
     * Deactivate Style
     *
     * @param integer $style_id Selected Style ID
     * @return boolean true | false
     */
    public function deactivate_style($style_id=0) {
        $result = [ 'status' => false ];
        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        if( $style_id > 0 && $style_id == $live_game_oc->style_id()) {
            $result['status'] = $live_game_oc->set_style(0);
        }
        return $result;
    }

    /**
     * Get Style Option Data
     *
     * @return array [ 'styles' ]
     */
    private function get_style_option_data(){
        return $this->model->style_option_data($this->bowstyle_id, $this->style_id);
    }

    /**
     * Get Style Config
     *
     * @return array
     */
    private function get_style_config(){
        $style_config = array();
        if($this->style_id==0){ // default style config
            $style_config = Tools::get_default_style_config();
        }else{
            $style_config = json_decode($this->model->style_config($this->style_id),true);
        }
        return $style_config;

    }

    /**
     * Get Score Data
     *
     * @return array
     */
    private function get_score_data(){
        $res = array();

        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        $game_data = $live_game_oc->get_game_data_bm_id();
        $score_oc = new Score_Controller_Class($this->connection);
        $gamemode_id = 0;
        if(! empty($game_data)){
            $gamemode_id = $game_data['gamemode_id'];
        }
        $res =  $score_oc->get_scoreboard_preview_data($gamemode_id);

        return $res;
    }

    /**
     * Get Preview Data
     *
     * @return array
     */
    private function get_preview_data(){
        $style_config = $this->get_style_config();

        $formatted_style_config = array();
        foreach ($style_config as $key => $value) {
            foreach($value as $vkey => $vval ){
                if($vkey == 'visibility_class' ){
                    $formatted_style_config[$key . '_vc' ] = $vval;
                }
            }
        }

        $score_data = $this->get_score_data();

        return [ 'style_config' => $formatted_style_config, 'score_data' => $score_data ];
    }

    /**
     * Form Config
     *
     * @return array
     */
    private function form_config(){
        $config = array();
        if ($this->style_id == 0) {

            $config = [
                'activate_btn' => 'hide',
                'deactivate_btn' => 'hide',
                'save_btn' => 'hide',
                'cancel_btn' => 'hide',
                'new_btn' => 'hide',
                'edit_btn' => 'hide',
                'delete_btn' => 'hide',
            ];
        }else{

            $config = [
                'activate_btn' => 'hide',
                'deactivate_btn' => '',
                'save_btn' => 'hide',
                'cancel_btn' => 'hide',
                'new_btn' => '',
                'edit_btn' => '',
                'delete_btn' => '',
            ];
        }

        return $config;
    }

    /**
     * Get Active Style Info
     *
     * @return array
     */
    private function get_active_style_info(){
        $res = array( 'bowstyle_name' => '', 'style_name' => '');
        if ($this->style_id > 0) {
            $res = $this->model->active_style_info($this->style_id);
        }
        return $res;
    }

    /**
     * Get Checkbox Data
     *
     * @return array
     */
    private function get_checkbox_data(){
        $res = array();
        if( $this->style_id == 0 ) {
            $style_config = Tools::get_default_style_config();
        }else{
            $style_config = $this->get_style_config();
        }
        foreach ($style_config as $key => $value) {
            foreach($value as $vkey => $vval ){
                $val = $vval;
                if($vkey == 'visibility') {
                    $val = $vval ? 'checked value="true"': 'value="false"';
                    $res[$key . '_checked'] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * Get Data.
     *
     * @param array $req_data [ 'option', 'bowstyle', 'info', 'preview', 'checkbox', 'config' ]
     * @return array
     */
    public function get_data($req_data=array( 'option', 'bowstyle', 'info', 'preview', 'checkbox', 'config' )){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        if( empty($req_data) ){
            return $result;
        }

        $data = null;

        if(in_array($this->preview_key,$req_data)){
            $data = is_null($data) ? $this->get_preview_data() : $data;
            $root_res[$this->preview_key] = $data;
        }

        if(in_array($this->style_key,$req_data)){
            // [ 'id', 'name', 'selected' ]
            $data = $this->get_style_option_data();
            $root_res[$this->style_key] = $data;
        }

        if(in_array($this->bowstyle_key,$req_data)){
            // [ 'id', 'name', 'selected' ]
            $data = $this->get_bowstyle_option_data();
            $root_res[$this->bowstyle_key] = $data;
        }

        if(in_array($this->checkbox_key,$req_data)){
            $data = $this->get_checkbox_data();
            $root_res[$this->checkbox_key] = $data;
        }

        if(in_array($this->config_key,$req_data)){
            $data = $this->form_config();
            $root_res[$this->config_key] = $data;
        }

        if(in_array($this->info_key,$req_data)){
            $data = $this->get_active_style_info();
            $root_res[$this->info_key] = $data;
        }

        $result[$this->root_key] = $root_res;

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
        $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);

        if ($request_value == 'group') {
            if (isset($_GET['bowstyle_id'])) {
                $bowstyle_id = is_numeric($_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

                $scoreboard_style_oc->set_bowstyle_id($bowstyle_id);
                $scoreboard_style_data = $scoreboard_style_oc->get_data(['option']);
            }
        } else if ($request_value == 'single') {
            if (isset($_GET['id'])) {
                $style_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $live_game_oc = new Live_Game_Controller_Class($connection);
                $scoreboard_style_oc->set_style_id($style_id);
                $scoreboard_style_data = $scoreboard_style_oc->get_data(['preview']);
                $scoreboard_style_data['is_live'] = $live_game_oc->style_id()==$style_id;
            }
        }
        else if ($request_value == 'new_form_data') {
            if (isset($_GET['bowstyle_id'])) {
                $bowstyle_id = is_numeric($_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

                $scoreboard_style_oc->set_bowstyle_id($bowstyle_id);
                $scoreboard_style_data = $scoreboard_style_oc->get_data(['preview','checkbox']);
            }
        }
        else if ($request_value == 'cancel_form') {
            if (isset($_GET['style_id']) && isset($_GET['bowstyle_id'])) {
                $style_id = is_numeric($_GET['style_id']) ? $_GET['style_id'] : 0;
                $bowstyle_id = is_numeric($_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

                $scoreboard_style_oc->set_style_id($style_id);
                $scoreboard_style_oc->set_bowstyle_id($bowstyle_id);
                $scoreboard_style_data = $scoreboard_style_oc->get_data(['preview','option']);
            }
        }
        else if ($request_value == 'live') {
            $live_game_oc = new Live_Game_Controller_Class($connection);
            $style_id = $live_game_oc->style_id();
            $scoreboard_style_oc->set_style_id($style_id);
            $scoreboard_style_oc->set_bowstyle_id($live_game_oc->style_bowstyle_id());
            $scoreboard_style_data = $scoreboard_style_oc->get_data(['preview','option']);
            $result['live_style'] = $style_id;
        }
        $result = array_merge($result,$scoreboard_style_data);
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
                $scoreboard_style_oc->set_style_id($style_id);
                // $key = 'checkbox';
                // $result[$key] = $scoreboard_style_oc->create_checkboxes($style_id, $key)[$key];
                $scoreboard_style_data = $scoreboard_style_oc->get_data(['checkbox']);
                $result = array_merge(
                    $result,
                    $scoreboard_style_data
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

        $style_id = 0;
        if( isset($_POST['style'])) {
            $style_id = is_numeric($_POST['style']) ? $_POST['style'] : 0;
        }
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
        else if ($request_value == 'deactivate-style') {
            if( isset($_POST['style_id'] ) ) {
                $style_id = is_numeric($_POST['style_id']) ? $_POST['style_id'] : 0;
                $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
                $result = $scoreboard_style_oc->deactivate_style($style_id);
            }
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

?>