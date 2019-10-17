<?php if(sizeof($summaries) > 0): ?>
<h5 class="text-light font-weight-light"><?php echo $summaries[0]['player_a'] ?> vs <?php echo $summaries[0]['player_b'] ?></h5>
<p class="text-info">[<span class="small"><?php echo strtoupper($summaries[0]['style'])?></span> - <span class="small"><?php echo strtolower($summaries[0]['gamemode']); ?></span>]</p>
<table class="table table-sm" id="gamedraw-info-modal-table">
    <thead>
        <tr class="bg-gray-2">
            <th class="text-gray-4 font-weight-normal border-0">Set</th>
            <th class="text-gray-4 font-weight-normal border-0"><?php echo $summaries[0]['player_a'] ?></th>
            <th class="text-gray-4 font-weight-normal border-0"><?php echo $summaries[0]['player_b'] ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $total_score_a = 0;
        $total_score_b = 0;
        $gameWinnerAClass = 'text-gray-4';
        $gameWinnerBClass = 'text-gray-4';
    ?>
        <?php for($i=0; $i<sizeof($summaries); $i++): ?>
        <?php
            $winnerACSS = 'text-gray-4';
            $winnerBCSS = $winnerACSS;
            $score_a = $summaries[$i]['score_a'];
            $score_b = $summaries[$i]['score_b'];
            $total_score_a += $summaries[$i]['score_a'];
            $total_score_b += $summaries[$i]['score_b'];
            if ($score_a > $score_b) {
                $winnerACSS = 'text-success';
            } else if ($score_a < $score_b) {
                $winnerBCSS = 'text-success';
            }
        ?>
        <tr>
            <td class="text-gray-4 font-weight-bold border-gray-2"><?php echo $summaries[$i]['sets']; ?></td>
            <td class="font-weight-bold border-gray-2 <?php echo $winnerACSS; ?>"><span><?php echo $summaries[$i]['score_a']; ?></span></td>
            <td class="font-weight-bold border-gray-2 <?php echo $winnerBCSS; ?>"><span><?php echo $summaries[$i]['score_b']; ?></span></td>
        </tr>
        <?php endfor; ?>
        <?php
            if ($total_score_a > $total_score_b) {
                $gameWinnerAClass = "text-success";
            } else if ($total_score_a < $total_score_b) {
                $gameWinnerBClass = "text-success";
            }
        ?>
        <tr class='bg-gray-3'>
            <td class="text-warning font-weight-bold border-gray-3">TOTAL</td>
            <td class="font-weight-bold border-gray-3 bg-gray-3 <?php echo $gameWinnerAClass; ?>"><span><?php echo $total_score_a; ?></span></td>
            <td class="font-weight-bold border-gray-3 bg-gray-3 <?php echo $gameWinnerBClass; ?>"><span><?php echo $total_score_b; ?></span></td>
        </tr>
    </tbody>
</table>
<?php endif; ?>