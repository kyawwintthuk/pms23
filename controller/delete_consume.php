<?php
    include('../db/config.php');
    $ids = $_POST['ids'];

    foreach($ids as $id){
        $sql = "DELETE FROM `consume` WHERE id = ".$id;

        // die($sql);
    
        $result = $conn->query($sql);
    
        if($result == TRUE){
            echo 'True';
        }
    }


?>