<tr>
    <td class='text-gray-4 border-gray-3 pl-0'>
        <?php
            $statusTxt = "";
            if ($gamestatus_id == 2) {
                $statusTxt .= "disabled='disabled'";
            }
        ?>
        <button data-gamesetid='<?php echo $id; ?>' <?php echo $statusTxt; ?> class='btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder gameset-delete-btn-cls'>X</button>
        <button data-gamesetid='<?php echo $id; ?>' <?php echo $statusTxt; ?> class='btn btn-sm btn-outline-warning-2 border-0 rounded-circle gameset-update-btn-cls'><i class='fas fa-pen'></i></button>
    </td>
    <td class='text-info font-weight-light border-gray-3'>
        <?php echo $game_num . '.'; ?>
        <span class='small'>[<?php echo strtoupper($bowstyle_name);?>]</span>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <?php echo $contestant_a_name . ' vs ' . $contestant_b_name; ?>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <?php echo $set_num; ?>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <?php
            if ( $gamestatus_id < 3) {
                if ($gamestatus_name == 'Live') { ?>
                    <button data-gamesetid='<?php echo $id; ?>' class='btn btn-sm btn-danger rounded-circle gameset-stoplive-btn-cls mr-3'><i class='fas fa-stop-circle'></i></button>
        <?php   } else { ?>
                    <button data-gamesetid='<?php echo $id; ?>' class='btn btn-sm btn-success rounded-circle gameset-live-btn-cls mr-3'><i class='fas fa-play-circle'></i></button>
        <?php   }
            } else { ?>
                <button data-gamesetid='<?php echo $id; ?>' class='btn btn-sm btn-primary rounded-circle gameset-view-btn-cls mr-3'><i class='fas fa-eye'></i></button>
        <?php
            }
        ?>
        <span class='small'><?php echo $gamestatus_name; ?></span>
    </td>
</tr>