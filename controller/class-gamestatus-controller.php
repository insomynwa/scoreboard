<?php
// Get Game Status
if ( isset( $_GET['gamestatus_get'])){
    $result = array(
        'status'    => false,
        'message'   => ''
    );

    if( $_GET['gamestatus_get'] == 'list') {
        $database = new Database();
        $db = $database->getConnection();

        $gamestatus = new GameStatus($db);
        $result_array = $gamestatus->get_gamestatus_list();

        $database->conn->close();

        if( $result_array['status'] ){
            $result['status'] = true;
            $result['has_value'] = $result_array['has_value'];
            if($result['has_value']){
                $item_template = TEMPLATE_DIR . 'gamestatus/option.php';
                $renderitem = '<option value="0">Select a status</option>';
                foreach( $result_array['gamestatuses'] as $item){
                    $renderitem .= template( $item_template, $item);
                }
                $result['gamestatuses'] = $renderitem;
            }else{
                $renderitem = '<option value="0">Select a status</option>';
                $renderitem .= template( $item_template, NULL);

                $result['gamestatuses'] = $renderitem;
                $result['message'] = "has no value";
            }
        }else{
            $result['message'] = "ERROR: status 0";
        }
    }
    echo json_encode($result);
}
?>