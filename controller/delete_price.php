<?php
    include('../db/config.php');
    $ids = $_POST['ids'];

    foreach($ids as $id){
        $sql = "DELETE FROM `product_price` WHERE id = ".$id;

        // die($sql);
    
        $result = $conn->query($sql);
    
        if($result == TRUE){
            echo 'True';
        }
    }


?>