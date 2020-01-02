<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gameset_Model_Class;

class Gameset_Controller_Class extends Controller_Class {
    private $connection;

    private $model;

    private $root_key;
    private $table_key;
    private $summary_key;
    private $modal_form_key;

    private $item_template_name;
    private $item_template_loc;

    private $no_item_template_name;
    private $no_item_template_loc;

    private $summary_template_name;
    private $summary_template_loc;

    private $id;
    private $gamedraw_id;

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
        $this->root_key = 'gameset';
        $this->table_key = 'table';
        $this->summary_key = 'summary';
        $this->modal_form_key = 'modal_form';
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
     * @return void
     */
    public function set_id($gameset_id){
        $this->id = $gameset_id;
    }

    /**
     * Set Gamedraw ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return void
     */
    public function set_gamedraw_id($gamedraw_id = 0){
        $this->gamedraw_id = $gamedraw_id;
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
    public function get_modal_form_data() {

        return $this->model->modal_form_data($this->id);
    }

    /**
     * Get New Num
     *
     *
     * @return integer
     */
    public function get_new_num(){

        return $this->model->last_set($this->gamedraw_id) + 1;
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
                    $live_game_oc->start_game($data['id']);
                }
            }else{
                if ( $live_game_oc->is_gameset_live($data['id'])) {
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
     * Get Table Data
     *
     * @return array
     */
    private function get_table_data(){
        return $this->model->table_data();
    }

    /**
     * Get Summary Data
     *
     * @return null
     */
    private function get_summary_data(){
        return $this->model->summary_data($this->id);
    }

    /**
     * Get Data
     *
     * @param array $req_data [ 'table', 'summary' ]
     * @return array
     */
    public function get_data($req_data = array( 'table', 'summary', 'modal_form', 'new_num' )) {
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        $data = null;

        if (in_array($this->table_key, $req_data)) {
            $data = is_null($data) ? $this->get_table_data() : $data;
            $root_res[$this->table_key] = $data;
        }
        if (in_array($this->summary_key, $req_data)){
            $data = $this->get_summary_data();
            $root_res[$this->summary_key] = $data;
        }
        if (in_array('new_num', $req_data)) {
            $data = $this->get_new_num();
            $root_res['new_num'] = $data;
        }

        if (in_array($this->modal_form_key, $req_data)) {
            $data = $this->get_modal_form_data();
            $root_res[$this->modal_form_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
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
        $gameset_oc = new Gameset_Controller_Class($connection);
        $gameset_data = array();

        if ( $request_value == 'modal_data' ){
            if(isset($_GET['id'])){
                $gameset_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $gameset_oc->set_id($gameset_id);
                $gameset_data = $gameset_oc->get_data(['modal_form']);
            }
        }else if ($request_value == 'new') {
            $gameset_data = $gameset_oc->get_data(['table']);
        }
        else if ($request_value == 'new_num' ) {

            if(isset($_GET['gamedraw_id'])){
                $gamedraw_id = is_numeric($_GET['gamedraw_id']) ? $_GET['gamedraw_id'] : 0;
                $gameset_oc->set_gamedraw_id($gamedraw_id);
                $gameset_data = $gameset_oc->get_data(['new_num']);
            }

        }else if ($request_value == 'summary'){

            if(isset($_GET['id'])){
                $gameset_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $gameset_oc->set_id($gameset_id);
                $gameset_data = $gameset_oc->get_data(['summary']);
            }
        }
        $result = array_merge($result, $gameset_data);
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