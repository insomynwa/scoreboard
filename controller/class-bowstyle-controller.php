<?php
// Get Bowstyle
if (isset( $_GET['bowstyle_get']) && $_GET['bowstyle_get'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['bowstyle_get'] == 'list') {

        $database = new Database();
        $db = $database->getConnection();

        $bowstyle = new Bowstyle($db);
        $result_query = $bowstyle->get_bowstyle_list();

        $database->conn->close();

        $result['status'] = $result_query['status'];

        if( $result['status'] ){
            $item_template = TEMPLATE_DIR . 'bowstyle/option.php';
            $bostyle_option = '';
            foreach( $result_query['bowstyles'] as $item){
                $item['live'] = 0;
                $bostyle_option .= template( $item_template, $item);
            }
            $result['bowstyle_option'] = '<option value="0">Choose</option>' . $bostyle_option;

            $item_template = TEMPLATE_DIR . 'bowstyle/radio.php';
            $bostyle_radio = '';
            foreach( $result_query['bowstyles'] as $item){
                $bostyle_radio .= template( $item_template, $item);
            }
            $result['bowstyle_radio'] = $bostyle_radio;
            // $result['bowstyles'] = $result_query['bowstyles'];
        }else{
            $result['message'] = 'ERROR: get BOWSTYLE';
        }
    }
    echo json_encode($result);
}
?>