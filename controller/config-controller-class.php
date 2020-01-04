<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\model\Config_Model_Class;
use \scoreboard\includes\Tools;

class Config_Controller_Class extends Controller_Class {

    private $connection;
    private $model;

    /**
     * Config Controller Class Construct
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Config_Model_Class($connection);
    }

    /**
     * Create Default Config
     *
     * @return boolean
     */
    public function create_default() {

        if (!$this->model->has_default()) {
            $default_data = [
                'id' => 1,
                'name' => 'form_scoreboard',
                'value' => json_encode(Tools::get_default_scoreboard_form_style_config()),
            ];
            $this->model->create_default($default_data);

            $default_data = [
                'id' => 2,
                'name' => 'live_scoreboard_time_interval',
                'value' => 500,
            ];
            $this->model->create_default($default_data);
        }

        return true;
    }

    /**
     * Get Init Config
     *
     * @return array result [ status, *json_key]
     */
    public function get_init(){
        $result = ['status'=>true];

        $live_game_oc = new Live_Game_Controller_Class($this->connection);

        $live_style_id = $live_game_oc->style_id();
        $bowstyle_id = 0;
        $live_style_bowstyle_id = $live_game_oc->get_style_bowstyle_id();
        if($live_style_bowstyle_id > 0) {
            $bowstyle_id = $live_style_bowstyle_id;
        }else{
            $live_game_bowstyle_id = $live_game_oc->get_game_bowstyle_id();
            if( $live_game_bowstyle_id > 0) {
                $bowstyle_id = $live_game_bowstyle_id;
            }
        }

        $result['live_style'] = $live_style_id;

        // Scoreboard Form
        $score_oc = new Score_Controller_Class($this->connection);
        $score_element = $score_oc->get_data(['form']);

        // Game Status
        $gamestatus_oc = new Gamestatus_Controller_Class($this->connection);
        $gamestatus_element = $gamestatus_oc->get_elements(['option']);
        // // Game Mode
        $gamemode_oc = new Gamemode_Controller_Class($this->connection);
        $gamemode_element = $gamemode_oc->get_elements(['radio']);
        // Bowstyle
        $bowstyle_oc = new Bowstyle_Controller_Class($this->connection);
        $bowstyle_element = $bowstyle_oc->get_elements(['radio'],'',0,true);
        // Scoreboard Style
        $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($this->connection);
        // $elements = ['bowstyle'/* ,'option' */,'preview','info','config'];
        // $scoreboard_style_element = $scoreboard_style_oc->get_elements($elements, $bowstyle_id, $live_style_id);
        $scoreboard_style_oc->set_bowstyle_id($bowstyle_id);
        $scoreboard_style_oc->set_style_id($live_style_id);
        $scoreboard_style_data = $scoreboard_style_oc->get_data(['option','bowstyle','preview', 'config', 'info']);
        // Team
        $team_oc = new Team_Controller_Class($this->connection);
        $team_element = $team_oc->get_elements(['table','option'],'',0,true);
        // Player
        $player_oc = new Player_Controller_Class($this->connection);
        $player_element = $player_oc->get_elements(['table','option'],'',0,true);
        // Gamedraw
        $gamedraw_oc = new Gamedraw_Controller_Class($this->connection);
        $gamedraw_element = $gamedraw_oc->get_data(['table','option']);
        // $gamedraw_element = $gamedraw_oc->get_elements(['table','option'],'',0,0,true);

        // Gameset
        $gameset_oc = new Gameset_Controller_Class($this->connection);
        // $gameset_element = $gameset_oc->get_elements(['table'],'',0,true);
        $gameset_element = $gameset_oc->get_data(['table']);

        $result = array_merge(
            $result,
            $score_element, $gamestatus_element, $gamemode_element, $bowstyle_element,
            /* $scoreboard_style_element, */ $scoreboard_style_data, $team_element, $player_element,$gamedraw_element,$gameset_element
        );
        return $result;
    }

    /**
     * Get Scoreboard Form Config
     *
     * @param integer $bowstyle_id Bowstyle ID
     * @param boolean $as_json_decode Set TRUE, if you want return it as string
     * @param boolean $as_array Set TRUE, if you want convert it as JSON Associative Array
     * @return mixed
     */
    public function get_scoreboard_form_config($bowstyle_id=0, $as_json_decode=true, $as_array=true) {
        $scoreboard_form_config = $this->model->scoreboard_form_config();
        if($scoreboard_form_config){
            if( $as_json_decode ){
                return json_decode($scoreboard_form_config, $as_array)[$bowstyle_id];
            }
            return $scoreboard_form_config;
        }
        return '';
    }
}

// Get Config
if (isset($_GET['config_get']) && $_GET['config_get'] != '') {
    $result = [
        'status' => true,
    ];
    $request_name = 'config_get';
    $request_value = $_GET[$request_name];

    if (Tools::is_valid_string_request($request_value)) {

        $database = new Database();
        $connection = $database->getConnection();
        $config_oc = new Config_Controller_Class($connection);

        if ($request_value == 'setup') {

            $config_oc->create_default();

            $gamestatus_oc = new Gamestatus_Controller_Class($connection);
            $gamestatus_oc->create_default();

            $gamemode_oc = new Gamemode_Controller_Class($connection);
            $gamemode_oc->create_default();

            $bowstyle_oc = new Bowstyle_Controller_Class($connection);
            $bowstyle_oc->create_default();

            $scoreboard_style_oc = new Scoreboard_Style_Controller_Class($connection);
            $scoreboard_style_oc->create_default();

            $live_game_oc = new Live_Game_Controller_Class($connection);
            $live_game_oc->create_default();
        } else if ($request_value == 'init') {

            $result = $config_oc->get_init();
        }

        $database->conn->close();
    }
    echo json_encode($result);
}