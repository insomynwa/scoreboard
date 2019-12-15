<?php

$sets_str = '';
$sets_css_helper_class = '';

if( $bowstyle_id == 1 ) { // Recurve
    $sets_str = 'Set ' . $sets['curr_set'];
}else if( $bowstyle_id == 2 ) { // Compound
    $sets_str = 'Set ' . $sets['curr_set'] . ' of ' . $sets['end_set'];
}

if( ( $contestants[0]['player'] == '' | $contestants[1]['player'] == '' ) && $gamemode_id == 1 ) {
    $style_config['player']['visibility_class'] = 'hide';
}
if( ( $contestants[0]['team'] == '' | $contestants[1]['team'] == '' ) && $gamemode_id == 2 ) {
    $style_config['team']['visibility_class'] = 'hide';
}

if( $style_config['team']['visibility_class'] == '' ) {
    $sets_css_helper_class = 'hide';
}

?>
<thead>
    <tr>
        <td class="live-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"></td>
        <td class="td-w live-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><span><?php echo $sets_str; ?></span></td>
        <td class="td-w live-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><span class="<?php echo $sets_css_helper_class; ?>"><?php echo $sets_str; ?></span></td>
        <td class="live-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score1 <?php echo $style_config['score1']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score2 <?php echo $style_config['score2']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score3 <?php echo $style_config['score3']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score4 <?php echo $style_config['score4']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score5 <?php echo $style_config['score5']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-score6 <?php echo $style_config['score6']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"></td>
        <td class="live-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><span>Set pts</span></td>
        <td class="live-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><span>Total</span></td>
        <td class="td-w live-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"></td>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="live-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"><div><img src="<?php echo $contestants[0]['logo']; ?>"></div></td>
        <td class="live-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['team']; ?></span></div></td>
        <td class="live-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['player']; ?></span></div></td>
        <td class="live-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_timer']; ?>s</span></div></td>
        <td class="live-scoreboard-score1 <?php echo $style_config['score1']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_1']; ?></span></div></td>
        <td class="live-scoreboard-score2 <?php echo $style_config['score2']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_2']; ?></span></div></td>
        <td class="live-scoreboard-score3 <?php echo $style_config['score3']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_3']; ?></span></div></td>
        <td class="live-scoreboard-score4 <?php echo $style_config['score4']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_4']; ?></span></div></td>
        <td class="live-scoreboard-score5 <?php echo $style_config['score5']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_5']; ?></span></div></td>
        <td class="live-scoreboard-score6 <?php echo $style_config['score6']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['score_6']; ?></span></div></td>
        <td class="live-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['set_points']; ?></span></div></td>
        <td class="live-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['set_scores']; ?></span></div></td>
        <td class="live-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['game_points']; ?></span></div></td>
        <td class="live-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['game_scores']; ?></span></div></td>
        <td class="live-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['desc']; ?></span></div></td>
    </tr>
    <tr>
        <td class="live-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"><div><img src="<?php echo $contestants[1]['logo']; ?>"></div></td>
        <td class="live-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['team']; ?></span></div></td>
        <td class="live-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['player']; ?></span></div></td>
        <td class="live-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_timer']; ?>s</span></div></td>
        <td class="live-scoreboard-score1 <?php echo $style_config['score1']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_1']; ?></span></div></td>
        <td class="live-scoreboard-score2 <?php echo $style_config['score2']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_2']; ?></span></div></td>
        <td class="live-scoreboard-score3 <?php echo $style_config['score3']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_3']; ?></span></div></td>
        <td class="live-scoreboard-score4 <?php echo $style_config['score4']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_4']; ?></span></div></td>
        <td class="live-scoreboard-score5 <?php echo $style_config['score5']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_5']; ?></span></div></td>
        <td class="live-scoreboard-score6 <?php echo $style_config['score6']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['score_6']; ?></span></div></td>
        <td class="live-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['set_points']; ?></span></div></td>
        <td class="live-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['set_scores']; ?></span></div></td>
        <td class="live-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['game_points']; ?></span></div></td>
        <td class="live-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['game_scores']; ?></span></div></td>
        <td class="live-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['desc']; ?></span></div></td>
    </tr>
</tbody>