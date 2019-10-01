<?php
// Get Bowstyle
if (isset( $_GET['GetBowstyles']) && $_GET['GetBowstyles'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetBowstyles'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $bowstyle = new Bowstyle($db);
        $tempRes = $bowstyle->GetBowstyles();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['bowstyles'] = $tempRes['bowstyles'];
        }
    }
    echo json_encode($result);
}
?>