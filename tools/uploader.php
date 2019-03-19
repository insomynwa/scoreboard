<?php

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
$path = '../uploads/'; // upload directory

if(!empty($_POST['team_name']) && $_FILES['team_logo'] && $_POST['team_action'] !='')
{
    $team_action = $_POST['team_action'];
    $result = array(
        'status'    => false,
        'message'   => ''
    );
    $upload_status = false;

    $name = $_POST['team_name'];
    $team_initial = strtoupper( substr( $name, 0, 3) );
    $team_desc = $_POST['team_desc'];

    $img = $_FILES['team_logo']['name'];
    $tmp = $_FILES['team_logo']['tmp_name'];
    $is_upload = ($img != "");
    $final_image = "";
    $sql = "";

    if( $is_upload ) {

        // get uploaded file's extension
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        // can upload same image using rand function
        $final_image = rand(1000,1000000).$img;

        // check's valid format
        if(in_array($ext, $valid_extensions) )
        {
            $path = $path.strtolower($final_image);
            /* $pathB = $path.strtolower($final_imageB);  */

            if(move_uploaded_file($tmp,$path)/*  && move_uploaded_file($tmpB,$pathB) */)
            {
                $upload_status = true;
                if( $team_action == 'create' ){
                    $result['action'] = "create";
                    $sql = "INSERT team (team_name,team_logo,team_initial,team_desc) VALUES ('".$name."','". strtolower($final_image) ."', '". $team_initial ."', '". $team_desc ."' )";
                }else if ( $team_action == 'update'){
                    $team_id = $_POST['team_id'];
                    $result['action'] = "update";
                    $sql = "UPDATE team SET team_logo='{$final_image}', team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";
                }

                //include database configuration file
                include_once '../config/database.php';

                $database = new Database();
                $db = $database->getConnection();

                $tempRes = $db->query($sql);
                if ($tempRes){
                    $result['message'] = "Success";
                    $result['status'] = true;
                }else{
                    $result['message'] = "ERROR: create/update data";
                }
            }else{
                if ( $team_action == 'update'){
                    $result['action'] = "update";
                    $team_id = $_POST['team_id'];
                    $sql = "UPDATE team SET team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";

                    //include database configuration file
                    include_once '../config/database.php';

                    $database = new Database();
                    $db = $database->getConnection();

                    $tempRes = $db->query($sql);
                    if ($tempRes){
                        $result['message'] = "ERROR: update logo, SUCCESS: update data";
                        $result['status'] = true;
                    }else{
                        $result['message'] = "ERROR: update team";
                    }
                }else{
                    $result['message'] = "ERROR: upload logo";
                }
            }
        }
        else
        {
            $result['message'] = "ERROR: invalid format";
        }
    }else{
        if ( $team_action == 'update'){
            $team_id = $_POST['team_id'];
            $sql = "UPDATE team SET team_name='{$name}', team_initial='{$team_initial}', team_desc='{$team_desc}' WHERE team_id={$team_id}";

            //include database configuration file
            include_once '../config/database.php';

            $database = new Database();
            $db = $database->getConnection();

            $tempRes = $db->query($sql);
            if ($tempRes){
                $result['message'] = "ERROR: update logo, SUCCESS: update data";
                $result['status'] = true;
            }else{
                $result['message'] = "ERROR: no-logo, update team";
            }
        }else{
            $result['message'] = "ERROR: upload logo";
        }
    }
    echo json_encode($result);
}
?>