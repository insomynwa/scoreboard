<?php

/* if ( isset( $_GET['config_set'] ) && $_GET['config_set'] != '' ) {
    $result = array(
        'status'    => true,
        'message'   => ''
    );
    if( $_GET['config_set'] == 'setup' ) {
        $database = new Database();
        $db = $database->getConnection();

        $obj_config = new Config($db);
        if( ! $obj_config->is_created() ) {
            $scoreboard_form_style_config_id = 1;
            $scoreboard_form_style_config_name = 'form_scoreboard';
            $scoreboard_form_style_config_value = json_encode( get_default_scoreboard_form_style_config() );

            $live_scoreboard_time_interval_config_id = 2;
            $live_scoreboard_time_interval_config_name = 'live_scoreboard_time_interval';
            $live_scoreboard_time_interval_config_value = 500;

            $response = $obj_config->create_default_config( $scoreboard_form_style_config_id, $scoreboard_form_style_config_name, $scoreboard_form_style_config_value );
            $response = $response && $obj_config->create_default_config( $live_scoreboard_time_interval_config_id, $live_scoreboard_time_interval_config_name, $live_scoreboard_time_interval_config_value );

            $result['status'] = $response && $result['status'];
        }

        $obj_gamestatus = new GameStatus($db);
        if( ! $obj_gamestatus->is_created() ) {
            $status_id = 1;
            $status_name = 'Stand by';
            $response = $obj_gamestatus->create_default_status( $status_id, $status_name );
            $status_id = 2;
            $status_name = 'Live';
            $response = $response && $obj_gamestatus->create_default_status( $status_id, $status_name );
            $status_id = 3;
            $status_name = 'Finished';
            $response = $response && $obj_gamestatus->create_default_status( $status_id, $status_name );

            $result['status'] = $response && $result['status'];
        }

        $obj_gamemode = new GameMode($db);
        if( ! $obj_gamemode->is_created() ) {
            $mode_id = 1;
            $mode_name = 'Beregu';
            $mode_desc = 'team vs team';
            $response = $obj_gamemode->create_default_mode( $mode_id, $mode_name, $mode_desc );
            $mode_id = 2;
            $mode_name = 'Individu';
            $mode_desc = 'individu vs individu';
            $response = $response && $obj_gamemode->create_default_mode( $mode_id, $mode_name, $mode_desc );

            $result['status'] = $response && $result['status'];
        }

        $obj_bowstyle = new Bowstyle($db);
        if( ! $obj_bowstyle->is_created() ) {
            $style_id = 1;
            $style_name = 'Recurve';
            $response = $obj_bowstyle->create_default_style( $style_id, $style_name );
            $style_id = 2;
            $style_name = 'Compound';
            $response = $response && $obj_bowstyle->create_default_style( $style_id, $style_name );

            $result['status'] = $response && $result['status'];
        }

        $obj_scoreboard_style = new Scoreboard_Style($db);
        if( ! $obj_scoreboard_style->is_created() ){
            $style_id = 1;
            $bowstyle_id = 1;
            $style = 1;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 2;
            $bowstyle_id = 1;
            $style = 2;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 3;
            $bowstyle_id = 1;
            $style = 3;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 4;
            $bowstyle_id = 2;
            $style = 1;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 5;
            $bowstyle_id = 2;
            $style = 2;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 6;
            $bowstyle_id = 2;
            $style = 3;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $result['status'] = $response && $result['status'];
        }

        $obj_livegame = new Live_Game($db);
        if( ! $obj_livegame->is_created() ){
            $response = $obj_livegame->create_default_game();

            $result['status'] = $response && $result['status'];
        }
    }
    echo json_encode($result);
} */

