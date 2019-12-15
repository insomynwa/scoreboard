<?php

foreach ($config as $key => $value) {
    foreach($value as $vkey => $vval ){
        $val = $vval;
        if($vkey == 'visibility') {
            $val = $vval ? 'checked value="true"': 'value="false"';
        }
        ${$key.'_'.$vkey} = $val;
    }
}

// var_dump($logo_visibility);die;
?>
<tr>
    <td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-logo" class="ssv-cb" <?php echo $logo_visibility; ?> name="logo" id="ssv-logo-cb"> logo</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score1" class="ssv-cb" <?php echo $score1_visibility; ?> name="score1" id="ssv-score1-cb"> score 1</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score4" class="ssv-cb" <?php echo $score4_visibility; ?> name="score4" id="ssv-score4-cb"> score 4</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setpoint" class="ssv-cb" <?php echo $setpoint_visibility; ?> name="setpoint" id="ssv-setpoint-cb"> set point</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamepoint" class="ssv-cb" <?php echo $gamepoint_visibility; ?> name="gamepoint" id="ssv-gamepoint-cb"> game point</td>
</tr>
<tr>
    <td></td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-team" class="ssv-cb" <?php echo $team_visibility; ?> name="team" id="ssv-team-cb"> team</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score2" class="ssv-cb" <?php echo $score2_visibility; ?> name="score2" id="ssv-score2-cb"> score 2</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score5" class="ssv-cb" <?php echo $score5_visibility; ?> name="score5" id="ssv-score5-cb"> score 5</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-setscore" class="ssv-cb" <?php echo $setscore_visibility; ?> name="setscore" id="ssv-setscore-cb"> set score</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-gamescore" class="ssv-cb" <?php echo $gamescore_visibility; ?> name="gamescore" id="ssv-gamescore-cb"> game score</td>
</tr>
<tr>
    <td></td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-player" class="ssv-cb" <?php echo $player_visibility; ?> name="player" id="ssv-player-cb"> player</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score3" class="ssv-cb" <?php echo $score3_visibility; ?> name="score3" id="ssv-score3-cb"> score 3</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-score6" class="ssv-cb" <?php echo $score6_visibility; ?> name="score6" id="ssv-score6-cb"> score 6</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-timer" class="ssv-cb" <?php echo $timer_visibility; ?> name="timer" id="ssv-timer-cb"> timer</td>
    <td class="text-light"><input type="checkbox" data-class="scoreboard-style-preview-desc" class="ssv-cb" <?php echo $description_visibility; ?> name="description" id="ssv-description-cb"> description</td>
</tr>