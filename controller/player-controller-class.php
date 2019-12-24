<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Player_Model_Class;

class Player_Controller_Class {

    private $connection;
    private $model;

    private $gamemode_id;

    private $json_key;
    private $table_json_key;
    private $option_json_key;

    private $item_template_name;
    private $item_template_loc;

    private $no_item_template_name;
    private $no_item_template_loc;

    private $option_template_name;
    private $option_template_loc;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Player_Model_Class($connection);

        $this->gamemode_id = 2;

        $this->json_key = 'player';
        $this->table_json_key = 'table';
        $this->option_json_key = 'option';

        $this->init_templates();
    }

    /**
     * Init templates
     *
     * @return void
     */
    private function init_templates(){

        $template_loc = TEMPLATE_DIR . 'player/';
        $this->item_template_name = 'item';
        $this->item_template_loc = $template_loc . $this->item_template_name . '.php';
        $this->no_item_template_name = 'no-item';
        $this->no_item_template_loc = $template_loc . $this->no_item_template_name . '.php';
        $this->option_template_name = 'option';
        $this->option_template_loc = $template_loc . $this->option_template_name . '.php';
    }

    /**
     * Get Team Players ID
     *
     * @param integer $team_id Team ID
     * @return array (player_id1, player_id2, player_id..n)
     */
    public function get_team_players_id($team_id=0){
        return $this->model->team_players_id($team_id);
    }

    /**
     * Delete Team Players
     *
     * @param array $players_id Players ID
     * @return boolean
     */
    public function delete_team_players($players_id=null){
        return $this->model->delete_players($players_id);
    }

    /**
     * Get Elements
     *
     * @param array $elements Element [ table, option ]
     * @param string $custom_parent_key Custom Parent Key
     * @param integer $selected_item Selected Item
     * @param boolean $value_only If TRUE, return children
     * @return array empty | string
     */
    public function get_elements($elements = array(), $custom_parent_key = '', $selected_item = 0, $value_only = false) {
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
            $result[$parent_key][$this->option_json_key] = $this->render_loop_element($this->option_json_key, '', $selected_item, $value_only);
        }
        return $result;
    }

    /**
     * Get Gamedraws ID
     *
     * @param integer $player_id Player ID
     * @return array empty | [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    private function get_gamedraws_id($player_id=0){
        if($player_id>0){
            $gamedraw_oc = new Gamedraw_Controller_Class($this->connection);
            return $gamedraw_oc->get_player_gamedraws_id($player_id);
        }
        return array();
    }

    /**
     * Get Gamesets ID
     *
     * @param integer $player_id Player ID
     * @return array empty [gameset_id1, gameset_id..n]
     */
    private function get_gamesets_id($player_id=0){
        if ($player_id > 0) {
            $gameset_oc = new Gameset_Controller_Class($this->connection);
            return $gameset_oc->get_player_gamesets_id($player_id);
        }
        return array();
    }

    /**
     * Get Scores ID
     *
     * @param integer $player_id Player ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    private function get_scores_id($player_id=0){
        if ($player_id > 0) {
            $score_oc = new Score_Controller_Class($this->connection);
            return $score_oc->get_player_scores_id($player_id);
        }
        return array();
    }

    /**
     * Delete Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    private function delete_scores($scores_id=null){
        if(empty($scores_id) || is_null($scores_id)) return false;
        $score_oc = new Score_Controller_Class($this->connection);
        return $score_oc->delete_player_scores($scores_id);
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
        return $gameset_oc->delete_player_gamesets($gamesets_id);
    }

    /**
     * Delete Gamedraws
     *
     * @param array $gamedraws_id Gamedraws ID
     * @return boolean
     */
    private function delete_gamedraws($gamedraws_id=null){
        if(empty($gamedraws_id) || is_null($gamedraws_id)) return false;
        $gamedraw_oc = new Gamedraw_Controller_Class($this->connection);
        return $gamedraw_oc->delete_player_gamedraws($gamedraws_id);
    }

    /**
     * Delete Player
     *
     * @param array|integer Player ID
     * @return boolean|array true | false | result[]
     */
    private function delete_player($player_id) {

        $result = [
            'status' => false,
            'action' => 'delete',
        ];
        if( $player_id == 0){
            $result['message'] = 'ERROR: delete_player Player ID: 0';
            return $result;
        }
        $gamedraws_id = $this->get_gamedraws_id($player_id);
        if(!empty($gamedraws_id)){
            $gamesets_id = $this->get_gamesets_id($player_id);
            if(!empty($gamesets_id)){
                $scores_id = $this->get_scores_id($player_id);

                if (!empty($scores_id)) {
                    if (!$this->delete_scores($scores_id)) {
                        $result['message'] = 'ERROR: delete_player delete_score';
                        return $result;
                    }
                }
                if (!$this->delete_gamesets($gamesets_id)) {
                    $result['message'] = 'ERROR: delete_player delete_gamesets';
                    return $result;
                }

                $live_game_oc = new Live_Game_Controller_Class($this->connection);
                if ($live_game_oc->is_gameset_live($gamesets_id)) {
                    if (!$live_game_oc->set_live_game(0)) {
                        $result['message'] = 'ERROR: delete_player set_live_game';
                        return $result;
                    }
                }
            }
            if (!$this->delete_gamedraws($gamedraws_id)) {
                $result['message'] = 'ERROR: delete_player delete_gamedraws';
                return $result;
            }
        }

        if (!$this->model->delete_player($player_id)) {
            $result['message'] = 'ERROR: delete_player';
            return $result;
        }
        $result['status'] = true;

        return $result;
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
            $data_list, 'players', $key, $template_loc, $element_pretext, $selected_item
        );
        if ($value_only) {
            if($element_type == $this->table_json_key && $element_value[$key] == ''){
                return Tools::template($this->no_item_template_loc, null);
            }
            return $element_value[$key];
        }
        return $element_value;
    }

    /**
     * Get Model Form Data
     *
     * @param integer $player_id Player ID
     * @return array result
     */
    public function get_modal_form_data($player_id=0){
        $result = [ 'status' => false ];
        if($player_id==0){
            $result['message'] = 'ERROR: get_modal_form_data Player ID:0';
            return $result;
        }
        $data = $this->model->modal_form_data($player_id);
        if(empty($data)){
            $result['message'] = 'ERROR: get_modal_form_data Empty Data';
            return $result;
        }
        $result['status'] = true;
        $result[$this->json_key] = $data;

        return $result;
    }

    /**
     * Get Scoreboard Form Data
     *
     * @param integer $player_id Player ID
     * @return mixed false | [player_name, team_name, team_logo]
     */
    public function get_scoreboard_form_data($player_id=0) {
        if( $player_id==0) return false;

        $data = $this->model->scoreboard_form_data($player_id);
        if( $data ){
            return $data;
        }
        return false;
    }

    /**
     * Form Action
     *
     * @param string $request_value Request Value
     * @param array $data Player Data
     * @return array [status,action,*message]
     */
    public function form_action($request_value = '', $data = null){
        $result = [
            'status' => false,
            'action' => $request_value
        ];
        if( $request_value == '' || is_null($data) ) return $result;

        if( $request_value == 'create' ) {
            $result['status'] = $this->model->create_player( $data['name'], $data['team_id'] );
            if(!$result['status']) {
                $result['message'] = 'ERROR: form_action create_player';
            }
        }else if( $request_value == 'update') {
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: form_action update_player';
                return $result;
            }
            $result['status'] = $this->model->update_player( $data['id'], $data['name'], $data['team_id']);
            if(!$result['status']) {
                $result['message'] = 'ERROR: form_action update_player';
            }
        }else if($request_value == 'delete'){
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: form_action delete_player';
                return $result;
            }
            $result = $this->delete_player($data['id']);
        }

        return $result;
    }
}