// Get Config
if (isset( $_GET['config_get']) && $_GET['config_get'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['config_get'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $config = new Web_Config($db);
        $result_config_query = $config->get();

        $bowstyle_id=0;
        $livegame = new Live_Game($db);
        $result_query = $livegame->get_live_bowstyle_id();
        if($result_query['status']){
            $bowstyle_id = $result_query['bowstyle_id'];
        }

        $app_config = new Config($db);
        // $result_scoreboard_config = $app_config->get_scoreboard_config();
        $result_scoreboard_config = $app_config->get_scoreboard_form_style_config();

        $database->conn->close();

        $result['status'] = $result_config_query['status'] && $result_scoreboard_config['status'];

        if( $result['status'] ){
            $result['config'] = $result_config_query['config'];
            // $scoreboard_cfg = json_decode($result_scoreboard_config['scoreboard_config']);
            $scoreboard_cfg = json_decode($result_scoreboard_config['style_config']);
            $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
            $active_mode = $result['config']['active_mode'];
            $result['config']['preview_scoreboard']['mode'] = $active_mode;
            switch ($active_mode) {
                case 1: case 2: case 3:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[0];
                    break;
                case 4: case 5: case 6:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[1];
                    break;
                case 7: case 8: case 9:
                    $result['config']['preview_scoreboard']['cfg'] = $scoreboard_cfg[2];
                    break;

                default:
                    $result['config']['preview_scoreboard']['cfg'] = null;
                    break;
            }
        }
    }
    else if( $_GET['config_get'] == 'setup' ) {
        $database = new Database();
        $db = $database->getConnection();
        $result['status'] = true;

        $obj_config = new Config($db);
        if( ! $obj_config->is_created() ) {
            $scoreboard_form_style_config_id = 1;
            $scoreboard_form_style_config_name = 'form_scoreboard';
            $scoreboard_form_style_config_value = json_encode( get_default_scoreboard_form_style_config() );

            $live_scoreboard_time_interval_config_id = 2;
            $live_scoreboard_time_interval_config_name = 'live_scoreboard_time_interval';
            $live_scoreboard_time_interval_config_value = 500;

            $response = $obj_config->create_default_config( $scoreboard_form_style_config_id, $scoreboard_form_style_config_name, $scoreboard_form_style_config_value );
            $response = $response && $obj_config->create_default_config( $live_scoreboard_time_interval_config_id, $live_scoreboard_time_interval_config_name, $live_scoreboard_time_interval_config_value );

            $result['status'] = $response && $result['status'];
        }

        $obj_gamestatus = new GameStatus($db);
        if( ! $obj_gamestatus->is_created() ) {
            $status_id = 1;
            $status_name = 'Stand by';
            $response = $obj_gamestatus->create_default_status( $status_id, $status_name );
            $status_id = 2;
            $status_name = 'Live';
            $response = $response && $obj_gamestatus->create_default_status( $status_id, $status_name );
            $status_id = 3;
            $status_name = 'Finished';
            $response = $response && $obj_gamestatus->create_default_status( $status_id, $status_name );

            $result['status'] = $response && $result['status'];
        }

        $obj_gamemode = new GameMode($db);
        if( ! $obj_gamemode->is_created() ) {
            $mode_id = 1;
            $mode_name = 'Beregu';
            $mode_desc = 'team vs team';
            $response = $obj_gamemode->create_default_mode( $mode_id, $mode_name, $mode_desc );
            $mode_id = 2;
            $mode_name = 'Individu';
            $mode_desc = 'individu vs individu';
            $response = $response && $obj_gamemode->create_default_mode( $mode_id, $mode_name, $mode_desc );

            $result['status'] = $response && $result['status'];
        }

        $obj_bowstyle = new Bowstyle($db);
        if( ! $obj_bowstyle->is_created() ) {
            $style_id = 1;
            $style_name = 'Recurve';
            $response = $obj_bowstyle->create_default_style( $style_id, $style_name );
            $style_id = 2;
            $style_name = 'Compound';
            $response = $response && $obj_bowstyle->create_default_style( $style_id, $style_name );

            $result['status'] = $response && $result['status'];
        }

        $obj_scoreboard_style = new Scoreboard_Style($db);
        if( ! $obj_scoreboard_style->is_created() ){
            $style_id = 1;
            $bowstyle_id = 1;
            $style = 1;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 2;
            $bowstyle_id = 1;
            $style = 2;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 3;
            $bowstyle_id = 1;
            $style = 3;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 4;
            $bowstyle_id = 2;
            $style = 1;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 5;
            $bowstyle_id = 2;
            $style = 2;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $style_id = 6;
            $bowstyle_id = 2;
            $style = 3;
            $style_config = get_default_scoreboard_style_config( $bowstyle_id, $style );
            $response = $response && $obj_scoreboard_style->create_default_style( $style_id, $bowstyle_id, $style, $style_config );

            $result['status'] = $response && $result['status'];
        }

        $obj_livegame = new Live_Game($db);
        if( ! $obj_livegame->is_created() ){
            $response = $obj_livegame->create_default_game();

            $result['status'] = $response && $result['status'];
        }
    }
    else if ( $_GET['config_get'] == 'init' ) {
        $database = new Database();
        $db = $database->getConnection();

        $obj_livegame = new Live_Game($db);
        $response = $obj_livegame->get_data();

        $live_gameset_id = 0;
        $live_style_id = 0;
        $live_game_bowstyle_id = 0;
        $live_style_bowstyle_id = 0;
        $live_style_bowstyle_name = '';
        $live_style = '';

        if ( $response['status'] ) {
            $live_gameset_id = $response[ 'gameset_id' ];
            $live_style_id = $response[ 'scoreboard_style_id' ];
            $live_game_bowstyle_id = is_null($response[ 'game_bowstyle_id' ]) ? 0 : $response[ 'game_bowstyle_id' ];
            $live_style_bowstyle_id = is_null($response[ 'style_bowstyle_id' ]) ? 0 : $response[ 'style_bowstyle_id' ];
            $live_style_bowstyle_name = is_null($response[ 'style_bowstyle_name' ]) ? '' : $response[ 'style_bowstyle_name' ];
            $live_style = is_null($response[ 'style' ]) ? '' : $response[ 'style' ];
        }
        $has_live_game = $live_gameset_id > 0;
        $has_live_style = $live_style_id > 0;

        $form_scoreboard = '<h4 class="text-gray-4 text-center font-weight-light">Start Game</h4>';
        $livegame_data = array();
        if ( $has_live_game ) {
            $obj_config = new Config($db);

            $obj_score = new Score($db);
            $response = $obj_score->get_form_live();

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

                $livegame_data['gamedraw_id'] = $response[ 'scores' ][ 'gamedraw_id' ];
                $livegame_data['gameset_id'] = $response[ 'scores' ][ 'gameset_id' ];
                $livegame_data['gamemode_id'] = $response[ 'scores' ][ 'gamemode_id' ];
                $livegame_data['sets'] = $response[ 'scores' ][ 'sets' ];
                $livegame_data['contestants'] = $response[ 'scores' ][ 'contestants' ];
                $livegame_data[ 'bowstyle_id' ] = $live_game_bowstyle_id;
                $livegame_data[ 'style_config' ] = json_decode( $obj_config->get_scoreboard_form_style_config(), true)[ $live_game_bowstyle_id ];
                $template = TEMPLATE_DIR . 'scoreboard/form.php';
                $form_scoreboard = '';
                $form_scoreboard .= template( $template, $livegame_data);
            }
        }

        $obj_gamestatus = new GameStatus($db);
        $response = $obj_gamestatus->get_list();
        $gamestatuses = [ 'options' => '' ];
        if( $response['status'] ){
            $item_template = TEMPLATE_DIR . 'gamestatus/option.php';
            $renderitem = '<option value="0">Choose</option>';
            foreach( $response['gamestatuses'] as $item){
                $renderitem .= template( $item_template, $item);
            }
            $gamestatuses['options'] = $renderitem;
        }else{
            $result['message'] = "ERROR: status 0";
        }

        $obj_gamemode = new GameMode($db);
        $response = $obj_gamemode->get_list();
        $gamemodes = [ 'radios' => '' ];
        if( $response['status'] ){
            $item_template = TEMPLATE_DIR . 'gamemode/radio.php';
            $renderitem = '';
            foreach( $response['gamemodes'] as $item){
                $renderitem .= template( $item_template, $item);
            }
            $gamemodes['radios'] = $renderitem;
        }else{
            $response['message'] = "ERROR: mode 0";
        }

        $obj_bowstyle = new Bowstyle($db);
        $response = $obj_bowstyle->get_list();
        $bowstyles = [ 'radios' => ''];
        $bowstyle_options = '';
        if( $response['status'] ){
            $bowstyle_options = $response['bowstyles'];

            $item_template = TEMPLATE_DIR . 'bowstyle/radio.php';
            $renderitem = '';
            foreach( $response['bowstyles'] as $item){
                $renderitem .= template( $item_template, $item);
            }
            $bowstyles['radios'] = $renderitem;
        }else{
            $result['message'] = 'ERROR: get BOWSTYLE';
        }

        $obj_scoreboard_style = new Scoreboard_Style($db);
        $scoreboard_styles = [
            'config'    => [
                'visibility_class' => [
                    'activate_btn'      => 'hide',
                    'save_btn'          => 'hide',
                    'cancel_btn'        => 'hide',
                    'new_btn'           => 'hide',
                    'edit_btn'          => 'hide',
                    'delete_btn'        => 'hide',
                    'preview_wrapper'   => 'hide'
                ],
            ],
            'options'   => [
                'bowstyle' => '' ,
                'style'=>''
            ],
            'info' => [
                'bowstyle' => '',
                'style' => ''
            ],
            'preview'=> [
                // 'checkbox'=> '',
                'view'=> ''
            ]
        ];
        $item_template = TEMPLATE_DIR . 'bowstyle/option.php';
        $renderitem = '<option value="0">Choose</option>';
        foreach( $bowstyle_options as $item){
            $item['live'] = $live_style_bowstyle_id;
            $renderitem .= template( $item_template, $item);
        }
        $scoreboard_styles['options']['bowstyle'] = $renderitem;
        if ( $has_live_style ) {
            $response = $obj_scoreboard_style->get_list_by_bowstyle_id( $live_style_bowstyle_id );
            $renderitem = '<option value="0">Choose</option>';
            if( $response[ 'status' ] ) {
                $item_template = TEMPLATE_DIR . 'scoreboard/style/option.php';
                foreach( $response['styles'] as $item){
                    $item['live'] = $live_style_id;
                    $renderitem .= template( $item_template, $item);
                }
            }
            $scoreboard_styles['options']['style'] = $renderitem;
        }else{
            $scoreboard_styles['options']['style'] = '<option value="0">Choose</option>';
        }
        $scoreboard_styles['info']['bowstyle'] = $live_style_bowstyle_name;
        $scoreboard_styles['info']['style'] = $live_style;
        if ( $live_style_id == 0 ) {
            $scoreboard_styles['config']['visibility_class'] = [
                'activate_btn'      => 'hide',
                'save_btn'          => 'hide',
                'cancel_btn'        => 'hide',
                'new_btn'           => 'hide',
                'edit_btn'          => 'hide',
                'delete_btn'        => 'hide'
            ];
        } else {
            $scoreboard_styles['config']['visibility_class'] = [
                'activate_btn'      => '',
                'save_btn'          => 'hide',
                'cancel_btn'        => 'hide',
                'new_btn'           => '',
                'edit_btn'          => '',
                'delete_btn'        => ''
            ];
        }

        $response = $obj_scoreboard_style->get_config($live_style_id);

        if($response['status']){
            $preview_data = array();
            $preview_data['game_data'] = NULL;
            $preview_data['style_config'] = json_decode($response['style_config'],true);//var_dump($style_config);
            if( $has_live_game ){
                $livegame_data['style_config'] = NULL;
                $preview_data['game_data'] = $livegame_data;
            }

            $item_template = TEMPLATE_DIR . 'scoreboard/style/preview.php';
            $style_preview = '';
            $style_preview .= template( $item_template, $preview_data);
            $scoreboard_styles['preview']['view'] = $style_preview;

            // $item_template = TEMPLATE_DIR . 'scoreboard/style/checkbox.php';
            // $style_preview = '';
            // $style_preview .= template( $item_template, [ 'config' => $preview_data['style_config']]);
            // $scoreboard_styles['preview']['checkbox'] = $style_preview;
        }else{
            $result['message'] = 'ERROR: get Scoreboard Style ';
        }

        $obj_team = new Team($db);
        $response = $obj_team->get_list();
        $teams = [ 'table' => '', 'options' => '' ];
        if( $response['status'] ){
            $option_template = TEMPLATE_DIR . 'team/option.php';
            $item_template = TEMPLATE_DIR . 'team/item.php';
            $render_option = '<option value="0">Choose</option>';
            $render_item = '';
            foreach( $response['teams'] as $item){
                $render_option .= template( $option_template, $item);
                $render_item .= template( $item_template, $item);
            }
            $teams['options'] = $render_option;
            $teams['table'] = $render_item;
        }else{
            $result['message'] = "ERROR: status 0";
        }

        $obj_player = new Player($db);
        $response = $obj_player->get_list();
        $players = [ 'table' => '', 'options' => '' ];
        if( $response['status'] ){
            $item_template = TEMPLATE_DIR . 'player/item.php';
            $option_template = TEMPLATE_DIR . 'player/option.php';
            $render_item = '';
            $render_option = '<option value="0">Choose</option>';
            foreach( $response['players'] as $item){
                $render_item .= template( $item_template, $item);
                $render_option .= template( $option_template, $item);
            }
            $players['options'] = $render_option;
            $players['table'] = $render_item;

        }else{
            $result['message'] = "ERROR: status 0";
        }

        $obj_gamedraw = new GameDraw($db);
        $response = $obj_gamedraw->get_list();
        $gamedraws = [ 'table' => '', 'options' => '' ];
        if( $response['status'] ){
            $item_template = TEMPLATE_DIR . 'gamedraw/item.php';
            $render_item = '';
            $option_template = TEMPLATE_DIR . 'gamedraw/option.php';
            $render_option = '<option value="0">Choose</option>';
            foreach( $response['gamedraws'] as $item){
                $render_item .= template( $item_template, $item);
                $render_option .= template( $option_template, $item);
            }
            $gamedraws['table'] = $render_item;
            $gamedraws['options'] = $render_option;
        }else{
            $result['message'] = "ERROR: status 0";
        }

        $obj_gameset = new GameSet($db);
        $response = $obj_gameset->get_list();
        $gamesets = [ 'table' => '' ];
        if( $response['status'] ){
            $item_template = TEMPLATE_DIR . 'gameset/item.php';
            $render_item = '';
            foreach( $response['gamesets'] as $item){
                $render_item .= template( $item_template, $item);
            }
            $gamesets['table'] = $render_item;
        }else{
            $result['message'] = "ERROR: status 0";
        }

        $database->conn->close();

        $result['status'] = true;

        $result['form_scoreboard'] = $form_scoreboard;
        $result['gamestatuses'] = $gamestatuses;
        $result['gamemodes'] = $gamemodes;
        $result['bowstyles'] = $bowstyles;
        $result['scoreboard_styles'] = $scoreboard_styles;
        $result['teams'] = $teams;
        $result['players'] = $players;
        $result['gamedraws'] = $gamedraws;
        $result['gamesets'] = $gamesets;

    }
    else if( $_GET['config_get'] == 'scoreboard' && isset( $_GET['mode']) && is_numeric( $_GET['mode']) && $_GET['mode'] > 0){

        $mode = $_GET['mode'];

        $database = new Database();
        $db = $database->getConnection();

        $app_config = new Config($db);
        // $resAppCfg = $app_config->GetConfigs();
        $result_scoreboard_config = $app_config->get_scoreboard_config();

        $database->conn->close();

        if($result_scoreboard_config['status']){
            $scoreboard_cfg = json_decode($result_scoreboard_config['scoreboard_config']);

            switch ($mode) {
                case 1: case 2: case 3:
                    $result['config'] = $scoreboard_cfg[0];
                    break;
                case 4: case 5: case 6:
                    $result['config'] = $scoreboard_cfg[1];
                    break;
                case 7: case 8: case 9:
                    $result['config'] = $scoreboard_cfg[2];
                    break;
                default:
                    $result['config'] = null;
                    break;
            }
            $result['status'] = true;
        }else{
            $result['message'] = "Can't load config.";
        }

        /* $database->conn->close();

        if($resAppCfg['status']){
            foreach ($resAppCfg['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config'] = $scoreboard_cfg;
                    $result['status'] = true;
                    break;
                }
            }
        }else{
            $result['message'] = "Can't load config.";
        } */
    }
    echo json_encode($result);
}

