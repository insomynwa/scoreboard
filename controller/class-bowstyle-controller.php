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
        $tempRes = $bowstyle->get_bowstyle_list();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['bowstyles'] = $tempRes['bowstyles'];
        }
    }
    echo json_encode($result);
}
?>