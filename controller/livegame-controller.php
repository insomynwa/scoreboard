<?php

if ( isset( $_POST['livegame_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['livegame_action'] != ''){
        $timer = 120;
        if( $_POST['livegame_action'] == 'stop-live-game'){
            $gameset_id = ( isset($_POST['gamesetid']) && is_numeric( $_POST[ 'gamesetid' ] ) ) ? $_POST['gamesetid'] : 0;

            $database = new Database();
            $db = $database->getConnection();

            $livegame = new Live_Game($db);
            $result_livegame = $livegame->set_live(0);
            $result['status'] = $result_livegame['status'];

            $gameset = new GameSet($db);
            $result_gameset = $gameset->id($gameset_id)->set_status_standby();
            $result['status'] = $result['status'] && $result_gameset;

            $database->conn->close();
        }
        else if( $_POST['livegame_action'] == 'set-live-game'){
            $gameset_id = ( isset($_POST['gamesetid']) && is_numeric( $_POST[ 'gamesetid' ] ) ) ? $_POST['gamesetid'] : 0;
            $database = new Database();
            $db = $database->getConnection();

            $obj_livegame = new Live_Game($db);
            $current_live_gameset_id = $obj_livegame->get_live_gameset_id();
            $response_style = $obj_livegame->get_style_bowstyle_id();
            $style_bowstyle_id = 0;
            if( $response_style['status'] ) {
                $style_bowstyle_id = $response_style['bowstyle_id'];
            }

            if($current_live_gameset_id != $gameset_id){
                $obj_livegame->set_live($gameset_id);

                $obj_gameset = new GameSet( $db );
                $success_update_new_live_gameset = $obj_gameset->id($gameset_id)->set_status_live();
                if($current_live_gameset_id > 0){
                    $gameset = new GameSet( $db );
                    $success_update_curr_live_gameset = $gameset->id($current_live_gameset_id)->set_status_standby();
                }

                $cur_new_gameset_bowstyle_id = $obj_gameset->get_bowstyle_id($gameset_id);

                if( $style_bowstyle_id != 0 && $style_bowstyle_id != $cur_new_gameset_bowstyle_id ){
                    $obj_livegame->clean_style();
                }
            }
            $database->conn->close();
            $result['status'] = true;
            $result['live_game'] = $gameset_id;
        }
    }
    echo json_encode($result);
}

if ( isset( $_GET[ 'livegame_get' ] ) && $_GET[ 'livegame_get' ] != '' && $_GET[ 'livegame_get' ] == 'scoreboard' ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $database = new Database();
    $db = $database->getConnection();

    $score = new Score($db);
    $res_s = $score->get_live();

    if ( $res_s[ 'status' ] ) {

        $result['status'] = ! is_null( $res_s[ 'scores' ]['style_config'] );

        if( $result[ 'status' ] ) {
            $contestant = null;
            if( $res_s[ 'scores' ][ 'gamemode_id' ] == 1 ) {
                $contestant = new Team($db);
            }else if ( $res_s[ 'scores' ][ 'gamemode_id' ] == 2 ) {
                $contestant = new Player($db);
            }

            for ($i=0; $i< sizeof($res_s[ 'scores' ]['contestants']); $i++) {
                // if( $value == 'contestant' ) {
                // }
                $res_con = $contestant->get_live( $res_s[ 'scores' ]['contestants'][$i]['id'] );
                $res_s[ 'scores' ]['contestants'][$i]['logo'] = 'uploads/' . $res_con[ 'logo' ];
                $res_s[ 'scores' ]['contestants'][$i]['team'] = $res_con[ 'team' ];
                $res_s[ 'scores' ]['contestants'][$i]['player'] = $res_con[ 'player' ];
            }
            // $result[ 'sets' ] = $res_s[ 'scores' ][ 'sets' ];
            // $result[ 'contestants' ] = $res_s[ 'scores' ][ 'contestants' ];
            // $result[ 'style_config' ] = json_decode($res_s[ 'scores' ]['style_config'], true);

            // $result['style_config'] = json_decode($res_s[ 'scores' ]['style_config'], true);

            $data_scores['gamemode_id'] = $res_s[ 'scores' ][ 'gamemode_id' ];
            $data_scores['bowstyle_id'] = $res_s[ 'scores' ][ 'bowstyle_id' ];
            $data_scores['sets'] = $res_s[ 'scores' ][ 'sets' ];
            $data_scores['contestants'] = $res_s[ 'scores' ][ 'contestants' ];
            $data_scores['style_config'] = json_decode($res_s[ 'scores' ]['style_config'], true);
            $item_template = TEMPLATE_DIR . 'scoreboard/live.php';
            $style_config = '';//var_dump($res_s[ 'scores' ]);die;
            $style_config .= template( $item_template, $data_scores);
            $result['style_config'] = $style_config;
        }
    }
    // $result_livegame_scoreboard
    $database->conn->close();
    echo json_encode( $result );
}
?>