<?php //var_dump($contestants[0]);die;
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
<form id="form-scoreboard-a" action="controller.php" method="post" class="form form-scoreboard">
    <table class="table table-sm">
        <thead class="">
            <tr class="">
                <td class="form-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"></td>
                <td class="td-w form-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><span><?php echo $sets_str; ?></span></td>
                <td class="td-w form-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><span class="<?php echo $sets_css_helper_class; ?>"><?php echo $sets_str; ?></span></td>
                <td class="form-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><?php echo $style_config['timer']['label']; ?></td>
                <td class="form-scoreboard-score1 score-field <?php echo $style_config['score1']['visibility_class']; ?>"><?php echo $style_config['score1']['label']; ?></td>
                <td class="form-scoreboard-score2 score-field <?php echo $style_config['score2']['visibility_class']; ?>"><?php echo $style_config['score2']['label']; ?></td>
                <td class="form-scoreboard-score3 score-field <?php echo $style_config['score3']['visibility_class']; ?>"><?php echo $style_config['score3']['label']; ?></td>
                <td class="form-scoreboard-score4 score-field <?php echo $style_config['score4']['visibility_class']; ?>"><?php echo $style_config['score4']['label']; ?></td>
                <td class="form-scoreboard-score5 score-field <?php echo $style_config['score5']['visibility_class']; ?>"><?php echo $style_config['score5']['label']; ?></td>
                <td class="form-scoreboard-score6 score-field <?php echo $style_config['score6']['visibility_class']; ?>"><?php echo $style_config['score6']['label']; ?></td>
                <td class="form-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><?php echo $style_config['setpoint']['label']; ?></td>
                <td class="form-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><?php echo $style_config['setscore']['label']; ?></td>
                <td class="form-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><?php echo $style_config['gamepoint']['label']; ?></td>
                <td class="form-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><?php echo $style_config['gamescore']['label']; ?></td>
                <td class="td-w form-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><?php echo $style_config['description']['label']; ?></td>
                <th class=""></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="form-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"><div><img src="<?php echo $contestants[0]['logo']; ?>"></div></td>
                <td class="form-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['team']; ?></span></div></td>
                <td class="form-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><div><span><?php echo $contestants[0]['player']; ?></span></div></td>
                <td class="form-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><input type="text" readonly class="form-control font-italic " name="score_a_timer" id="score-a-timer" value="<?php echo $contestants[0]['score_timer']; ?>">s</td>
                <td class="form-scoreboard-score1 score-field <?php echo $style_config['score1']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score1" id="score-a-score1" value="<?php echo $contestants[0]['score_1']; ?>"></td>
                <td class="form-scoreboard-score2 score-field <?php echo $style_config['score2']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score2" id="score-a-score2" value="<?php echo $contestants[0]['score_2']; ?>"></td>
                <td class="form-scoreboard-score3 score-field <?php echo $style_config['score3']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score3" id="score-a-score3" value="<?php echo $contestants[0]['score_3']; ?>"></td>
                <td class="form-scoreboard-score4 score-field <?php echo $style_config['score4']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score4" id="score-a-score4" value="<?php echo $contestants[0]['score_4']; ?>"></td>
                <td class="form-scoreboard-score5 score-field <?php echo $style_config['score5']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score5" id="score-a-score5" value="<?php echo $contestants[0]['score_5']; ?>"></td>
                <td class="form-scoreboard-score6 score-field <?php echo $style_config['score6']['visibility_class']; ?>"><input type="text" class="form-control score-a-input-cls" name="score_a_score6" id="score-a-score6" value="<?php echo $contestants[0]['score_6']; ?>"></td>
                <td class="form-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><input type="text" class="form-control " name="score_a_setpoints" id="score-a-setpoints" data-setpoints="<?php echo $contestants[0]['set_points']; ?>" value="<?php echo $contestants[0]['set_points']; ?>"></td>
                <td class="form-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_a_setscores" id="score-a-setscores" value="<?php echo $contestants[0]['set_scores']; ?>"></td>
                <td class="form-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_a_gamepoints" id="score-a-gamepoints" data-gamepoints="<?php echo $contestants[0]['game_points']; ?>" value="<?php echo $contestants[0]['game_points']; ?>"></td>
                <td class="form-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_a_gamescores" id="score-a-gamescores" value="<?php echo $contestants[0]['game_scores']; ?>"></td>
                <td class="form-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><input type="text" class="form-control " name="score_a_desc" id="score-a-desc" value="<?php echo $contestants[0]['desc']; ?>"></td>
                <td class="pr-0 pt-0">
                    <input type="hidden" name="score_a_gamedraw_id" id="score-a-gamedraw-id" value="<?php echo $gamedraw_id; ?>">
                    <input type="hidden" name="score_a_gameset_id" id="score-a-gameset-id" value="<?php echo $gameset_id; ?>">
                    <input type="hidden" name="score_a_id" id="score-a-id" value="<?php echo $contestants[0]['score_id']; ?>">
                    <input type="hidden" name="score_action" value="update-a">
                    <input type="submit" value="UPDATE" id="score-a-submit" class="btn btn-primary no-boradius form-scoreboard-submit-btn">
                </td>
            </tr>
        </tbody>
    </table>
