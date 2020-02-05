<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Gameset_Model_Class;
use scoreboard\model\Live_Game_Model_Class;
use scoreboard\model\Player_Model_Class;
use scoreboard\model\Score_Model_Class;
use scoreboard\model\Team_Model_Class;

class Live_Game_Controller_Class extends Controller_Class {

    private $connection;
    private $model = null;

    private $live_template_name = '';
    private $live_template_loc = '';

    private $has_live_game;
    private $has_live_style;

    /**
     * Class Constructor
     *
     * @param obj $connection
     * @return void
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Live_Game_Model_Class($connection);
        $this->init_templates();
    }

    /**
     * Get Live Gameset ID
     *
     * @return integer
     */
    public function get_gameset_id() {
        return $this->model->gameset_id();
    }

    /**
     * Has Live Game
     *
     * @return boolean
     */
    public function has_live_game(){
        return $this->model->gameset_id(true);
    }

    private function init_templates() {
        $template_loc = TEMPLATE_DIR . 'scoreboard/';
        $this->live_template_name = 'live';
        $this->live_template_loc = $template_loc . $this->live_template_name . '.php';

    }

    /**
     * Get Live Game Bowstyle ID
     *
     * @return integer bowstyle ID
     */
    public function get_game_bowstyle_id() {

        return $this->model->game_bowstyle_id();
    }

    /**
     * Get Game Data (Bowstyle ID & Gamemode ID)
     *
     * @return array
     */
    public function get_game_data_bm_id(){
        return $this->model->game_data_bm_id();
    }

    /**
     * Get Live Style Bowstyle ID
     *
     * @return integer bowstyle ID
     */
    public function style_bowstyle_id(){
        $action_m = $this->model->get_style_bowstyle_id();
        $this->has_live_style = $action_m > 0;

        return $action_m;
    }

    /**
     * Get Live Style Bowstyle ID
     *
     * @return integer bowstyle ID
     */
    public function get_style_bowstyle_id(){
        $action_m = $this->model->get_style_bowstyle_id();
        $this->has_live_style = $action_m > 0;

        return $action_m;
    }

    /**
     * Get Live Style ID
     *
     * @return integer
     */
    public function style_id(){
        $action_m = $this->model->get_style_id();
        $this->has_live_style = $action_m > 0;

        return $action_m;
    }

    /**
     * Remove Live Style
     *
     * @return void
     */
    public function remove_live_style(){
        $this->model->clean_style();
    }

    /**
     * Set Live Game
     *
     * @return void
     */
    public function set_live_game($gameset_id=0){
        return $this->model->set_live_game($gameset_id);
    }

    /**
     * Set Live Style
     *
     * @param integer $style_id
     * @return array
     */
    public function set_live_style($style_id=0){
        $result = [ 'status' => false];
        if( $style_id==0 ) {
            $result['message'] = 'ERROR: Style ID : 0';
            return $result;
        }
        $live_game_bowstyle_id = $this->get_game_bowstyle_id();
        $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($this->connection);
        if( ($live_game_bowstyle_id != 0) && ($live_game_bowstyle_id != $scoreboard_style_oc->get_bowstyle_id($style_id))){
            $result['message'] = 'Wrong Style for the Game!';
            return $result;
        }

        return [ 'status' => $this->model->set_style($style_id) ];
    }

    /**
     * Set Live Style
     *
     * @param integer $style_id Style ID
     * @return boolean
     */
    public function set_style($style_id=0){

        return $this->model->set_style($style_id);
    }

    /**
     * Create Default Live Game
     *
     * @return boolean
     */
    public function create_default() {

        if (!$this->model->has_default()) {
            $default_data = [
                'id' => 1,
                'gameset_id' => 0,
                'style_id' => 0,
            ];
            $this->model->create_default($default_data);
        }

        return true;
    }

    /**
     * Check if Gameset is live
     *
     * @param array|integer Gameset ID
     * @return boolean
     */
    public function is_gameset_live($gameset_id){
        if(is_array($gameset_id)){
            if(empty($gameset_id)) return false;
            return in_array($this->get_gameset_id(),$gameset_id);
        }
        else if( is_numeric($gameset_id) || is_int($gameset_id) ){
            if( $gameset_id == 0 ) return false;
            return ($gameset_id == $this->get_gameset_id());
        }
        return false;
    }

