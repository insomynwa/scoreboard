<?php

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
$path = '../uploads/'; // upload directory

if(!empty($_POST['team_name']) && $_FILES['team_logo'] && $_POST['team_action'] =='create')
{
    $status = false;
    $img = $_FILES['team_logo']['name'];
    $tmp = $_FILES['team_logo']['tmp_name'];

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
            // echo "<img src='$path' />";
            $name = $_POST['team_name'];

            //include database configuration file
            include_once '../config/database.php';

            $database = new Database();
            $db = $database->getConnection();

            //insert form data in the database basename($_FILES['uploadedFile']['name'])
            // $insert = $db->query("INSERT tim (tim_name,tim_logo) VALUES ('".$name."','".$path."')");
            $insert = $db->query("INSERT tim (tim_name,tim_logo) VALUES ('".$name."','". strtolower($final_image) ."')");
            // $insert = $db->query("INSERT tim (tim_name,tim_logo) VALUES ('".$nameB."','".$pathB."')");

            if($insert){
                $status = true;
            }
            $database->conn->close();

            // echo strtolower($final_image);
            //echo $insert?'ok':'err';
            // return json_encode(array('logoA'=>$path/* , 'logoB'=>$pathB */));
        }
    }
    else
    {
        echo 'invalid';
    }
    echo json_encode(array('status'=>$status));
}
?>