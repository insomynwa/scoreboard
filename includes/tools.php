<?php
namespace scoreboard\includes;

class Tools {

    public function __construct() {}

    /**
     * Validate String Request Value
     *
     * @param string $request_value
     * @return boolean
     */
    public static function is_valid_string_request($request_value) {
        if ($request_value == '') {
            return false;
        }

        return true;
    }

    /**
     * Post Integer/Numeric
     *
     * @param integer $post_input $_POST
     * @return integer
     */
    public static function post_int($post_input){
        if(isset($post_input)) {
            if( is_numeric($post_input) || is_int($post_input)){
                return $post_input;
            }
        }
        return 0;
    }

    /**
     * Get Default Scoreboard Form Style Config
     *
     * @return array
     */
    public static function get_default_scoreboard_form_style_config() {
        // 0 = DEFAULT
        // 1 = RECURVE
        // 2 = COMPOUND

        $score_config['0']['logo']['label'] = "";
        $score_config['1']['logo']['label'] = "";
        $score_config['2']['logo']['label'] = "";

        for ($i = 0; $i < sizeof($score_config); $i++) {
            $score_config[$i]['team']['label'] = "Team";

            $score_config[$i]['player']['label'] = "Player";

            $score_config[$i]['timer']['label'] = "";

            $score_config[$i]['score1']['label'] = "1";

            $score_config[$i]['score2']['label'] = "2";

            $score_config[$i]['score3']['label'] = "3";

            $score_config[$i]['score4']['label'] = "4";

            $score_config[$i]['score5']['label'] = "5";

            $score_config[$i]['score6']['label'] = "6";

            $score_config[$i]['setpoint']['label'] = "";

            $score_config[$i]['gamepoint']['label'] = "Set pts";

            $score_config[$i]['description']['label'] = "Description";

            $score_config[$i]['team']['visibility'] = true;
            $score_config[$i]['team']['visibility_class'] = '';

            $score_config[$i]['player']['visibility'] = true;
            $score_config[$i]['player']['visibility_class'] = '';

            $score_config[$i]['score1']['visibility'] = true;
            $score_config[$i]['score1']['visibility_class'] = '';

            $score_config[$i]['score2']['visibility'] = true;
            $score_config[$i]['score2']['visibility_class'] = '';

            $score_config[$i]['score3']['visibility'] = true;
            $score_config[$i]['score3']['visibility_class'] = '';

            $score_config[$i]['setscore']['visibility'] = true;
            $score_config[$i]['setscore']['visibility_class'] = '';
        }

        $score_config['0']['setscore']['label'] = "";
        $score_config['1']['setscore']['label'] = "Total";
        $score_config['2']['setscore']['label'] = "";

        $score_config['0']['gamescore']['label'] = "Total";
        $score_config['1']['gamescore']['label'] = "";
        $score_config['2']['gamescore']['label'] = "Total";

        $score_config['0']['logo']['visibility'] = true; //false
        $score_config['0']['logo']['visibility_class'] = '';
        $score_config['1']['logo']['visibility'] = false; //false
        $score_config['1']['logo']['visibility_class'] = 'hide';
        $score_config['2']['logo']['visibility'] = false; //false
        $score_config['2']['logo']['visibility_class'] = 'hide';

        $score_config['0']['timer']['visibility'] = true; //false
        $score_config['0']['timer']['visibility_class'] = '';
        $score_config['1']['timer']['visibility'] = false; //false
        $score_config['1']['timer']['visibility_class'] = 'hide';
        $score_config['2']['timer']['visibility'] = false; //false
        $score_config['2']['timer']['visibility_class'] = 'hide';

        $score_config['0']['score4']['visibility'] = true; //false
        $score_config['0']['score4']['visibility_class'] = '';
        $score_config['1']['score4']['visibility'] = false; //false
        $score_config['1']['score4']['visibility_class'] = 'hide';
        $score_config['2']['score4']['visibility'] = false; //false
        $score_config['2']['score4']['visibility_class'] = 'hide';

        $score_config['0']['score5']['visibility'] = true; //false
        $score_config['0']['score5']['visibility_class'] = '';
        $score_config['1']['score5']['visibility'] = false; //false
        $score_config['1']['score5']['visibility_class'] = 'hide';
        $score_config['2']['score5']['visibility'] = false; //false
        $score_config['2']['score5']['visibility_class'] = 'hide';

        $score_config['0']['score6']['visibility'] = true; //false
        $score_config['0']['score6']['visibility_class'] = '';
        $score_config['1']['score6']['visibility'] = false; //false
        $score_config['1']['score6']['visibility_class'] = 'hide';
        $score_config['2']['score6']['visibility'] = false; //false
        $score_config['2']['score6']['visibility_class'] = 'hide';

        $score_config['0']['gamescore']['visibility'] = true; //false
        $score_config['0']['gamescore']['visibility_class'] = '';
        $score_config['1']['gamescore']['visibility'] = false; //false
        $score_config['1']['gamescore']['visibility_class'] = 'hide';
        $score_config['2']['gamescore']['visibility'] = true;
        $score_config['2']['gamescore']['visibility_class'] = '';

        $score_config['0']['setpoint']['visibility'] = true;
        $score_config['0']['setpoint']['visibility_class'] = '';
        $score_config['1']['setpoint']['visibility'] = true;
        $score_config['1']['setpoint']['visibility_class'] = '';
        $score_config['2']['setpoint']['visibility'] = false; //false
        $score_config['2']['setpoint']['visibility_class'] = 'hide';

        $score_config['0']['gamepoint']['visibility'] = true; //false
        $score_config['0']['gamepoint']['visibility_class'] = '';
        $score_config['1']['gamepoint']['visibility'] = true;
        $score_config['1']['gamepoint']['visibility_class'] = '';
        $score_config['2']['gamepoint']['visibility'] = false; //false
        $score_config['2']['gamepoint']['visibility_class'] = 'hide';

        $score_config['0']['description']['visibility'] = true;
        $score_config['0']['description']['visibility_class'] = '';
        $score_config['1']['description']['visibility'] = false; //false
        $score_config['1']['description']['visibility_class'] = 'hide';
        $score_config['2']['description']['visibility'] = false; //false
        $score_config['2']['description']['visibility_class'] = 'hide';

        return $score_config;
    }

