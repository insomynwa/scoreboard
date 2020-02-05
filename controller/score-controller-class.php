<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Score_Model_Class;

class Score_Controller_Class extends Controller_Class {

    private $connection;

    private $model;

    private $scoreboard_form_name;
    private $scoreboard_form_template_loc;

    private $root_key;
    private $form_key;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Score_Model_Class($connection);
        $this->root_key = 'scoreboard_form';
        $this->form_key = 'form';

        $this->init_templates();
    }

    /**
     * Init Template
     *
     * @return void
     */
    private function init_templates(){
        $template_loc = TEMPLATE_DIR . 'scoreboard/';
        $this->scoreboard_form_name = 'form';
        $this->scoreboard_form_template_loc = $template_loc . $this->scoreboard_form_name . '.php';
    }

    /**
     * Get Scoreboard Preview Data
     *
     * @param integer $gamemode_id Gamemode ID
     * @return array
     */
    public function get_scoreboard_preview_data($gamemode_id=0){
        if( $gamemode_id==0){
            return $this->scoreboard_preview_data_default();
        }
        return $this->model->scoreboard_preview_data($gamemode_id);
    }

    /**
     * Get Form Data
     *
     * @param integer $gamemode_id Gamemode ID
     * @return array
     */
    public function get_form_data($gamemode_id=0){
        return $this->model->form_data($gamemode_id);
    }

    /**
     * Scoreboard Preview Default Data
     *
     * @return array
     */
    private function scoreboard_preview_data_default(){
        return [
            'gamedraw_id' => 0, 'gameset_id' => 0, 'gamemode_id' => 0, 'bowstyle_id' => 0,
            'sets' => [ 'curr_set' => 'X', 'end_set' => 'Y' ],
            'contestants' => [
                0 => [
                    'logo' => 'uploads/no-image.png', 'team' => 'Team A', 'player' => 'Player A', 'score_timer' => 120,
                    'score_1' => 0, 'score_2' => 0, 'score_3' => 0, 'score_4' => 0, 'score_5' => 0, 'score_6' => 0,
                    'set_scores' => 0, 'game_scores' => 0, 'set_points' => 0, 'game_points' => 0, 'desc' => 'Description A'
                ],
                1 => [
                    'logo' => 'uploads/no-image.png', 'team' => 'Team B', 'player' => 'Player B', 'score_timer' => 120,
                    'score_1' => 0, 'score_2' => 0, 'score_3' => 0, 'score_4' => 0, 'score_5' => 0, 'score_6' => 0,
                    'set_scores' => 0, 'game_scores' => 0, 'set_points' => 0, 'game_points' => 0, 'desc' => 'Description B'
                ]
            ]
        ];
    }

    /**
     * Get Team Scores ID
     *
     * @param integer $team_id Team ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    public function get_team_scores_id($team_id=0){
        return $this->model->team_scores_id($team_id);
    }

    /**
     * Get Gamedraw Scores ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    public function get_gamedraw_scores_id($gamedraw_id=0){
        return $this->model->gamedraw_scores_id($gamedraw_id);
    }

    /**
     * Get Player Scores ID
     *
     * @param integer $player_id Player ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    public function get_player_scores_id($player_id=0){
        return $this->model->player_scores_id($player_id);
    }

    /**
     * Get Scoreboard Form
     *
     * @return array
     */
    private function get_form(){
        $res = array();

        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        $live_game_data = $live_game_oc->get_game_data_bm_id();
        if(! empty($live_game_data)){
            $live_gamemode_id = $live_game_data['gamemode_id'];
            $live_game_bowstyle_id = $live_game_data['bowstyle_id'];
            $res['data'] = $this->get_form_data($live_gamemode_id);

            $config_oc = new Config_Controller_Class($this->connection);
            $form_config = $config_oc->get_scoreboard_form_config($live_game_bowstyle_id);
            $formatted_config = array();
            foreach ($form_config as $key => $value) {
                foreach($value as $vkey => $vval ){
                    if($vkey == 'visibility_class' ){
                        $formatted_config[$key . '_vc' ] = $vval;
                    }
                    if($vkey == 'label' ){
                        $formatted_config[$key . '_label' ] = $vval;
                    }
                }
            }
            $res['config'] = $formatted_config;
        }

        return $res;
    }

    /**
     * Get Data
     *
     * @param array $req_data
     * @return array
     */
    public function get_data($req_data = array( 'form' )){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        $data = null;
        if(in_array($this->form_key,$req_data)){
            $data = $this->get_form();
            $root_res[$this->form_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
    }

    /**
     * Delete Team Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    public function delete_team_scores($scores_id=null){
        return $this->model->delete_scores($scores_id);
    }

    /**
     * Delete Player Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    public function delete_player_scores($scores_id=null){
        return $this->model->delete_scores($scores_id);
    }

    /**
     * Delete Gamedraw Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    public function delete_gamedraw_scores($scores_id=null){
        return $this->model->delete_scores($scores_id);
    }

    /**
     * Delete Gameset Scores
     *
     * @param array $scores_id Scores ID
     * @return boolean
     */
    public function delete_gameset_scores($scores_id=null){
        return $this->model->delete_scores($scores_id);
    }

    /**
     * Get Gameset Scores ID
     *
     * @param integer Gameset ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    public function get_gameset_scores_id($gameset_id){
        return $this->model->gameset_scores_id($gameset_id);
    }

    /**
     * Get Scoreboard Data
     *
     * @return array empty | [ scores ]
     */
    public function get_scoreboard_data(){
        return $this->model->scoreboard_data();
    }

    /**
     * Create Score
     *
     * @param integer $gameset_id Gameset ID
     * @param array $contestants Contestant Data
     * @return boolean
     */
    public function create_scores($gameset_id=0, $contestants=null){
        $action_om =  $this->model->create_score($gameset_id, $contestants);
        return $action_om;
    }

    /**
     * Form Action
     *
     * @param array $score_data Score Data
     * @return array result
     */
    public function form_action($score_data=null){
        $result = [ 'status' => false ];

        if( $score_data['score_id'] == 0){
            $result['message'] = 'ERROR: form_action Score ID: 0';
            return $result;
        }

        $action_m = $this->model->update($score_data);
        if(!$action_m){
            $result['message'] = 'ERROR: form_action update';
            return $result;
        }

        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($this->connection);
        $scoreboard_style_oc->set_style_id($live_game_oc->style_id());

        $scoreboard_style_data = $scoreboard_style_oc->get_data(['preview']);
        $result['status'] = true;
        $result = array_merge($result, $scoreboard_style_data);

        // $score_data = [
        //     'gamedraw_id'   => Tools::post_int($_POST[ "{$owner}gamedraw_id" ]),
        //     'gameset_id'    => Tools::post_int($_POST[ "{$owner}gameset_id" ]),
        //     'score_id'      => Tools::post_int($_POST[ "{$owner}id" ]),
        //     'timer'         => isset($_POST[ "{$owner}timer" ]) ? str_replace("s", "", $_POST[ "{$owner}timer" ]) : 0,
        //     'score_1'       => Tools::post_int($_POST[ "{$owner}score1" ]),
        //     'score_2'       => Tools::post_int($_POST[ "{$owner}score2" ]),
        //     'score_3'       => Tools::post_int($_POST[ "{$owner}score3" ]),
        //     'score_4'       => Tools::post_int($_POST[ "{$owner}score4" ]),
        //     'score_5'       => Tools::post_int($_POST[ "{$owner}score5" ]),
        //     'score_6'       => Tools::post_int($_POST[ "{$owner}score6" ]),
        //     'setpoints'     => Tools::post_int($_POST[ "{$owner}setpoints" ]),
        //     'score_desc'          => isset($_POST[ "{$owner}desc" ]) ? $_POST[ "{$owner}desc" ] : ''
        // ];
        return $result;
    }
}

if (isset($_POST['score_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'score_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();

        $owner = 'score_x_';
        if($request_value == 'update-a'){
            $owner = 'score_a_';
        }
        else if( $request_value == 'update-b'){
            $owner = 'score_b_';
        }

        $score_data = [
            'gamedraw_id'   => Tools::post_int($_POST[ "{$owner}gamedraw_id" ]),
            'gameset_id'    => Tools::post_int($_POST[ "{$owner}gameset_id" ]),
            'score_id'      => Tools::post_int($_POST[ "{$owner}id" ]),
            'timer'         => isset($_POST[ "{$owner}timer" ]) ? str_replace("s", "", $_POST[ "{$owner}timer" ]) : 0,
            'score_1'       => Tools::format_score($_POST[ "{$owner}score1" ]),
            'score_2'       => Tools::format_score($_POST[ "{$owner}score2" ]),
            'score_3'       => Tools::format_score($_POST[ "{$owner}score3" ]),
            'score_4'       => Tools::format_score($_POST[ "{$owner}score4" ]),
            'score_5'       => Tools::format_score($_POST[ "{$owner}score5" ]),
            'score_6'       => Tools::format_score($_POST[ "{$owner}score6" ]),
            'setpoints'     => Tools::post_int($_POST[ "{$owner}setpoints" ]),
            'score_desc'    => isset($_POST[ "{$owner}desc" ]) ? $_POST[ "{$owner}desc" ] : ''
        ];

        $score_oc = new Score_Controller_Class($connection);
        $result = $score_oc->form_action($score_data);

        $database->conn->close();
    }
    echo json_encode($result);
}

// Update Timer
if (isset($_POST['score_timer_action'])) {
    $result = array(
        'status' => false,
        'message' => '',
    );
    if ($_POST['score_timer_action'] != '') {
        $score_id = 0;
        $timer = 120;
        if ($_POST['score_timer_action'] == 'update-timer-a') {

            $score_id = isset($_POST['score_a_id']) ? $_POST['score_a_id'] : 0;
            $timer = isset($_POST['timer_a']) ? $_POST['timer_a'] : 0;

        } else if ($_POST['score_timer_action'] == 'update-timer-b') {

            $score_id = isset($_POST['score_b_id']) ? $_POST['score_b_id'] : 0;
            $timer = isset($_POST['timer_b']) ? $_POST['timer_b'] : 0;
        }

        $database = new Database();
        $db = $database->getConnection();

        $score = new Score_Model_Class($db);
        $score->SetID($score_id);
        $score->SetTimer($timer);
        $tempRes = $score->UpdateScoreTimer();

        if ($tempRes['status']) {
            $result['status'] = true;
        } else {
            $result['message'] = "ERROR: Update Score Timer";
        }

        $database->conn->close();
    }
    echo json_encode($result);
}

?>