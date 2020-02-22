<?php

$sets_str = 'Set X';
$sets_css_helper_class = '';

foreach ($style_config as $key => $value) {
    foreach($value as $vkey => $vval ){
        ${$key.'_'.$vkey} = $vval;
    }
}
if( ! is_null($game_data) ) {
    foreach ($game_data as $key => $value) {
        if( $key == 'gamemode_id' || $key == 'bowstyle_id'){
            ${$key} = $value;
        }else{
            foreach( (array)$value as $vkey => $vval ){
                if( $key == 'contestants' ) {
                    foreach( $vval as $ckey => $cval ){
                        ${$key.'_'. $vkey . '_' . $ckey} = $cval;
                    }
                }else{
                    ${$key .'_'. $vkey} = $vval;
                }
            }
        }
    }

    if( $bowstyle_id == 1 ) { // Recurve
        $sets_str = 'Set ' . $sets_curr_set;
    }else if( $bowstyle_id == 2 ) { // Compound
        $sets_str = 'Set ' . $sets_curr_set . ' of ' . $sets_end_set;
    }

    if( ( $contestants_0_player == '' | $contestants_1_player == '' ) && $gamemode_id == 1 ) {
        $player_visibility_class = 'hide';
    }
    if( ( $contestants_0_team == '' | $contestants_1_team == '' ) && $gamemode_id == 2 ) {
        $team_visibility_class = 'hide';
    }

}

if( $team_visibility_class == '' ) {
    $sets_css_helper_class = 'hide';
}
// $logo_label = value
// var_dump($gamescore_label);
// var_dump($gamescore_visibility_class);die;
?>
<thead>
    <tr>
        <td class="scoreboard-style-preview-logo text-light small <?php echo $logo_visibility_class; ?>"></td>
        <td class="td-w scoreboard-style-preview-team <?php echo $team_visibility_class; ?>"><span><?php echo $sets_str; ?></span></td>
        <td class="td-w scoreboard-style-preview-player <?php echo $player_visibility_class; ?>"><span class="<?php echo $sets_css_helper_class; ?>"><?php if($game_data!=null) echo $sets_str; ?></span></td>
        <td class="scoreboard-style-preview-timer <?php echo $timer_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score1 <?php echo $score1_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score2 <?php echo $score2_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score3 <?php echo $score3_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score4 <?php echo $score4_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score5 <?php echo $score5_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-score6 <?php echo $score6_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-setpoint <?php echo $setpoint_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-setscore <?php echo $setscore_visibility_class; ?>"></td>
        <td class="scoreboard-style-preview-gamepoint <?php echo $gamepoint_visibility_class; ?>"><span>Set pts</span></td>
        <td class="scoreboard-style-preview-gamescore <?php echo $gamescore_visibility_class; ?>"><span>Total</span></td>
        <td class="td-w scoreboard-style-preview-desc <?php echo $description_visibility_class; ?>"></td>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="scoreboard-style-preview-logo text-light small <?php echo $logo_visibility_class; ?>"><div><img src="<?php if($game_data!=null) echo $contestants_0_logo; ?>"></td>
        <td class="scoreboard-style-preview-team <?php echo $team_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_team; else echo 'Team A'; ?></span></div></td>
        <td class="scoreboard-style-preview-player <?php echo $player_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_player; else echo 'Player A'; ?></span></div></td>
        <td class="scoreboard-style-preview-timer <?php echo $timer_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_timer; else echo '120'; ?>s</span></div></td>
        <td class="scoreboard-style-preview-score1 <?php echo $score1_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_1; else echo '1'; ?></span></div></td>
        <td class="scoreboard-style-preview-score2 <?php echo $score2_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_2; else echo '2'; ?></span></div></td>
        <td class="scoreboard-style-preview-score3 <?php echo $score3_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_3; else echo '3'; ?></span></div></td>
        <td class="scoreboard-style-preview-score4 <?php echo $score4_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_4; else echo '4'; ?></span></div></td>
        <td class="scoreboard-style-preview-score5 <?php echo $score5_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_5; else echo '5'; ?></span></div></td>
        <td class="scoreboard-style-preview-score6 <?php echo $score6_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_score_6; else echo '6'; ?></span></div></td>
        <td class="scoreboard-style-preview-setpoint <?php echo $setpoint_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_set_points; else echo '0'; ?></span></div></td>
        <td class="scoreboard-style-preview-setscore <?php echo $setscore_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_set_scores; else echo '11'; ?></span></div></td>
        <td class="scoreboard-style-preview-gamepoint <?php echo $gamepoint_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_game_points; else echo '0'; ?></span></div></td>
        <td class="scoreboard-style-preview-gamescore <?php echo $gamescore_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_game_scores; else echo '11'; ?></span></div></td>
        <td class="scoreboard-style-preview-desc <?php echo $description_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_0_desc; else echo 'Description A'; ?></span></div></td>
    </tr>
    <tr>
        <td class="scoreboard-style-preview-logo text-light small <?php echo $logo_visibility_class; ?>"><div><img src="<?php if($game_data!=null) echo $contestants_1_logo; ?>"></div></td>
        <td class="scoreboard-style-preview-team <?php echo $team_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_team; else echo 'Team B'; ?></span></div></td>
        <td class="scoreboard-style-preview-player <?php echo $player_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_player; else echo 'Player A'; ?></span></div></td>
        <td class="scoreboard-style-preview-timer <?php echo $timer_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_timer; else echo '120'; ?>s</span></div></td>
        <td class="scoreboard-style-preview-score1 <?php echo $score1_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_1; else echo '7'; ?></span></div></td>
        <td class="scoreboard-style-preview-score2 <?php echo $score2_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_2; else echo '8'; ?></span></div></td>
        <td class="scoreboard-style-preview-score3 <?php echo $score3_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_3; else echo '9'; ?></span></div></td>
        <td class="scoreboard-style-preview-score4 <?php echo $score4_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_4; else echo '10'; ?></span></div></td>
        <td class="scoreboard-style-preview-score5 <?php echo $score5_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_5; else echo '11'; ?></span></div></td>
        <td class="scoreboard-style-preview-score6 <?php echo $score6_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_score_6; else echo '12'; ?></span></div></td>
        <td class="scoreboard-style-preview-setpoint <?php echo $setpoint_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_set_points; else echo '2'; ?></span></div></td>
        <td class="scoreboard-style-preview-setscore <?php echo $setscore_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_set_scores; else echo '57'; ?></span></div></td>
        <td class="scoreboard-style-preview-gamepoint <?php echo $gamepoint_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_game_points; else echo '2'; ?></span></div></td>
        <td class="scoreboard-style-preview-gamescore <?php echo $gamescore_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_game_scores; else echo '57'; ?></span></div></td>
        <td class="scoreboard-style-preview-desc <?php echo $description_visibility_class; ?>"><div><span><?php if($game_data!=null) echo $contestants_1_desc; else echo 'Description B'; ?></span></div></td>
    </tr>
</tbody>