    /**
     * Simple Templating function
     *
     * @param $file   - Path to the PHP file that acts as a template.
     * @param $args   - Associative array of variables to pass to the template file.
     * @return string - Output of the template file. Likely HTML.
     */
    public static function template($file, $args) {
        // ensure the file exists
        if (!file_exists($file)) {
            return '';
        }

        // Make values in the associative array easier to access by extracting them
        if (is_array($args)) {
            extract($args);
        }

        // buffer the output (including the file is "output")
        ob_start();
        include $file;
        return ob_get_clean();
    }

    /**
     * Get Default Scoreboard Style Config
     *
     * @param int $bowstyle_id
     * @param int $style
     * @return json_encode
     */
    public static function get_default_scoreboard_style_config($bowstyle_id, $style) {
        $style_config = array();

        $style_config['logo']['label'] = '';
        $style_config['logo']['visibility'] = false;
        $style_config['logo']['visibility_class'] = 'hide';

        $style_config['team']['label'] = '';
        $style_config['team']['visibility'] = true;
        $style_config['team']['visibility_class'] = '';

        $style_config['player']['label'] = '';
        $style_config['player']['visibility'] = true;
        $style_config['player']['visibility_class'] = '';

        $style_config['timer']['label'] = '';
        $style_config['timer']['visibility'] = false;
        $style_config['timer']['visibility_class'] = 'hide';

        if ($style == 2) {
            $style_config['score1']['label'] = '';
            $style_config['score1']['visibility'] = true;
            $style_config['score1']['visibility_class'] = '';
            $style_config['score2']['label'] = '';
            $style_config['score2']['visibility'] = true;
            $style_config['score2']['visibility_class'] = '';
            $style_config['score3']['label'] = '';
            $style_config['score3']['visibility'] = true;
            $style_config['score3']['visibility_class'] = '';
        } else {
            $style_config['score1']['label'] = '';
            $style_config['score1']['visibility'] = false;
            $style_config['score1']['visibility_class'] = 'hide';
            $style_config['score2']['label'] = '';
            $style_config['score2']['visibility'] = false;
            $style_config['score2']['visibility_class'] = 'hide';
            $style_config['score3']['label'] = '';
            $style_config['score3']['visibility'] = false;
            $style_config['score3']['visibility_class'] = 'hide';
        }

        $style_config['score4']['label'] = '';
        $style_config['score4']['visibility'] = false;
        $style_config['score4']['visibility_class'] = 'hide';
        $style_config['score5']['label'] = '';
        $style_config['score5']['visibility'] = false;
        $style_config['score5']['visibility_class'] = 'hide';
        $style_config['score6']['label'] = '';
        $style_config['score6']['visibility'] = false;
        $style_config['score6']['visibility_class'] = 'hide';

        $style_config['setpoint']['label'] = '';
        $style_config['setpoint']['visibility'] = false;
        $style_config['setpoint']['visibility_class'] = 'hide';

        if ($style == 3) {
            $style_config['setscore']['label'] = '';
            $style_config['setscore']['visibility'] = false;
            $style_config['setscore']['visibility_class'] = 'hide';

        } else {
            $style_config['setscore']['label'] = '';
            $style_config['setscore']['visibility'] = true;
            $style_config['setscore']['visibility_class'] = '';
        }

        if ($bowstyle_id == 1) { // Recurve

            $style_config['gamescore']['label'] = 'Total';
            $style_config['gamescore']['visibility'] = false;
            $style_config['gamescore']['visibility_class'] = 'hide';

            $style_config['gamepoint']['label'] = 'Set pts';
            $style_config['gamepoint']['visibility'] = true;
            $style_config['gamepoint']['visibility_class'] = '';
        } else if ($bowstyle_id == 2) { // Compound

            $style_config['gamescore']['label'] = 'Total';
            $style_config['gamescore']['visibility'] = true;
            $style_config['gamescore']['visibility_class'] = '';

            $style_config['gamepoint']['label'] = 'Set pts';
            $style_config['gamepoint']['visibility'] = false;
            $style_config['gamepoint']['visibility_class'] = 'hide';
        }

        $style_config['description']['label'] = '';
        $style_config['description']['visibility'] = false;
        $style_config['description']['visibility_class'] = 'hide';

        return json_encode($style_config);
    }

