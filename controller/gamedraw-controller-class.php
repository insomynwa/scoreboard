<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gamedraw_Model_Class;

class Gamedraw_Controller_Class extends Controller_Class{
    private $connection;

    private $model;

    private $root_key;
    private $option_key;
    private $table_key;
    private $summary_key;
    private $modal_form_key;

    private $option_template_name;
    private $option_template_loc;

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
    public function __construct($connection=null)
    {
        $this->connection = $connection;
        $this->model = new Gamedraw_Model_Class($connection);
        $this->init_json_key();
        $this->init_templates();
    }

    /**
     * Init Json Key
     *
     * @return void
     */
    private function init_json_key(){
        $this->root_key = 'gamedraw';
        $this->option_key = 'option';
        $this->table_key = 'table';
        $this->summary_key = 'summary';
        $this->modal_form_key = 'modal_form';
    }

    /**
     * Init Templates
     *
     * @return void
     */
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
     * Set Gamedraw ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return void
     */
    public function set_id($gamedraw_id=0){
        $this->id = $gamedraw_id;
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
     * @return array integer
     */
    private function get_new_number(){
        return $this->model->new_number();
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
     * @return array empty | [ id, num, bowstyle_id, gamemode_id, contestant_a_id, contestant_b_id ]
     */
    private function get_modal_form_data() {
        return $this->model->modal_form_data($this->id);
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
     * Get Table Data
     *
     * @return array
     */
    private function get_table_data(){
        return $this->model->table_data();
    }

    /**
     * Get Option Data
     *
     * @return array empty | [ 'gamedraws' ]
     */
    private function get_option_data(){
        return $this->model->option_data();
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
     * @param array $req_data [ 'option', 'table', 'summary', 'modal_form', 'new_num' ]
     * @return array
     */
    public function get_data( $req_data = array( 'option', 'table', 'summary', 'modal_form', 'new_num' ) ) {
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        $data = null;
        if (in_array($this->table_key, $req_data)) {
            // id, num, bowstyle_name, gamemode_name, contestant_a_name, contestant_b_name
            $data = is_null($data) ? $this->get_table_data() : $data;
            $root_res[$this->table_key] = $data;
        }

        if (in_array($this->option_key, $req_data)) {
            // id, num, contestant_a_name, contestant_b_name
            $data = is_null($data) ? $this->get_option_data() : $data;
            $root_res[$this->option_key] = $data;
        }

        if (in_array($this->summary_key, $req_data)) {
            $data = $this->get_summary_data();
            $root_res[$this->summary_key] = $data;
        }

        if (in_array($this->modal_form_key, $req_data)) {
            $data = $this->get_modal_form_data();
            $root_res[$this->modal_form_key] = $data;
        }

        if (in_array('new_num', $req_data)) {
            $data = $this->get_new_number();
            $root_res['new_num'] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
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
        $gamedraw_oc = new Gamedraw_Controller_Class($connection);
        $gamedraw_data = array();

        if ( $request_value == 'new_num'){
            $gamedraw_data = $gamedraw_oc->get_data(['new_num']);

        }
        else if ( $request_value == 'modal_data' ){
            if(isset($_GET['id'])){
                $gamedraw_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                $gamedraw_oc->set_id($gamedraw_id);
                $gamedraw_data = $gamedraw_oc->get_data(['modal_form']);
            }
        }
        else if( $request_value == 'summary' && isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0){
            if(isset($_GET['id'])){
                $gamedraw_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;

                $gamedraw_oc->set_id($gamedraw_id);
                $gamedraw_data = $gamedraw_oc->get_data(['summary']);
            }
        }else if ($request_value == 'new') {
            $gamedraw_data = $gamedraw_oc->get_data(['table','option']);
        }
        $result = array_merge($result, $gamedraw_data);
        $database->conn->close();
    }
    echo json_encode($result);
}

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