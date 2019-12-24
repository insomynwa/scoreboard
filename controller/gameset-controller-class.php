<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gameset_Model_Class;

class Gameset_Controller_Class extends Controller_Class {
    private $connection;

    private $model;

    private $json_key;
    private $table_json_key;
    private $summary_json_key;

    private $item_template_name;
    private $item_template_loc;

    private $no_item_template_name;
    private $no_item_template_loc;

    private $summary_template_name;
    private $summary_template_loc;

    private $id;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Gameset_Model_Class($connection);
        $this->init_json_key();
        $this->init_templates();
    }

    /**
     * Init JSON key
     *
     * @return void
     */
    private function init_json_key() {
        $this->json_key = 'gameset';
        $this->table_json_key = 'table';
        $this->summary_json_key = 'summary';
    }

    /**
     * Init Templates
     *
     * @return void
     */
    private function init_templates() {
        $template_loc = TEMPLATE_DIR . 'gameset/';
        $this->item_template_name = 'item';
        $this->item_template_loc = $template_loc . $this->item_template_name . '.php';
        $this->no_item_template_name = 'no-item';
        $this->no_item_template_loc = $template_loc . $this->no_item_template_name . '.php';
        $this->summary_template_name = 'summary';
        $this->summary_template_loc = $template_loc . $this->summary_template_name . '.php';
    }

    /**
     * Set ID
     *
     * @param integer $gameset_id Gameset ID
     * @return instance
     */
    public function set_id($gameset_id){
        $this->id = $gameset_id;
        return $this;
    }

    /**
     * Get Team Gamesets ID
     *
     * @param integer $team_id Team ID
     * @return array [gameset_id1, gameset_id2, gameset_id..n]
     */
    public function get_team_gamesets_id($team_id=0){
        return $this->model->team_gamesets_id($team_id);
    }

    /**
     * Get Player Gamesets ID
     *
     * @param integer $player_id Player ID
     * @return array [gameset_id1, gameset_id2, gameset_id..n]
     */
    public function get_player_gamesets_id($player_id=0){
        return $this->model->player_gamesets_id($player_id);
    }

    /**
     * Delete Team Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    public function delete_team_gamesets($gamesets_id=null){
        return $this->model->delete_gamesets($gamesets_id);
    }

    /**
     * Delete Player Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    public function delete_player_gamesets($gamesets_id=null){
        return $this->model->delete_gamesets($gamesets_id);
    }

    /**
     * Delete Gamedraw Gamesets
     *
     * @param array $gamesets_id Gamesets ID
     * @return boolean
     */
    public function delete_gamedraw_gamesets($gamesets_id=null){
        return $this->model->delete_gamesets($gamesets_id);
    }

    /**
     * Get Scores ID
     *
     * @param integer $gameset_id Gameset ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    private function get_scores_id($gameset_id=0){
        if ($gameset_id > 0) {
            $score_oc = new Score_Controller_Class($this->connection);
            return $score_oc->get_gameset_scores_id($gameset_id);
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
        return $score_oc->delete_gameset_scores($scores_id);
    }

    /**
     * Delete Gameset
     *
     * @param array|integer Gameset ID
     * @return boolean|array result
     */
    public function delete_gameset($gameset_id){
        $result = [
            'status' => false,
            'action' => 'delete'
        ];

        if( $gameset_id == 0) return $result;

        $scores_id = $this->get_scores_id($gameset_id);

        if (!empty($scores_id)) {
            if (!$this->delete_scores($scores_id)) {
                $result['message'] = 'ERROR: delete_gameset delete_scores';
                return $result;
            }
        }

        if (!$this->model->delete_gameset($gameset_id)) {
            $result['message'] = 'ERROR: delete_gameset';
            return $result;
        }

        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        if ($live_game_oc->is_gameset_live($gameset_id)) {
            if (!$live_game_oc->set_live_game(0)) {
                $result['message'] = 'ERROR: delete_gameset set_live_game';
                return $result;
            }
        }
        $result['status'] = true;

        return $result;
    }

    /**
     * Get Gamedraw Gamesets ID
     *
     * @param array|integer Gamedraw ID
     * @return array (gameset_id1, gameset_id2, gameset_id..n)
     */
    public function get_gamedraw_gamesets_id($gamedraws_id){
        return $this->model->gamedraw_gamesets_id($gamedraws_id);
    }

    /**
     * Get Modal Data
     *
     * @param integer $gameset_id Gameset ID
     * @return array status, gameset [ gameset_id, gamedraw_id, gameset_num, gameset_status ]
     */
    public function get_modal_form_data($gameset_id=0) {
        $result = [
            'status' => false
        ];
        if( $gameset_id == 0 ){
            $result['message'] = 'ERROR: get_modal_form_data Gameset ID: 0';
            return $result;
        }
        $form_data = $this->model->modal_form_data($gameset_id);
        if(empty($form_data)){
            $result['message'] = 'ERROR: get_modal_form_data Empty Data';
            return $result;
        }
        $result['status'] = true;
        $result[$this->json_key] = $form_data;

        return $result;
    }

    /**
     * Create Gameset Table
     *
     * @param array $list Gameset Data
     * @param string $table_key JSON Key
     * @return string
     */
    private function create_gameset_table($list = null, $table_key = '') {
        $gameset_list = is_null($list) ? $this->model->get_list() : $list;
        $key = $table_key != '' ? $table_key : $this->table_json_key;
        return Tools::create_loop_element(
            $gameset_list,
            'gamesets',
            $key,
            $this->item_template_loc
        );
    }

    /**
     * Get Gameset Element
     *
     *
     * @return array
     */
    public function get_gameset_elements() {
        $result = [
            'status' => true,
        ];
        $gameset_data = $this->model->get_list();
        $result['gameset']['table'] = $this->create_gameset_table($gameset_data, 'table')['table'];
        if ($result['gameset']['table'] == '') {
            $result['gameset']['table'] = Tools::template($this->no_item_template_loc, null);
        }
        return $result;
    }

    /**
     * Get New Num
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array result[]
     */
    public function get_new_num($gamedraw_id=0){
        $result = [
            'status' => true
        ];
        if($gamedraw_id==0) return $result;

        $result['new_set'] = $this->model->last_set($gamedraw_id) + 1;

        return $result;
    }

    /**
     * Get Bowstyle ID
     *
     * @param integer $gameset_id Gameset ID
     * @return boolean|integer false | bowstyle_id
     */
    public function get_bowstyle_id($gameset_id=0){
        if( $gameset_id == 0) return false;
        return $this->model->bowstyle_id($gameset_id);
    }

    /**
     * Update Status:
     * Standby: 1,
     * Live: 2,
     * Finish: 3,
     *
     * @param integer $gameset_id Gameset ID
     * @param integer $status Status ID
     * @return boolean
     */
    public function set_update_status($gameset_id=0, $status=1){
        if( $gameset_id == 0 ) return false;
        return $this->model->update_status($gameset_id, $status);
    }

    /**
     * Gameset Action
     *
     * @param string $request_value Request Value
     * @param array $data Gameset Data
     * @return array [status,action,*message]
     */
    public function form_action($request_value = '', $data = null){
        $result = [
            'status' => false,
            'action' => $request_value
        ];

        // $gameset_data = array(
        //     'id' => $gameset_id,
        //     'gamedraw_id' => 0,
        //     'num' => 1,
        //     'status_id' => 1,
        // );
        if( $request_value == '' || is_null($data) ) return $result;

        if( $request_value == 'create' ) {
            // create gameset
            // create score
            $new_id = $this->model->create_gameset($data, true);
            if($new_id==0){
                $result['message'] = 'ERROR: form_action create_gameset';
                return $result;
            }
            $gamedraw_oc = new Gamedraw_Controller_Class($this->connection);
            $contestants = $gamedraw_oc->get_contestants_id($data['gamedraw_id']);
            if( empty($contestants)){
                $result['message'] = 'ERROR: form_action get_contestants_id';
                return $result;
            }
            $score_oc = new Score_Controller_Class($this->connection);
            $result['status'] = $score_oc->create_scores($new_id, $contestants);
            if(!$result['status']) {
                $result['message'] = 'ERROR: form_action create_scores';
            }

        }else if( $request_value == 'update') {
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: form_action Gameset ID: 0';
                return $result;
            }
            if(!$this->model->update_gameset($data)) {
                $result['message'] = 'ERROR: form_action update_player';
                return $result;
            }

            $live_game_oc = new Live_Game_Controller_Class($this->connection);
            if( $data['status_id'] == 2) {
                if (! $live_game_oc->is_gameset_live($data['id'])) {
                    // $prev_live_game_id = $live_game_oc->get_live_gameset_id();
                    // if( !$live_game_oc->set_live_game($data['id'])){
                    //     $result['message'] = 'ERROR: form_action set_live_game';
                    //     return $result;
                    // }
                    // if( $prev_live_game_id > 0){
                    //     if( ! $this->set_update_status($prev_live_game_id, 1)) {
                    //         $result['message'] = 'ERROR: form_action set_update_status';
                    //         return $result;
                    //     }
                    // }
                    $live_game_oc->start_game($data['id']);
                }
            }else{
                if ( $live_game_oc->is_gameset_live($data['id'])) {
                    // if( !$live_game_oc->set_live_game(0)){
                    //     $result['message'] = 'ERROR: form_action set_live_game';
                    //     return $result;
                    // }
                    // if( ! $this->set_update_status($data['id'], $data['status_id'])) {
                    //     $result['message'] = 'ERROR: form_action set_update_status';
                    //     return $result;
                    // }
                    $live_game_oc->stop_game($data['id']);
                }
            }
            $result['status'] = true;

        }else if($request_value == 'delete'){
            if( $data['id'] == 0 ) {
                $result['message'] = 'ERROR: form_action delete_player';
                return $result;
            }
            $result = $this->delete_gameset($data['id']);
        }

        return $result;
    }

    /**
     * Render Summary Element
     *
     * @return string
     */
    private function render_summary_element(){

        // contestant name,score1-3,setpoint,setscore
        $summary_element = '';
        $summary_data = $this->model->summary_data($this->id);
        if(!empty($summary_data)){
            $summary_element = Tools::template($this->summary_template_loc, $summary_data);
        }
        return $summary_element;
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
        if (in_array($this->summary_json_key, $elements)){
            $result[$parent_key][$this->summary_json_key] = $this->render_summary_element();
        }
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
            if ($element_type == $this->table_json_key) {
                $key = $this->table_json_key;
                $template_loc = $this->item_template_loc;
            }
        }
        $element_value = Tools::create_loop_element(
            $data_list, 'gamesets', $key, $template_loc, $element_pretext, $selected_item
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

if (isset($_GET['gameset_get'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'gameset_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();

        if ( $request_value == 'modal_data' ){
            if(isset($_GET['id'])){
                $gameset_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $gameset_oc = new Gameset_Controller_Class($connection);

                $result = $gameset_oc->get_modal_form_data($gameset_id);
            }
        }else if ($request_value == 'new') {
            $gameset_oc = new Gameset_Controller_Class($connection);
            $gameset_element = $gameset_oc->get_elements(['table'],'',0,true);
            $result = array_merge($result, $gameset_element);
        }
        else if ($request_value == 'new_num' ) {

            if(isset($_GET['gamedraw_id'])){
                $gamedraw_id = is_numeric($_GET['gamedraw_id']) ? $_GET['gamedraw_id'] : 0;
                $gameset_oc = new Gameset_Controller_Class($connection);
                $result = $gameset_oc->get_new_num($gamedraw_id);
            }

        }else if ($request_value == 'summary'){

            if(isset($_GET['id'])){
                $gameset_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $gameset_oc = new Gameset_Controller_Class($connection);
                $gameset_oc->set_id($gameset_id);
                $gameset_element = $gameset_oc->get_elements(['summary'],'','',true);
                $result = array_merge($result, $gameset_element);
            }
        }
        $database->conn->close();
    }
    echo json_encode($result);
}
// Create Game Set
if (isset($_POST['gameset_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'gameset_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        $gameset_oc = new Gameset_Controller_Class($connection);

        $gameset_id = 0;
        if( $request_value == 'update' || $request_value == 'delete' ){
            if( isset($_POST['gameset_id'])) {
                $gameset_id = is_numeric($_POST['gameset_id']) ? $_POST['gameset_id'] : $gameset_id;
            }
        }

        $gameset_data = array(
            'id' => $gameset_id,
            'gamedraw_id' => 0,
            'num' => 1,
            'status_id' => 1,
        );
        if($request_value == 'create' || $request_value == 'update'){
            $gameset_data['gamedraw_id'] = is_numeric($_POST['gameset_gamedraw']) ? $_POST['gameset_gamedraw'] : 0;
            $gameset_data['num'] = is_numeric($_POST['gameset_setnum']) ? $_POST['gameset_setnum'] : 1;
        }
        if($request_value == 'update'){
            $gameset_data['status_id'] = is_numeric($_POST['gameset_status']) ? $_POST['gameset_status'] : 1;
        }

        $result = $gameset_oc->form_action($request_value, $gameset_data);

        $database->conn->close();

    }
    echo json_encode($result);
}

// Get Game Set Last Num
/* if (isset( $_GET['GetGameSetLastNum']) && $_GET['GetGameSetLastNum'] != '') {
$result = array(
'status'    => false,
'message'   => ''
);
$gamedraw_id = is_numeric($_GET['GetGameSetLastNum']) ? $_GET['GetGameSetLastNum'] : 0;

$database = new Database();
$db = $database->getConnection();

$gameset = new Gameset_Model_Class($db);
$gameset->SetGameDrawID($gamedraw_id);
$res = $gameset->GetLastNum();
if($res['status']){
$result['status'] = true;
$result['has_value'] = $res['has_value'];
if($res['has_value']){
$result['next_num'] = $res['last_num'] + 1;
}
}

$database->conn->close();

echo json_encode($result);
} */

// Get Game Set by Game Draw
/* if (isset( $_GET['GetGameSetsByGameDraw']) && $_GET['GetGameSetsByGameDraw'] != '') {
$result = array(
'status'    => false,
'message'   => ''
);
$gamedraw_id = isset($_GET['GetGameSetsByGameDraw']) ? $_GET['GetGameSetsByGameDraw'] : 0;
if($gamedraw_id > 0){

$database = new Database();
$db = $database->getConnection();

$gameset = new Gameset_Model_Class($db);
$gameset->SetGameDrawID($gamedraw_id);
$tempRes = $gameset->GetGameSetsByGameDraw();

if( $tempRes['status'] ){
$result['status'] = $tempRes['status'];
$result['gamesets'] = $tempRes['gamesets'];
}else{
$result['message'] = "ERROR: Load Game Set";
}
$database->conn->close();
}else{
$result['message'] = "ERROR: Game Draw ID = 0";
}
echo json_encode($result);
} */

?>