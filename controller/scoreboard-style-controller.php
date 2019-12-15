<?php
if (isset( $_GET['scoreboard_style_get']) && $_GET['scoreboard_style_get'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['scoreboard_style_get'] == 'group') {

        if(isset( $_GET['bowstyle_id']) && is_numeric( $_GET['bowstyle_id']) && $_GET['bowstyle_id'] > 0){
            $database = new Database();
            $db = $database->getConnection();

            $bowstyle_id = $_GET['bowstyle_id'];

            $scoreboard_style = new Scoreboard_Style($db);
            $result_scoreboard_style = $scoreboard_style->bowstyle($bowstyle_id)->get_styles_by_bowstyle_id();

            $result['status'] = $result_scoreboard_style['status'];

            if($result['status']){
                $item_template = TEMPLATE_DIR . 'scoreboard/style/option.php';
                $scoreboard_style_option = '';
                foreach( $result_scoreboard_style['styles'] as $item){
                    $item['live'] = 0;
                    $scoreboard_style_option .= template( $item_template, $item);
                }
                $result['scoreboard_style_option'] = '<option value="0">Choose</option>' . $scoreboard_style_option;

                $livegame = new Live_Game($db);
                $res_livegame_bowstyle = $livegame->get_live_bowstyle_id();
                $result[ 'has_warning' ] = $res_livegame_bowstyle[ 'status' ];
                if($res_livegame_bowstyle[ 'status' ] != FALSE ) {
                    if ( $res_livegame_bowstyle[ 'bowstyle_id' ] != $bowstyle_id ) {
                        $result[ 'message' ] = "Don't select this bowstyle!";
                    }
                }
            }else{
                $result['message'] = 'ERROR: get Scoreboard Style';
            }
            $database->conn->close();
        }
    }
    else if ( $_GET['scoreboard_style_get'] == 'single'){
        if(isset( $_GET['id']) && is_numeric( $_GET['id']) && $_GET['id'] > 0){
            $database = new Database();
            $db = $database->getConnection();

            $style_id = $_GET['id'];
            // $livegame >>>>>>>>>>>>>>>>>>>> next update

            $scoreboard_style = new Scoreboard_Style($db);
            $result_scoreboard_style = $scoreboard_style->id($style_id)->get_style_config_by_id();
            $result['status'] = $result_scoreboard_style['status'];

            if($result['status']){

                $score = new Score($db);
                $response = $score->get_form_live();
                $livegame_data = NULL;

                if( $response[ 'status' ] ){
                    $contestant = null;
                    if( $response[ 'scores' ][ 'gamemode_id' ] == 1 ) {
                        $contestant = new Team($db);
                    }else if ( $response[ 'scores' ][ 'gamemode_id' ] == 2 ) {
                        $contestant = new Player($db);
                    }

                    for ($i=0; $i< sizeof($response[ 'scores' ]['contestants']); $i++) {
                        $res_con = $contestant->get_live( $response[ 'scores' ]['contestants'][$i]['id'] );
                        $response[ 'scores' ]['contestants'][$i]['logo'] = 'uploads/' . $res_con[ 'logo' ];
                        $response[ 'scores' ]['contestants'][$i]['team'] = $res_con[ 'team' ];
                        $response[ 'scores' ]['contestants'][$i]['player'] = $res_con[ 'player' ];
                    }

                    $livegame_data['gamemode_id'] = $response[ 'scores' ][ 'gamemode_id' ];
                    $livegame_data['sets'] = $response[ 'scores' ][ 'sets' ];
                    $livegame_data['contestants'] = $response[ 'scores' ][ 'contestants' ];
                }

                $obj_livegame = new Live_Game($db);
                $response = $obj_livegame->get_live_bowstyle_id();
                if ( $response['status'] ) {
                    $livegame_data[ 'bowstyle_id' ] = $response[ 'bowstyle_id' ];
                }

                $database->conn->close();

                // $preview_data = array();
                $preview_data['game_data'] = $livegame_data;
                $preview_data['style_config'] = json_decode($result_scoreboard_style['style_config'],true);
                // $result['style_config'] = json_decode($result_scoreboard_style['style_config']);

                $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
                $style_preview = '';
                $style_preview .= template( $item_template, $preview_data );
                $result['style_preview'] = $style_preview;

                // $item_template = TEMPLATE_DIR . 'scoreboard/style/checkbox.php';
                // $style_preview = '';
                // $style_preview .= template( $item_template, [ 'config' => $preview_data['style_config']]);
                // $result['style_preview_checkbox'] = $style_preview;
            }else{
                $result['message'] = 'ERROR: get Scoreboard Style';
            }
        }
    }
    else if( $_GET['scoreboard_style_get'] == 'new_num' && isset( $_GET['bowstyle_id'])) {

        $bowstyle_id = is_numeric( $_GET['bowstyle_id']) ? $_GET['bowstyle_id'] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $scoreboard_style = new Scoreboard_Style($db);
        $response = $scoreboard_style->bowstyle($bowstyle_id)->get_new_num();


        if($response['status']){
            $config_data = array();
            $config_data['game_data'] = NULL;
            $config_data['style_config'] = get_default_style_config();

            $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
            $style_preview = '';
            $style_preview .= template( $item_template, $config_data);
            $result['style_preview'] = $style_preview;
            $result['new_num'] = $response['new_num'];
            $result['status'] = $response['status'];

            $item_template = TEMPLATE_DIR . 'scoreboard/style/checkbox.php';
            $style_preview = '';
            $style_preview .= template( $item_template, [ 'config' => $config_data['style_config']]);
            $result['checkbox'] = $style_preview;

        }else{
            $result['message'] = 'ERROR: get Config Scoreboard Style';
        }
        $database->conn->close();
    }

    echo json_encode($result);
}

