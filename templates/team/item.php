<tr>
    <td class='text-gray-4 border-gray-3 pl-0'>
        <button data-teamid='<?php echo $id; ?>' class='btn btn-sm btn-outline-danger border-0 rounded-circle font-weight-bolder team-delete-btn-cls'>X</button>
        <button data-teamid='<?php echo $id; ?>' class='btn btn-sm btn-outline-warning-2 border-0 rounded-circle team-update-btn-cls'><i class='fas fa-pen'></i></button>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <img style="max-height:46px;" src='uploads/<?php echo $logo; ?>'>
    </td>
    <td class='text-gray-4 font-weight-light border-gray-3'>
        <span><?php echo $name; ?></span>
    </td>
</tr>