if (isset($_POST['player_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'player_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {

        $database = new Database();
        $connection = $database->getConnection();
        $player_oc = new Player_Controller_Class($connection);

        $player_id = 0;
        if( $request_value == 'update' || $request_value == 'delete' ){
            if( isset($_POST['player_id'])) {
                $player_id = is_numeric($_POST['player_id']) ? $_POST['player_id'] : $player_id;
            }
        }
        $player_data = [
            'id'        => $player_id,
            'name'      => '',
            'team_id'   => 0
        ];
        if($request_value == 'create' || $request_value == 'update'){
            $player_data['name'] = $_POST['player_name'] != '' ? $_POST['player_name'] : 'player';
            $player_data['team_id'] = $_POST['player_team'] > 0 ? $_POST['player_team'] : 0;
        }

        $result = $player_oc->form_action($request_value, $player_data);

        $database->conn->close();
    }
    echo json_encode($result);
}

if (isset($_GET['player_get'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'player_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        if ($request_value == 'single') {
            if(isset($_GET['id'])){
                $player_oc = new Player_Controller_Class($connection);
                $player_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                // $result = $player_oc->get_single_player($player_id);
                $result = $player_oc->get_modal_form_data($player_id);
            }
        }else if ($request_value == 'new') {
            $player_oc = new Player_Controller_Class($connection);
            // $result = $player_oc->get_player_elements(true, true);
            $team_element = $player_oc->get_elements(['table','option'],'',0,true);
            $result = array_merge( $result, $team_element);
        }
        // else if ($request_value == 'list') {
        //     $database = new Database();
        //     $db = $database->getConnection();

        //     $player = new Player_Model_Class($db);
        //     $result_query = $player->get_player_list();
        //     if ($result_query['status']) {
        //         $result['status'] = true;
        //         $result['has_value'] = $result_query['has_value'];
        //         if ($result['has_value']) {
        //             $item_template = TEMPLATE_DIR . 'player/item.php';
        //             $option_template = TEMPLATE_DIR . 'player/option.php';
        //             $render_item = '';
        //             $render_option = '<option value="0">Select a player</option>';
        //             foreach ($result_query['players'] as $item) {
        //                 $render_item .= Tools::template($item_template, $item);
        //                 $render_option .= Tools::template($option_template, $item);
        //             }
        //             $result['players'] = $render_item;
        //             $result['player_option'] = $render_option;
        //         } else {
        //             $item_template = TEMPLATE_DIR . 'player/no-item.php';
        //             $render_item = '';
        //             $render_option = '<option value="0">Select a player</option>';
        //             $render_item .= Tools::template($item_template, null);
        //             $result['players'] = $render_item;
        //             $result['player_option'] = $render_option;
        //             $result['message'] = "has no value";
        //         }
        //     } else {
        //         $result['message'] = "ERROR: status 0";
        //     }
        // }
        $database->conn->close();
    }
    echo json_encode($result);
}
?>