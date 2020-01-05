<?php
namespace scoreboard\controller;

use scoreboard\config\Database;
use scoreboard\includes\Tools;
use scoreboard\model\Team_Model_Class;

class Team_Controller_Class {

    private $connection;
    private $model;

    private $root_key;
    private $table_key;
    private $option_key;
    private $modal_form_key;

    private $item_template_name;
    private $item_template_loc;

    private $no_item_template_name;
    private $no_item_template_loc;

    private $option_template_name;
    private $option_template_loc;

    private $id;
    private $gamemode_id;

    /**
     * Class Constructor
     *
     * @param object $connection
     */
    public function __construct($connection = null) {
        $this->connection = $connection;
        $this->model = new Team_Model_Class($connection);

        $this->gamemode_id = 1;

        $this->root_key = 'team';
        $this->table_key = 'table';
        $this->option_key = 'option';
        $this->modal_form_key = 'modal_form';

        $this->init_templates();
    }

    /**
     * Init templates
     *
     * @return void
     */
    private function init_templates(){

        $template_loc = TEMPLATE_DIR . 'team/';
        $this->item_template_name = 'item';
        $this->item_template_loc = $template_loc . $this->item_template_name . '.php';
        $this->no_item_template_name = 'no-item';
        $this->no_item_template_loc = $template_loc . $this->no_item_template_name . '.php';
        $this->option_template_name = 'option';
        $this->option_template_loc = $template_loc . $this->option_template_name . '.php';
    }