if ( isset( $_POST['config_action']) ) {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_POST['config_action'] == 'update') {

        $config_id = isset($_POST['config_id']) ? $_POST['config_id'] : 0;
        $time_interval = isset($_POST['config_time_interval']) ? $_POST['config_time_interval'] : 1000;
        $active_mode = isset($_POST['config_active_mode']) ? $_POST['config_active_mode'] : 0;

        if( $config_id == 0 || $time_interval == 0 ){
            $result['message'] = "ERROR: All fields are required!";
        }else{

            $config_data = array(
                'id'            => $config_id,
                'time_interval' => $time_interval,
                'active_mode'   => $active_mode
            );
            $database = new Database();
            $db = $database->getConnection();

            $config = new Web_Config($db);
            $is_success = $config->set_data($config_data)->update();
            $database->conn->close();
            if( $is_success ){
                $result['status'] = $is_success;
                $result['action'] = 'update';
                $result['activated_mode'] = $active_mode;
            }

        }

    }
    echo json_encode($result);
}

//==================================================================================================
// Get Config
/* if (isset( $_GET['GetConfig']) && $_GET['GetConfig'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetConfig'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $config = new Web_Config($db);
        $tempRes = $config->get();

        $bowstyle_id=0;
        $livegame = new Live_Game($db);
        $resLiveGame = $livegame->GetLiveGameID();

        if($resLiveGame['status']){
            $gameset_id = $resLiveGame['live_game'];

            if($gameset_id!=0){
                $gameset = new GameSet($db);
                $gameset->SetID( $gameset_id );
                $tempGSRes = $gameset->GetGameSetByID();

                if( $tempGSRes['status'] ){
                    $gamedraw_id = $tempGSRes['gameset']['gamedraw_id'];
                    $gamedraw = new GameDraw($db);
                    $gamedraw->SetID($gamedraw_id);
                    $tempGDRes = $gamedraw->GetGameDrawByID();
                    if($tempGDRes['status']){
                        $bowstyle_id = $tempGDRes['gamedraw']['bowstyle_id'];
                    }
                }
            }
        }

        $app_config = new Config($db);
        $tempAppCfgRes = $app_config->GetConfigs();

        $database->conn->close();

        $result['status'] = $tempRes['status'] && $tempAppCfgRes['status'];

        if( $result['status'] ){
            $result['config'] = $tempRes['config'];
            foreach ($tempAppCfgRes['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config']['scoreboard'] = $scoreboard_cfg[$bowstyle_id];
                }
            }
        }
    }
    else if( $_GET['GetConfig'] == 'scoreboard'){

        $database = new Database();
        $db = $database->getConnection();

        $app_config = new Config($db);
        $resAppCfg = $app_config->GetConfigs();

        $database->conn->close();

        if($resAppCfg['status']){
            foreach ($resAppCfg['configs'] as $key => $val) {
                if($val['name']=='scoreboard'){
                    $scoreboard_cfg = json_decode($val['value']);
                    $result['config'] = $scoreboard_cfg;
                    $result['status'] = true;
                    break;
                }
            }
        }else{
            $result['message'] = "Can't load config.";
        }
    }
    echo json_encode($result);
} */

?>