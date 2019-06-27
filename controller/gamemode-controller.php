<?php

// Get Game Mode
if (isset( $_GET['GetGameModes']) && $_GET['GetGameModes'] != '') {
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    if( $_GET['GetGameModes'] == 'all') {

        $database = new Database();
        $db = $database->getConnection();

        $gamemode = new GameMode($db);
        $tempRes = $gamemode->GetGameModes();

        $database->conn->close();

        $result['status'] = $tempRes['status'];

        if( $result['status'] ){
            $result['gamemodes'] = $tempRes['gamemodes'];
        }
    }
    echo json_encode($result);
}
?>