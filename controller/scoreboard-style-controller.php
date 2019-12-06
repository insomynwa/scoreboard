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
            $database->conn->close();
            $result['status'] = $result_scoreboard_style['status'];

            if($result['status']){
                $item_template = TEMPLATE_DIR . 'scoreboard/style/option.php';
                $scoreboard_style_option = '';
                foreach( $result_scoreboard_style['styles'] as $item){
                    $scoreboard_style_option .= template( $item_template, $item);
                }
                $result['scoreboard_style_option'] = '<option value="0">choose</option>' . $scoreboard_style_option;
            }else{
                $result['message'] = 'ERROR: get Scoreboard Style';
            }
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
            $database->conn->close();
            $result['status'] = $result_scoreboard_style['status'];

            if($result['status']){
                $result['style_config'] = json_decode($result_scoreboard_style['style_config']);

                $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
                $style_preview = '';
                $style_preview .= template( $item_template, json_decode($result_scoreboard_style['style_config'],true));
                $result['style_preview'] = $style_preview;
            }else{
                $result['message'] = 'ERROR: get Scoreboard Style';
            }
        }
    }
    else if( $_GET['scoreboard_style_get'] == 'new_num' && isset( $_GET['bowstyle_id']) && is_numeric( $_GET['bowstyle_id']) && $_GET['bowstyle_id'] > 0) {

        $bowstyle_id = $_GET['bowstyle_id'];

        $database = new Database();
        $db = $database->getConnection();

        $scoreboard_style = new Scoreboard_Style($db);
        $result_query = $scoreboard_style->bowstyle($bowstyle_id)->get_new_num();
        $database->conn->close();
        $result['status'] = $result_query['status'];

        if($result['status']){

            $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
            $style_preview = '';
            $style_preview .= template( $item_template, $scoreboard_style->get_default_style_config());
            $result['style_preview'] = $style_preview;
            $result['new_num'] = $result_query['new_num'];
        }else{
            $result['message'] = 'ERROR: get Scoreboard Style';
        }
    }
    echo json_encode($result);
}

if ( isset( $_POST['scoreboard_style_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['scoreboard_style_action'] == 'create') {

        // TO-DO >> prevent null ID

        $style_id = $_POST['style'];
        $bowstyle_id = $_POST['bowstyle_id'];

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

        $data = array(
            'id'            => 0,
            'bowstyle_id'   => $bowstyle_id,
            'style'         => $style_id,
            'style_config'  => json_encode($style_config)
        );

        $database = new Database();
        $db = $database->getConnection();

        $scoreboard_style = new Scoreboard_Style($db);
        $result_query = $scoreboard_style->set_data($data)->create(true);
        $database->conn->close();
        $result['status'] = $result_query['status'];

        if ($result['status']){
            $result['action'] = 'create';
            $result['latest_id'] = $result_query['latest_id'];
        }else{
            $result['message'] = 'ERROR: create Scoreboard Style';
        }

    }
    else if( $_POST['scoreboard_style_action'] == 'update') {

        // TO-DO >> prevent null ID

        $style_id = $_POST['style'];

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

        $data = array(
            'id'            => $style_id,
            'bowstyle_id'   => 0,
            'style'         => 0,
            'style_config'  => json_encode($style_config)
        );

        $database = new Database();
        $db = $database->getConnection();

        $scoreboard_style = new Scoreboard_Style($db);
        $result_query = $scoreboard_style->set_data($data)->update();
        $database->conn->close();
        $result['status'] = $result_query;

        if ($result_query){
            $result['action'] = 'update';
        }else{
            $result['message'] = 'ERROR: create Scoreboard Style';
        }

    }
    else if( $_POST['scoreboard_style_action'] == 'delete') {

        // TO-DO >> prevent null ID

        $style_id = $_POST['style'];

        $database = new Database();
        $db = $database->getConnection();

        $scoreboard_style = new Scoreboard_Style($db);
        $result_query = $scoreboard_style->id($style_id)->delete();
        $database->conn->close();
        $result['status'] = $result_query;

        if ($result_query){
            $result['action'] = 'delete';
        }else{
            $result['message'] = 'ERROR: create Scoreboard Style';
        }
    }
    echo json_encode($result);
}

?>