    /**
     * Set ID
     *
     * @param integer $team_id Team ID
     * @return void
     */
    public function set_id($team_id=0){
        $this->id = $team_id;
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
     * @return array
     */
    private function get_option_data(){
        return $this->model->option_data();
    }

    /**
     * Get Model Form Data
     *
     * @param integer $team_id Team ID
     * @return array result
     */
    private function get_modal_form_data(){
        return $this->model->modal_form_data($this->id);
    }

    /**
     * Get Data
     *
     * @param array $req_data Requested Data
     * @return array
     */
    public function get_data( $req_data=array( 'option', 'table', 'modal_form')){
        $result = array();
        $result[$this->root_key] = array();
        $root_res = $result[$this->root_key];

        if( empty($req_data) ){
            return $result;
        }

        $data = null;
        $table_data = null;

        if(in_array($this->table_key,$req_data)){
            $table_data = $this->get_table_data();
            $root_res[$this->table_key] = $table_data;
        }

        if(in_array($this->option_key,$req_data)){
            $data = is_null($table_data) ? $this->get_option_data() : $table_data;
            $root_res[$this->option_key] = $data;
        }

        if (in_array($this->modal_form_key, $req_data)) {
            $data = $this->get_modal_form_data();
            $root_res[$this->modal_form_key] = $data;
        }

        $result[$this->root_key] = $root_res;

        return $result;
    }

    /**
     * Get Players ID
     *
     * @param integer Team ID
     * @return array empty | [player_id1, player_id2, player_id..n]
     */
    private function get_players_id($team_id = 0) {
        if ($team_id > 0) {
            $player_oc = new Player_Controller_Class($this->connection);
            return $player_oc->get_team_players_id($team_id);
        }
        return array();
    }

    /**
     * Get Gamedraws ID
     *
     * @param integer $team_id Team ID
     * @return array empty | [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    private function get_gamedraws_id($team_id){
        if ($team_id > 0) {
            $gamedraw_oc = new Gamedraw_Controller_Class($this->connection);
            return $gamedraw_oc->get_team_gamedraws_id($team_id);
        }
        return array();
    }

    /**
     * Get Gamesets ID
     *
     * @param integer $team_id Team ID
     * @return array empty | [gameset_id1, gameset_id2, gameset_id..n]
     */
    private function get_gamesets_id($team_id=0){
        if ($team_id > 0) {
            $gameset_oc = new Gameset_Controller_Class($this->connection);
            return $gameset_oc->get_team_gamesets_id($team_id);
        }
        return array();
    }

    /**
     * Get Scores ID
     *
     * @param integer $team_id Team ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    private function get_scores_id($team_id=0){
        if ($team_id > 0) {
            $score_oc = new Score_Controller_Class($this->connection);
            return $score_oc->get_team_scores_id($team_id);
        }
        return array();
    }

    /**
     * Get Logo
     *
     * @param integer $team_id Team ID
     * @return string no-image.png | filename.png
     */
    private function get_logo($team_id=0){
        if ($team_id > 0) {
            return $this->model->logo($team_id);
        }
        return 'no-image.png';
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
        return $score_oc->delete_team_scores($scores_id);
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
        return $gameset_oc->delete_team_gamesets($gamesets_id);
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
        return $gamedraw_oc->delete_team_gamedraws($gamedraws_id);
    }

    /**
     * Delete Players
     *
     * @param array $players_id Gamedraws ID
     * @return boolean
     */
    private function delete_players($players_id=null){
        if(empty($players_id) || is_null($players_id)) return false;
        $player_oc = new Player_Controller_Class($this->connection);
        return $player_oc->delete_team_players($players_id);
    }

    /**
     * Delete Team
     *
     * @param integer Team ID
     * @return array result[]
     */
    private function delete_team($team_id = 0) {
        $result = [
            'status' => false,
            'action' => 'delete',
        ];

        if( $team_id == 0){
            $result['message'] = 'ERROR: delete_team Team ID: 0';
            return $result;
        }

        $team_logo = $this->get_logo($team_id);
        $players_id = $this->get_players_id($team_id);
        $gamedraws_id = $this->get_gamedraws_id($team_id);

        if(! empty($gamedraws_id) ){
            $gamesets_id = $this->get_gamesets_id($team_id);

            if(! empty($gamesets_id) ){
                $scores_id = $this->get_scores_id($team_id);

                if (!empty($scores_id)) {
                    if (!$this->delete_scores($scores_id)) {
                        $result['message'] = 'ERROR: delete_team delete_scores';
                        return $result;
                    }
                }
                if (!$this->delete_gamesets($gamesets_id)) {
                    $result['message'] = 'ERROR: delete_team delete_gamesets';
                    return $result;
                }

                $live_game_oc = new Live_Game_Controller_Class($this->connection);
                if ($live_game_oc->is_gameset_live($gamesets_id)) {
                    if (!$live_game_oc->set_live_game(0)) {
                        $result['message'] = 'ERROR: delete_team set_live_game';
                        return $result;
                    }
                }
            }
            if (!$this->delete_gamedraws($gamedraws_id)) {
                $result['message'] = 'ERROR: delete_team delete_gamedraws';
                return $result;
            }
        }

        if (!empty($players_id)) {
            if (!$this->delete_players($players_id)) {
                $result['message'] = 'ERROR: delete_team delete_players';
                return $result;
            }
        }

        if (!$this->model->delete_team($team_id)) {
            $result['message'] = 'ERROR: delete_team';
            return $result;
        }

        if ($team_logo != "no-image.png" && $team_logo != '') {
            unlink(UPLOAD_DIR . $team_logo);
        }
        $result['status'] = true;
        return $result;
    }

    /**
     * Form Action
     *
     * @param string $action Action
     * @param array $data Input Data
     * @param integer $team_id Team ID
     * @return array result
     */
    public function form_action($action = '', $data = null, $team_id = 0) {
        $result = [
            'status' => false,
            'action' => $action,
        ];
        if ($action == '') {
            return $result;
        }

        if ($action == 'create' || $action == 'update') {
            if (is_null($data)) {
                return $result;
            }
        }

        if ($action == 'delete' || $action == 'update') {
            if ($team_id == 0) {
                return $result;
            }
        }

        if ($action == 'create' || $action == 'update') {

            $team_data_id = $data['id'];
            $team_name = $data['name'];
            $team_logo = '';
            $team_logo_img = $data['logo']['img'];
            $team_logo_tmp = $data['logo']['tmp'];
            $team_initial = $data['initial'];
            $team_description = $data['description'];

            $valid_extensions = Tools::valid_image_extensions(); // valid extensions
            $path = Tools::upload_directory();

            $upload_status = false;

            $name = $team_name;
            $team_initial = strtoupper(substr($name, 0, 3));
            $team_desc = $team_description;

            $img = $team_logo_img;
            $tmp = $team_logo_tmp;
            $is_upload = ($img != "");
            $final_image = "";
            $sql = "";

            if ($is_upload) {

                // get uploaded file's extension
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                // can upload same image using rand function
                $final_image = rand(1000, 1000000) . $img;

                // check's valid format
                if (in_array($ext, $valid_extensions)) {
                    $path = $path . strtolower($final_image);
                    /* $pathB = $path.strtolower($final_imageB);  */

                    if (move_uploaded_file($tmp, $path) /*  && move_uploaded_file($tmpB,$pathB) */) {
                        $upload_status = true;

                        $prev_team_logo = "";

                        if ($action == 'create') {
                            $team_logo = strtolower($final_image);
                            $result['status'] = $this->model->insert($team_logo, $team_name, $team_initial, $team_description);
                            if (!$result['status']) {
                                $result['message'] = 'ERROR: create team & logo';
                            }
                        } else if ($action == 'update') {
                            $result['new_logo'] = '';

                            $action_om = $this->model->team_logo($team_data_id);
                            $prev_team_logo = $action_om == '' ? 'no-image.png' : $action_om;
                            $result['new_logo'] = $final_image;
                            $team_logo = $final_image;

                            $result['status'] = $this->model->update_team($team_data_id, $team_logo, $team_name, $team_initial, $team_description);
                            if ($result['status']) {
                                if ($prev_team_logo != "no-image.png") {
                                    unlink(UPLOAD_DIR . $prev_team_logo);
                                }
                            } else {
                                $result['message'] = 'ERROR: update team & logo';
                            }
                        }
                    } else {
                        if ($action == 'update') {
                            $result['new_logo'] = '';

                            $action_om = $this->model->team_logo($team_data_id);
                            $prev_team_logo = $action_om == '' ? 'no-image.png' : $action_om;
                            $result['new_logo'] = $prev_team_logo;
                            $team_logo = $prev_team_logo;

                            $result['status'] = $this->model->update_team($team_data_id, $team_logo, $team_name, $team_initial, $team_description);
                            if (!$result['status']) {
                                $result['message'] = 'ERROR: update team';
                            }
                        } else {
                            $result['message'] = "ERROR: upload logo";
                        }
                    }
                } else {
                    $result['message'] = "ERROR: invalid format";
                }
            } else {
                if ($action == 'update') {
                    $result['new_logo'] = '';

                    $action_om = $this->model->team_logo($team_data_id);
                    $prev_team_logo = $action_om == '' ? 'no-image.png' : $action_om;
                    $result['new_logo'] = $prev_team_logo;
                    $team_logo = $prev_team_logo;

                    $result['status'] = $this->model->update_team($team_data_id, $team_logo, $team_name, $team_initial, $team_description);
                    if (!$result['status']) {
                        $result['message'] = 'ERROR: update team';
                    }
                } else if ($action == 'create') {
                    if ($team_logo == '') {
                        $team_logo = 'no-image.png';
                    }

                    $result['status'] = $this->model->insert($team_logo, $team_name, $team_initial, $team_description);
                    if (!$result['status']) {
                        $result['message'] = 'ERROR: create team';
                    }
                }
            }
        } else if ($action == 'delete') {
            $result = $this->delete_team($team_id);
        }
        return $result;
    }

    /**
     * Get Scoreboard Form Data
     *
     * @param integer $team_id Team ID
     * @return mixed false | [team_logo, team_name]
     */
    public function get_scoreboard_form_data($team_id=0) {
        if( $team_id==0) return false;

        $data = $this->model->scoreboard_form_data($team_id);
        if( $data ){
            return $data;
        }
        return false;
    }

}

if (isset($_POST['team_action'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'team_action';
    $request_value = $_POST[$request_name];
    if (Tools::is_valid_string_request($request_value)) {

        $database = new Database();
        $connection = $database->getConnection();
        $team_oc = new Team_Controller_Class($connection);
        $team_id = 0;
        $team_data = array();
        if ($request_value == 'update' || $request_value == 'delete') {
            if (isset($_POST['team_id'])) {
                $team_id = is_numeric($_POST['team_id']) ? $_POST['team_id'] : 0;
            }
        }
        if ($request_value == 'create' || $request_value == 'update') {
            $team_data = [
                'id' => $team_id,
                'name' => $_POST['team_name'] != '' ? $_POST['team_name'] : 'invalid name (edit it!)',
                'logo' => [
                    'img' => $_FILES['team_logo']['name'],
                    'tmp' => $_FILES['team_logo']['tmp_name'],
                ],
                'initial' => $_POST['team_name'] != '' ? strtoupper(substr($_POST['team_name'], 0, 3)) : '-',
                'description' => $_POST['team_desc'] != '' ? $_POST['team_desc'] : 'team description',
            ];
            $result = $team_oc->form_action($request_value, $team_data, $team_id);
        } else if ($request_value == 'delete') {
            $result = $team_oc->form_action($request_value, null, $team_id);
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

if (isset($_GET['team_get'])) {
    $result = [
        'status' => true,
    ];
    $request_name = 'team_get';
    $request_value = $_GET[$request_name];
    if (Tools::is_valid_string_request($request_value)) {
        $database = new Database();
        $connection = $database->getConnection();
        $team_oc = new Team_Controller_Class($connection);
        if ($request_value == 'single') {
            if( isset($_GET['id'])) {
                $team_id = is_numeric($_GET['id']) ? $_GET['id'] : 0;
                $team_oc->set_id($team_id);
                $team_data = $team_oc->get_data(['modal_form']);
            }
        } else if ($request_value == 'new_list') {
            $team_data = $team_oc->get_data(['table','option']);
        }
        $result = array_merge($result, $team_data);
        $database->conn->close();
    }
    echo json_encode($result);
}

?>