if (isset( $_GET['style_config_get']) && $_GET['style_config_get'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if ( $_GET[ 'style_config_get' ] == 'checkbox' && isset( $_GET['style_id'] ) ){

        $style_id = is_numeric( $_GET[ 'style_id' ] ) ? $_GET[ 'style_id' ] : 0;

        $database = new Database();
        $db = $database->getConnection();

        $obj_scoreboard_style = new Scoreboard_Style($db);
        $response = $obj_scoreboard_style->get_config($style_id);
        $scoreboard_styles[ 'checkbox' ] = '';
        if($response['status']) {
            $style_config = json_decode($response['style_config'],true);//var_dump($style_config);

            $item_template = TEMPLATE_DIR . 'scoreboard/style/checkbox.php';
            $style_preview = '';
            $style_preview .= template( $item_template, [ 'config' => $style_config ] );
            $scoreboard_styles[ 'checkbox' ] = $style_preview;

            $result['status'] = true;
        }

        $result['checkbox'] = $scoreboard_styles[ 'checkbox' ];

        $database->conn->close();

    }
    echo json_encode($result);
}

if ( isset( $_POST['scoreboard_style_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( isset( $_POST['style'] ) ) {
        $style_id = is_numeric($_POST['style']) ? $_POST['style'] : 0;
        $style_config = null;
        $data = array();

        $database = new Database();
        $db = $database->getConnection();
        if( $_POST['scoreboard_style_action'] == 'create' || $_POST['scoreboard_style_action'] == 'update') {

            $style_config = array(
                'logo'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['logo'] == "true" ? true : false,
                    'visibility_class'  => $_POST['logo'] == "true" ? '' : 'hide'
                ),
                'team'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['team'] == "true" ? true : false,
                    'visibility_class'  => $_POST['team'] == "true" ? '' : 'hide'
                ),
                'player'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['player'] == "true" ? true : false,
                    'visibility_class'  => $_POST['player'] == "true" ? '' : 'hide'
                ),
                'timer'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['timer'] == "true" ? true : false,
                    'visibility_class'  => $_POST['timer'] == "true" ? '' : 'hide'
                ),
                'score1'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score1'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score1'] == "true" ? '' : 'hide'
                ),
                'score2'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score2'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score2'] == "true" ? '' : 'hide'
                ),
                'score3'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score3'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score3'] == "true" ? '' : 'hide'
                ),
                'score4'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score4'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score4'] == "true" ? '' : 'hide'
                ),
                'score5'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score5'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score5'] == "true" ? '' : 'hide'
                ),
                'score6'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['score6'] == "true" ? true : false,
                    'visibility_class'  => $_POST['score6'] == "true" ? '' : 'hide'
                ),
                'setpoint'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['setpoint'] == "true" ? true : false,
                    'visibility_class'  => $_POST['setpoint'] == "true" ? '' : 'hide'
                ),
                'gamepoint'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['gamepoint'] == "true" ? true : false,
                    'visibility_class'  => $_POST['gamepoint'] == "true" ? '' : 'hide'
                ),
                'setscore'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['setscore'] == "true" ? true : false,
                    'visibility_class'  => $_POST['setscore'] == "true" ? '' : 'hide'
                ),
                'gamescore'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['gamescore'] == "true" ? true : false,
                    'visibility_class'  => $_POST['gamescore'] == "true" ? '' : 'hide'
                ),
                'description'  => array(
                    'label'             => '',
                    'visibility'        => $_POST['description'] == "true" ? true : false,
                    'visibility_class'  => $_POST['description'] == "true" ? '' : 'hide'
                )
            );

            $data['style_config'] = json_encode($style_config);
        }
        if( $_POST['scoreboard_style_action'] == 'create') {

            // TO-DO >> prevent null ID

            $bowstyle_id = $_POST['bowstyle_id'];

            $data['id'] = 0;
            $data['bowstyle_id'] = $bowstyle_id;
            $data['style'] = $style_id;

            $scoreboard_style = new Scoreboard_Style($db);
            $result_query = $scoreboard_style->set_data($data)->create(true);
            $result['status'] = $result_query['status'];

            if ($result['status']){
                $result['action'] = 'create';
                $result['latest_id'] = $result_query['latest_id'];
            }else{
                $result['message'] = 'ERROR: create Scoreboard Style';
            }

        }
        else if( $_POST['scoreboard_style_action'] == 'update') {

            $data['id'] = $style_id;
            $data['bowstyle_id'] = 0;
            $data['style'] = 0;

            $scoreboard_style = new Scoreboard_Style($db);
            $result_query = $scoreboard_style->set_data($data)->update();
            $result['status'] = $result_query;

            if ($result_query){
                $result['action'] = 'update';
            }else{
                $result['message'] = 'ERROR: create Scoreboard Style';
            }

        }
        else if( $_POST['scoreboard_style_action'] == 'delete') {

            // TO-DO >> prevent null ID

            $scoreboard_style = new Scoreboard_Style($db);
            $result_query = $scoreboard_style->id($style_id)->delete();
            $result['status'] = $result_query;

            if ($result_query){
                $obj_livegame = new Live_Game($db);
                $live_style_id = $obj_livegame->get_style_id();
                if($style_id == $live_style_id){
                    $obj_livegame->clean_style();
                }
                $result['action'] = 'delete';
            }else{
                $result['message'] = 'ERROR: create Scoreboard Style';
            }
        }
        else if( $_POST[ 'scoreboard_style_action' ] == 'set-scoreboard-style' ) {


            $livegame = new Live_Game($db);
            $result['status'] = $livegame->set_style( $style_id );
        }
        $database->conn->close();
    }
    echo json_encode($result);
}

?>