</form>
<form id="form-scoreboard-b" action="controller.php" method="post" class="form form-scoreboard">
    <table class="table table-sm">
        <thead class="">
            <tr class="">
                <td class="form-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"></td>
                <td class="td-w form-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"></td>
                <td class="td-w form-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"></td>
                <td class="form-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><?php echo $style_config['timer']['label']; ?></td>
                <td class="form-scoreboard-score1 score-field <?php echo $style_config['score1']['visibility_class']; ?>"><?php echo $style_config['score1']['label']; ?></td>
                <td class="form-scoreboard-score2 score-field <?php echo $style_config['score2']['visibility_class']; ?>"><?php echo $style_config['score2']['label']; ?></td>
                <td class="form-scoreboard-score3 score-field <?php echo $style_config['score3']['visibility_class']; ?>"><?php echo $style_config['score3']['label']; ?></td>
                <td class="form-scoreboard-score4 score-field <?php echo $style_config['score4']['visibility_class']; ?>"><?php echo $style_config['score4']['label']; ?></td>
                <td class="form-scoreboard-score5 score-field <?php echo $style_config['score5']['visibility_class']; ?>"><?php echo $style_config['score5']['label']; ?></td>
                <td class="form-scoreboard-score6 score-field <?php echo $style_config['score6']['visibility_class']; ?>"><?php echo $style_config['score6']['label']; ?></td>
                <td class="form-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><?php echo $style_config['setpoint']['label']; ?></td>
                <td class="form-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><?php echo $style_config['setscore']['label']; ?></td>
                <td class="form-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><?php echo $style_config['gamepoint']['label']; ?></td>
                <td class="form-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><?php echo $style_config['gamescore']['label']; ?></td>
                <td class="td-w form-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><?php echo $style_config['description']['label']; ?></td>
                <th class=""></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="form-scoreboard-logo text-light small <?php echo $style_config['logo']['visibility_class']; ?>"><div><img src="<?php echo $contestants[1]['logo']; ?>"></div></td>
                <td class="form-scoreboard-team <?php echo $style_config['team']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['team']; ?></span></div></td>
                <td class="form-scoreboard-player <?php echo $style_config['player']['visibility_class']; ?>"><div><span><?php echo $contestants[1]['player']; ?></span></div></td>
                <td class="form-scoreboard-timer <?php echo $style_config['timer']['visibility_class']; ?>"><input type="text" readonly class="form-control font-italic " name="score_b_timer" id="score-b-timer" value="<?php echo $contestants[1]['score_timer']; ?>">s</td>
                <td class="form-scoreboard-score1 <?php echo $style_config['score1']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score1" id="score-b-score1" value="<?php echo $contestants[1]['score_1']; ?>"></td>
                <td class="form-scoreboard-score2 <?php echo $style_config['score2']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score2" id="score-b-score2" value="<?php echo $contestants[1]['score_2']; ?>"></td>
                <td class="form-scoreboard-score3 <?php echo $style_config['score3']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score3" id="score-b-score3" value="<?php echo $contestants[1]['score_3']; ?>"></td>
                <td class="form-scoreboard-score4 <?php echo $style_config['score4']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score4" id="score-b-score4" value="<?php echo $contestants[1]['score_4']; ?>"></td>
                <td class="form-scoreboard-score5 <?php echo $style_config['score5']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score5" id="score-b-score5" value="<?php echo $contestants[1]['score_5']; ?>"></td>
                <td class="form-scoreboard-score6 <?php echo $style_config['score6']['visibility_class']; ?>"><input type="text" class="form-control score-b-input-cls" name="score_b_score6" id="score-b-score6" value="<?php echo $contestants[1]['score_6']; ?>"></td>
                <td class="form-scoreboard-setpoint <?php echo $style_config['setpoint']['visibility_class']; ?>"><input type="text" class="form-control " name="score_b_setpoints" id="score-b-setpoints" data-setpoints="<?php echo $contestants[1]['set_points']; ?>" value="<?php echo $contestants[1]['set_points']; ?>"></td>
                <td class="form-scoreboard-setscore <?php echo $style_config['setscore']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_b_setscores" id="score-b-setscores" value="<?php echo $contestants[1]['set_scores']; ?>"></td>
                <td class="form-scoreboard-gamepoint <?php echo $style_config['gamepoint']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_b_gamepoints" id="score-b-gamepoints" data-gamepoints="<?php echo $contestants[1]['game_points']; ?>" value="<?php echo $contestants[1]['game_points']; ?>"></td>
                <td class="form-scoreboard-gamescore <?php echo $style_config['gamescore']['visibility_class']; ?>"><input type="text" class="form-control " readonly name="score_b_gamescores" id="score-b-gamescores" value="<?php echo $contestants[1]['game_scores']; ?>"></td>
                <td class="form-scoreboard-desc <?php echo $style_config['description']['visibility_class']; ?>"><input type="text" class="form-control " name="score_b_desc" id="score-b-desc" value="<?php echo $contestants[1]['desc']; ?>"></td>
                <td class="pr-0 pt-0">
                    <input type="hidden" name="score_b_gamedraw_id" id="score-b-gamedraw-id" value="<?php echo $gamedraw_id; ?>">
                    <input type="hidden" name="score_b_gameset_id" id="score-b-gameset-id" value="<?php echo $gameset_id; ?>">
                    <input type="hidden" name="score_b_id" id="score-b-id" value="<?php echo $contestants[1]['score_id']; ?>">
                    <input type="hidden" name="score_action" value="update-b">
                    <input type="submit" value="UPDATE" id="score-b-submit" class="btn btn-primary no-boradius form-scoreboard-submit-btn">
                </td>
            </tr>
        </tbody>
    </table>
</form>