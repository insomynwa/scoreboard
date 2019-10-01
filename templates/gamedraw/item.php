<tr>
    <td class='text-gray-4 border-gray-3 pl-0'>
        <button data-gamedrawid='<?php echo $id; ?>' class='btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder gamedraw-delete-btn-cls'>X</button>
        <button data-gamedrawid='<?php echo $id; ?>' class='btn btn-sm btn-outline-warning border-0 rounded-circle gamedraw-update-btn-cls'><i class='fas fa-pen'></i></button>
    </td>
    <td class='text-info font-weight-light border-gray-3'>
        [<span class='small'><?php echo strtoupper($bowstyle_name) . '</span> - <span class="small">' . strtolower($gamemode_name); ?></span>]
    </td>
    <td class='text-gray-4 font-weight-normal border-gray-3'>
        <?php echo $num; ?>.
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <?php echo "$contestant_a_name vs $contestant_b_name"; ?>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <button data-gamedrawid='<?php echo $id; ?>' class='btn btn-sm btn-outline-success rounded-circle border-0 gamedraw-view-btn-cls mr-3'><i class='fas fa-eye'></i></button>
    </td>
</tr>