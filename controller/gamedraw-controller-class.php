<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gamedraw_Model_Class;

class Gamedraw_Controller_Class extends Controller_Class{
    private $connection;

    private $model;

    private $json_key;
    private $option_json_key;
    private $table_json_key;
    private $summary_json_key;

    private $option_template_name;
    private $option_template_loc;

    private $item_template_name;
    private $item_template_loc;

    private $no_item_template_name;
    private $no_item_template_loc;

    private $summary_template_name;
    private $summary_template_loc;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection=null)
    {
        $this->connection = $connection;
        $this->model = new Gamedraw_Model_Class($connection);
        $this->init_json_key();
        $this->init_templates();
    }

    /**
     * Do Request
     *
     * @param string $request_value
     * @return array
     */

    private function init_json_key(){
        $this->json_key = 'gamedraw';
        $this->option_json_key = 'option';
        $this->table_json_key = 'table';
        $this->summary_json_key = 'summary';
    }

    private function init_templates(){
        $template_loc = TEMPLATE_DIR . 'gamedraw/';
        $this->option_template_name = 'option';
        $this->option_template_loc = $template_loc . $this->option_template_name . '.php';

        $this->item_template_name = 'item';
        $this->item_template_loc = $template_loc . $this->item_template_name . '.php';

        $this->no_item_template_name = 'no-item';
        $this->no_item_template_loc = $template_loc . $this->no_item_template_name . '.php';

        $this->summary_template_name = 'summary';
        $this->summary_template_loc = $template_loc . $this->summary_template_name . '.php';
    }

    /**
     * Get Team Gamedraws ID
     *
     * @param integer $team_id Team ID
     * @return void array [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    public function get_team_gamedraws_id($team_id=0){
        return $this->model->team_gamedraws_id($team_id);
    }

    /**
     * Get Player Gamedraws ID
     *
     * @param integer $player_id Player ID
     * @return void array [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    public function get_player_gamedraws_id($player_id=0){
        return $this->model->player_gamedraws_id($player_id);
    }

    /**
     * Delete Team Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    public function delete_team_gamedraws($gamedraws_id=null){
        return $this->model->delete_gamedraws($gamedraws_id);
    }

    /**
     * Delete Player Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    public function delete_player_gamedraws($gamedraws_id=null){
        return $this->model->delete_gamedraws($gamedraws_id);
    }

    /**
     * Get New Gamedraw Number
     *
     * @return array [status,new_num]
     */
    public function get_new_number(){
        return [
            'status'  => true,
            'new_num' => $this->model->new_number()
        ];
    }

    /**
     * Get Contestants ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array [contestant_a_id,contestant_b_id]
     */
    public function get_contestants_id($gamedraw_id=0){
        $contestants = array();
        $action_om = $this->model->contestants_id($gamedraw_id);
        if(!empty($action_om)){
            $contestants = $action_om;
        }
        return $contestants;
    }

    /**
     * Get Form Modal Data
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [ id, num, bowstyle_id, gamemode_id, contestant_a_id, contestant_b_id ]
     */
    public function get_modal_form_data($gamedraw_id=0) {
        $result = [
            'status' => false
        ];
        if( $gamedraw_id == 0 ){
            $result['message'] = 'ERROR: get_modal_form_data Gamedraw ID: 0';
            return $result;
        }
        $data = $this->model->modal_form_data($gamedraw_id);
        if(empty($data)){
            $result['message'] = 'ERROR: get_modal_form_data modal_form_data';
            return $result;
        }
        $result['status'] = true;
        $result[$this->json_key] = $data;

        return $result;
    }

    /**
     * Get Gamesets ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [gameset_id1, gameset_id2, gameset_id..n]
     */
    private function get_gamesets_id($gamedraw_id=0){
        if ($gamedraw_id > 0) {
            $game_set_oc = new Gameset_Controller_Class($this->connection);
            return $game_set_oc->get_gamedraw_gamesets_id($gamedraw_id);
        }
        return array();
    }

    /**
     * Get Scores ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    private function get_scores_id($gamedraw_id=0){
        if ($gamedraw_id > 0) {
            $score_oc = new Score_Controller_Class($this->connection);
            return $score_oc->get_gamedraw_scores_id($gamedraw_id);
        }
        return array();
    }

    /**
     * Delete Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    private function delete_scores($gamedraw_id=null){
        if(empty($gamedraw_id) || is_null($gamedraw_id)) return false;
        $score_oc = new Score_Controller_Class($this->connection);
        return $score_oc->delete_gamedraw_scores($gamedraw_id);
    }

    /**
     * Delete Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    private function delete_gamesets($gamesets_id=null){
        if(empty($gamesets_id) || is_null($gamesets_id)) return false;
        $gameset_oc = new Gameset_Controller_Class($this->connection);
        return $gameset_oc->delete_gamedraw_gamesets($gamesets_id);
    }

    /**
     * Delete Gamedraw
     *
     * @param array|integer Gamedraw ID
     * @return boolean
     */
    public function delete_gamedraw($gamedraw_id){

        $result = [
            'status' => false,
            'action' => 'delete',
        ];
        if( $gamedraw_id == 0){
            $result['message'] = 'ERROR: delete_gamedraw Gamedraw ID: 0';
            return $result;
        }
        $gamesets_id = $this->get_gamesets_id($gamedraw_id);
        if( !empty($gamesets_id)){
            $scores_id = $this->get_scores_id($gamedraw_id);

            if (!empty($scores_id)) {
                if (!$this->delete_scores($scores_id)) {
                    $result['message'] = 'ERROR: delete_gamedraw delete_scores';
                    return $result;
                }
            }
            if (!$this->delete_gamesets($gamesets_id)) {
                $result['message'] = 'ERROR: delete_gamedraw delete_gamesets';
                return $result;
            }

            $live_game_oc = new Live_Game_Controller_Class($this->connection);
            if ($live_game_oc->is_gameset_live($gamesets_id)) {
                if (!$live_game_oc->set_live_game(0)) {
                    $result['message'] = 'ERROR: delete_gamedraw set_live_game';
                    return $result;
                }
            }
        }

        if (!$this->model->delete_gamedraw($gamedraw_id)) {
            $result['message'] = 'ERROR: delete_gamedraw';
            return $result;
        }
        $result['status'] = true;

        return $result;
    }

    /**
     * Gamedraw Action
     *
     * @param string $request_value Request Value
     * @param array $data Gamedraw Data
     * @return array [status,action,*message]
     */
    public function form_action($request_value = '', $data = null){
        $result = [
            'status' => false,
            'action' => $request_value
        ];
        if( $request_value == '' || is_null($data) ){
            $result['message'] = 'ERROR: gamedraw_action Request:"" | Data: Null';
            return $result;
        }

        if (!is_null($data)) {

            // $game_draw_data = array(
            //     'id'                => $gamedraw_id,
            //     'num'               => 1,
            //     'bowstyle_id'       => 0,
            //     'gamemode_id'       => 0,
            //     'contestant_a_id'   => 0,
            //     'contestant_b_id'   => 0
            // );
        }

        if( $request_value == 'create' ) {
            $result['status'] = $this->model->create_game_draw( $data );
            if(!$result['status']) {
                $result['message'] = 'ERROR: gamedraw_action create_game_draw';
            }
            $result['next_num'] = $data['num'] + 1;
        }else if( $request_value == 'update') {
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: gamedraw_action update_player';
                return $result;
            }
            $result['status'] = $this->model->update_game_draw( $data );
            if(!$result['status']) {
                $result['message'] = 'ERROR: gamedraw_action update_game_draw';
            }
        }else if($request_value == 'delete'){
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: gamedraw_action delete';
                return $result;
            }
            $result = $this->delete_gamedraw($data['id']);
        }

        return $result;
    }

    /**
     * Get Elements
     *
     * @param array $elements Element [ table, option ]
     * @param string $custom_parent_key Custom Parent Key
     * @param integer $flag_id Flag ID
     * @param integer $selected_item Selected Item
     * @param boolean $value_only If TRUE, return children
     * @return array empty | string
     */
    public function get_elements($elements = array(), $custom_parent_key = '', $flag_id=0, $selected_item = 0, $value_only = false) {
        $result = array();
        if (empty($elements)) {
            return $result;
        }

        $parent_key = '';
        if ($custom_parent_key == '') {
            $parent_key = $this->json_key;
        } else {
            $parent_key = $custom_parent_key;
        }

        if (in_array($this->table_json_key, $elements)) {
            $result[$parent_key][$this->table_json_key] = $this->render_loop_element($this->table_json_key, '', $selected_item, $value_only);
        }

        if (in_array($this->option_json_key, $elements)) {
            // $result[$this->json_key]['radio'] = $this->get_radio('radio')['radio'];
            $result[$parent_key][$this->option_json_key] = $this->render_loop_element($this->option_json_key, '', $selected_item, $value_only);
        }

        if (in_array($this->summary_json_key, $elements)) {
            // $result[$this->json_key]['radio'] = $this->get_radio('radio')['radio'];
            $result[$parent_key][$this->summary_json_key] = $this->render_summary_element($flag_id);
        }
        return $result;
    }

    /**
     * Render Summary Element
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return string
     */
    private function render_summary_element($gamedraw_id=0){
        $summary_element = '';
        $summary_data = $this->model->summary($gamedraw_id);
        if(!empty($summary_data)){
            $summary_element = Tools::template($this->summary_template_loc, $summary_data);
        }
        return $summary_element;
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
    private function render_loop_element($element_type = '', $custom_key = '', $selected_item = 0, $value_only = false) {
        $data_list = $this->model->list();
        $key = '';
        $element_pretext = '';
        $template_loc = '';
        if ($custom_key != '') {
            $key = $custom_key;
        } else {
            if ($element_type == $this->option_json_key) {
                $key = $this->option_json_key;
                $element_pretext = '<option value="0">Choose</option>';
                $template_loc = $this->option_template_loc;
            } else if ($element_type == $this->table_json_key) {
                $key = $this->table_json_key;
                $template_loc = $this->item_template_loc;
            }
        }
        $element_value = Tools::create_loop_element(
            $data_list, 'gamedraws', $key, $template_loc, $element_pretext, $selected_item
        );
        if ($value_only) {
            if($element_type == $this->table_json_key && $element_value[$key] == ''){
                return Tools::template($this->no_item_template_loc, null);
            }
            return $element_value[$key];
        }
        return $element_value;
    }
}
if ( isset( $_GET['gamedraw_get'])){
    $result = [
        'status' => true,
    ];
    $request_name = 'gamedraw_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();

        if ( $request_value == 'new_num'){
            $gamedraw_oc = new Gamedraw_Controller_Class($connection);
            $result = $gamedraw_oc->get_new_number();

        }
        else if ( $request_value == 'modal_data' ){
            if(isset($_GET['id'])){
                $gamedraw_oc = new Gamedraw_Controller_Class($connection);
                $gamedraw_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                $result = $gamedraw_oc->get_modal_form_data($gamedraw_id);
            }
        }
        else if( $request_value == 'summary' && isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0){
            if(isset($_GET['id'])){
                $gamedraw_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                $gamedraw_oc = new Gamedraw_Controller_Class($connection);
                $gamedraw_element = $gamedraw_oc->get_elements(['summary'],'',$gamedraw_id,0,true);
                $result = array_merge($result, $gamedraw_element);
            }
        }else if ($request_value == 'new') {
            $gamedraw_oc = new Gamedraw_Controller_Class($connection);
            $gamedraw_element = $gamedraw_oc->get_elements(['table','option'],'',0,0,true);
            $result = array_merge($result, $gamedraw_element);
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

// Action Game Draw
if ( isset( $_POST['gamedraw_action']) ) {
    $result = [
        'status' => true,
    ];
    $request_name = 'gamedraw_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        $gamedraw_oc = new Gamedraw_Controller_Class($connection);

        $gamedraw_id = 0;
        if( $request_value == 'update' || $request_value == 'delete' ){
            if( isset($_POST['gamedraw_id'])) {
                $gamedraw_id = is_numeric($_POST['gamedraw_id']) ? $_POST['gamedraw_id'] : $gamedraw_id;
            }
        }

        $gamedraw_data = array(
            'id'                => $gamedraw_id,
            'num'               => 1,
            'bowstyle_id'       => 0,
            'gamemode_id'       => 0,
            'contestant_a_id'   => 0,
            'contestant_b_id'   => 0
        );
        if($request_value == 'create' || $request_value == 'update'){
            $gamedraw_data['num'] = is_numeric($_POST['gamedraw_num']) ? $_POST['gamedraw_num'] : 1;
            $gamedraw_data['bowstyle_id'] = isset($_POST['gamedraw_bowstyle']) ? $_POST['gamedraw_bowstyle'] : 0;
            $gamedraw_data['gamemode_id'] = isset($_POST['gamedraw_gamemode']) ? $_POST['gamedraw_gamemode'] : 0;
            if( $gamedraw_data['gamemode_id'] == 1 ) {// ID Beregu
                $gamedraw_data['contestant_a_id'] = isset($_POST['gamedraw_team_a']) ? $_POST['gamedraw_team_a'] : 0;
                $gamedraw_data['contestant_b_id'] = isset($_POST['gamedraw_team_b']) ? $_POST['gamedraw_team_b'] : 0;
            }else if( $gamedraw_data['gamemode_id'] == 2) {// ID Individu
                $gamedraw_data['contestant_a_id'] = isset($_POST['gamedraw_player_a']) ? $_POST['gamedraw_player_a'] : 0;
                $gamedraw_data['contestant_b_id'] = isset($_POST['gamedraw_player_b']) ? $_POST['gamedraw_player_b'] : 0;
            }
        }

        $result = $gamedraw_oc->form_action($request_value, $gamedraw_data);

        $database->conn->close();
    }
    echo json_encode($result);
}
?>