    /**
     * Get Default Style Config
     *
     * @return array
     */
    public static function get_default_style_config() {
        return array(
            'logo' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'team' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'player' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'timer' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score1' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score2' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score3' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score4' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score5' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'score6' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'setpoint' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'gamepoint' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'setscore' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'gamescore' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
            'description' => array(
                'label' => '',
                'visibility' => true,
                'visibility_class' => '',
            ),
        );
    }

    /**
     * Render Result
     *
     * @param string $key
     * @param variant $value
     * @param string $message
     * @param boolean $status
     * @return array
     */
    public static function render_result($key = '', $value = null, $status = false, $message = '') {
        if ($status) {
            return [
                $key => $value,
            ];
        } else {
            return [
                $key => [
                    'status' => $status,
                    'message' => $message,
                ],
            ];
        }
    }

    /**
     * Create Loop Element
     *
     * @param array $data_list
     * @param string $loop_key teams,players,etc
     * @param string $json_key
     * @param string $template_loc
     * @param string $render_list_start
     * @param integer $selected_item
     * @return array
     */
    public function create_loop_element($data_list = null, $loop_key = '', $json_key = '', $template_loc = '', $render_list_start = '', $selected_item = 0) {

        if (is_null($data_list)) {
            return self::render_result($json_key, $render_list_start, false, 'create_loop_element');
        }
        if ( empty($data_list) ){
            return Tools::render_result($json_key, $render_list_start, true);
        }
        foreach ($data_list[$loop_key] as $item) {
            $item['live'] = 0;
            if ($selected_item>0) {
                $item['live'] = $selected_item;
            }

            $render_list_start .= Tools::template($template_loc, $item);
        }
        return Tools::render_result($json_key, $render_list_start, true);
    }

    /**
     * Get Valid Image Extensions
     *
     * @return array
     */
    public static function valid_image_extensions(){
        return array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt');
    }

    /**
     * Upload Directory Location
     *
     * @return void
     */
    public static function upload_directory(){
        return BASE_DIR . '/uploads/'; // upload directory
    }
}