    /**
     * Start Game
     *
     * @param integer $gameset_id Gameset ID
     * @return array result
     */
    public function start_game($gameset_id=0){
        $result = [ 'status' => false ];
        if( $gameset_id==0) return $result;

        // var_dump($gameset_id);die;
        $prev_live_gameset_id = $this->get_gameset_id();
        $gameset_oc = new Gameset_Controller_Class($this->connection);
        if($prev_live_gameset_id != 0){
            if( ! $gameset_oc->set_update_status($prev_live_gameset_id, 1)) {
                $result['message'] = 'ERROR: start_game set_update_status';
                return $result;
            }
        }
        if( ! $gameset_oc->set_update_status($gameset_id, 2)) {
            $result['message'] = 'ERROR: start_game set_update_status';
            return $result;
        }
        if( !$this->set_live_game($gameset_id)){
            $result['message'] = 'ERROR: start_game set_live_game';
            return $result;
        }
        $new_game_bowstyle_id = $gameset_oc->get_bowstyle_id($gameset_id);
        if( !$new_game_bowstyle_id ){
            $result['message'] = 'ERROR: start_game get_bowstyle_id';
            return $result;
        }
        $prev_style_bowstyle_id = $this->get_style_bowstyle_id();
        if( $new_game_bowstyle_id != $prev_style_bowstyle_id){
            if (!$this->set_style(0)) {
                $result['message'] = 'ERROR: start_game set_style';
                return $result;
            }
        }
        $result['status'] = true;
        return $result;

    }

    /**
     * Stop live Game
     *
     * @param integer $gameset_id Gameset ID
     * @return array result
     */
    public function stop_game($gameset_id = 0){
        $result = [ 'status' => false ];
        if( $gameset_id==0) return $result;

        $gameset_oc = new Gameset_Controller_Class($this->connection);
        if( ! $gameset_oc->set_update_status($gameset_id, 1)) {
            $result['message'] = 'ERROR: start_game set_update_status';
            return $result;
        }
        if( !$this->set_live_game(0)){
            $result['message'] = 'ERROR: start_game set_live_game';
            return $result;
        }

        $result['status'] = true;
        return $result;
    }

    /**
     * Get Scoreboard
     *
     * @return array
     */
    public function get_scoreboard(){
        $result = [ 'status' => false ];

        $gameset_id = $this->get_gameset_id();

        if( $gameset_id == 0){
            $result['message'] = 'No Live Game';
            return $result;
        }

        if($this->style_id() == 0) {
            $result['message'] = 'No live Style';
            return $result;
        }

        $score_oc = new Score_Controller_Class($this->connection);
        $scoreboard_data = $score_oc->get_scoreboard_data();

        if( ! empty($scoreboard_data )) {
            $scores = $scoreboard_data['scores'];
            $gamemode_id = $scores['gamemode_id'];
            $contestant_oc = null;
            if( $gamemode_id == 1){
                $contestant_oc = new Team_Controller_Class($this->connection);
            }
            else if( $gamemode_id == 2) {
                $contestant_oc = new Player_Controller_Class($this->connection);
            }

            for ($i=0; $i < sizeof($scores['contestants']); $i++){
                $scores['contestants'][$i]['logo'] = 'uploads/no-image.png';
                $scores['contestants'][$i]['team'] = '-';
                $scores['contestants'][$i]['player'] = '-';
                $scoreboard_form_data = $contestant_oc->get_scoreboard_form_data($scores['contestants'][$i]['id']);
                if( $scoreboard_form_data ){
                    $scores['contestants'][$i]['logo'] = 'uploads/' . $scoreboard_form_data['team_logo'];
                    $scores['contestants'][$i]['team'] = $scoreboard_form_data['team_name'];
                    if( $scores['gamemode_id'] == 1 ){
                        $scores['contestants'][$i]['player'] = '-';
                    }else if( $scores['gamemode_id'] == 2){
                        $scores['contestants'][$i]['player'] = $scoreboard_form_data['player_name'];
                    }
                }
            }
            $data_scores['gamemode_id'] = $gamemode_id;
            $data_scores['bowstyle_id'] = $scores['bowstyle_id'];
            $data_scores['sets'] = $scores['sets'];
            $data_scores['contestants'] = $scores['contestants'];
            $data_scores['style_config'] = json_decode($scores['style_config'], true);
            $style_config = '';
            $style_config .= Tools::template($this->live_template_loc, $data_scores);
            $result['style_config'] = $style_config;
        }
        $result['status'] = true;

        return $result;
    }

}

if (isset($_POST['livegame_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'livegame_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        $live_game_oc = new Live_Game_Controller_Class($connection);

        $gameset_id = 0;
        $timer = 120;
        if( isset($_POST['gamesetid']) ){
            $gameset_id = is_numeric($_POST['gamesetid']) ? $_POST['gamesetid'] : 0;
        }

        if ($request_value == 'stop-live-game') {

            $result = $live_game_oc->stop_game($gameset_id);

        } else if ($request_value == 'set-live-game') {

            $result = $live_game_oc->start_game($gameset_id);
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

if (isset($_GET['livegame_get'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'livegame_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        $live_game_oc = new Live_Game_Controller_Class($connection);
        if($request_value == 'scoreboard'){
            $result = $live_game_oc->get_scoreboard();
        }
        $database->conn->close();
    }
    echo json_encode($result);
}
?>