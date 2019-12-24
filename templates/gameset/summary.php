<?php
$contestant_a = $summary[0];
$contestant_b = $summary[1];
?>
<thead class='bg-dark text-white'>
    <tr class='bg-gray-2'>
        <th class='text-gray-4 font-weight-normal border-0'>Point</th>
        <th class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_a['contestant_name']; ?></th>
        <th class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_b['contestant_name']; ?></th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class='text-gray-4 font-weight-bold border-0'>1</td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_a['score_1']; ?></td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_b['score_1']; ?></td>
    </tr>
    <tr>
        <td class='text-gray-4 font-weight-bold border-0'>2</td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_a['score_2']; ?></td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_b['score_2']; ?></td>
    </tr>
    <tr class='text-gray-4 font-weight-normal border-0'>
        <td class='text-gray-4 font-weight-bold border-0'>3</td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_a['score_3']; ?></td>
        <td class='text-gray-4 font-weight-normal border-0'><?php echo $contestant_b['score_3']; ?></td>
    </tr>
    <tr class='d-none'>
        <td class='text-gray-4 font-weight-normal border-0'>4</td>
        <td class='d-none'></td>
        <td class='d-none'></td>
    </tr>
    <tr class='d-none'>
        <td class='text-gray-4 font-weight-normal border-0'>5</td>
        <td class='d-none'></td>
        <td class='d-none'></td>
    </tr>
    <tr class='d-none'>
        <td class='text-gray-4 font-weight-normal border-0'>6</td>
        <td class='d-none'></td>
        <td class='d-none'></td>
    </tr>
    <tr class='bg-gray-3'>
        <td class='text-warning font-weight-bold border-gray-3'>Set Scores</td>
        <td class='text-warning font-weight-bold border-gray-3'><?php echo $contestant_a['setscores']; ?></td>
        <td class='text-warning font-weight-bold border-gray-3'><?php echo $contestant_b['setscores']; ?></td>
    </tr>
    <tr class='bg-gray-3'>
        <td class='text-success font-weight-bold border-gray-3'>Set Points</td>
        <td class='text-success font-weight-bold border-gray-3'><?php echo $contestant_a['setpoints']; ?></td>
        <td class='text-success font-weight-bold border-gray-3'><?php echo $contestant_b['setpoints']; ?></td>
    </tr>
</tbody>