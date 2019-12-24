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

    private $json_key;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Score_Model_Class($connection);
        $this->json_key = 'scoreboard_form';
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
     * Get Scoreboard Data
     *
     * @param integer $game_bowstyle_id
     * @return mixed null | array
     */
    public function get_scoreboard_form_data($game_bowstyle_id=0){
        $data = $this->model->scoreboard_form_data();
        $livegame_data = null;
        if ($data) {
            $scores = $data['scores'];
            $livegame_data = array();
            $contestant_oc = null;
            if($scores['gamemode_id'] == 1){
                $contestant_oc = new Team_Controller_Class($this->connection);
            }else if($scores['gamemode_id'] == 2){
                $contestant_oc = new Player_Controller_Class($this->connection);
            }

            for ($i = 0; $i < sizeof($scores['contestants']); $i++) {
                $scores['contestants'][$i]['logo'] = 'uploads/no-image.png';
                $scores['contestants'][$i]['team'] = '';
                $scores['contestants'][$i]['player'] = '';
                $scoreboard_form_data = $contestant_oc->get_scoreboard_form_data($scores['contestants'][$i]['id']);
                if( $scoreboard_form_data ){
                    $scores['contestants'][$i]['logo'] = 'uploads/' . $scoreboard_form_data['team_logo'];
                    $scores['contestants'][$i]['team'] = $scoreboard_form_data['team_name'];
                    if( $scores['gamemode_id'] == 1 ){
                        $scores['contestants'][$i]['player'] = '';
                    }else if( $scores['gamemode_id'] == 2){
                        $scores['contestants'][$i]['player'] = $scoreboard_form_data['player_name'];
                    }
                }
            }
            $livegame_data['gamedraw_id'] = $scores['gamedraw_id'];
            $livegame_data['gameset_id'] = $scores['gameset_id'];
            $livegame_data['gamemode_id'] = $scores['gamemode_id'];
            $livegame_data['sets'] = $scores['sets'];
            $livegame_data['contestants'] = $scores['contestants'];
            $livegame_data['bowstyle_id'] = $game_bowstyle_id;
        }
        return $livegame_data;
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
        $form = '<h4 class="text-gray-4 text-center font-weight-light">Start Game</h4>';
        $live_game_oc = new Live_Game_Controller_Class($this->connection);
        if( ! $live_game_oc->has_live_game() ){
            return $form;
        }

        $live_game_bowstyle_id = $live_game_oc->get_game_bowstyle_id();
        $data = $this->get_scoreboard_form_data($live_game_bowstyle_id);
        if(is_null($data)){
            return $form;
        }
        $config_oc = new Config_Controller_Class($this->connection);
        $data['style_config'] = $config_oc->get_scoreboard_form_config($live_game_bowstyle_id);

        $form = Tools::template( $this->scoreboard_form_template_loc, $data );

        return $form;
    }

    /**
     * Get Elements
     * 'scoreboard_form'
     *
     * @param array $elements Array Element
     * @return array
     */
    public function get_elements($elements=array()){
        $result = array();
        if(in_array('scoreboard_form',$elements)){
            $result['scoreboard_form'] = $this->get_form();
        }
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
        $action_om =  $this->model->create_score($gameset_id, $contestants['contestant_a_id']);
        $action_om =  $this->model->create_score($gameset_id, $contestants['contestant_b_id']) && $action_om;
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

        $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($this->connection);
        $live_game_oc = new Live_Game_Controller_Class($this->connection);

        $scoreboard_style_element = $scoreboard_style_oc->get_elements(['preview'],0,$live_game_oc->style_id());
        $result['status'] = true;
        $result = array_merge($result, $scoreboard_style_element);

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
            'score_1'       => Tools::post_int($_POST[ "{$owner}score1" ]),
            'score_2'       => Tools::post_int($_POST[ "{$owner}score2" ]),
            'score_3'       => Tools::post_int($_POST[ "{$owner}score3" ]),
            'score_4'       => Tools::post_int($_POST[ "{$owner}score4" ]),
            'score_5'       => Tools::post_int($_POST[ "{$owner}score5" ]),
            'score_6'       => Tools::post_int($_POST[ "{$owner}score6" ]),
            'setpoints'     => Tools::post_int($_POST[ "{$owner}setpoints" ]),
            'score_desc'          => isset($_POST[ "{$owner}desc" ]) ? $_POST[ "{$owner}desc" ] : ''
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