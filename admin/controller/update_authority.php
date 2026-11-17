<?php
    include('../../config.php');
    $updated_date = date('Y-m-d h:i:s');
    $sql = "UPDATE `ip_list` SET authority = ".$_POST['authority']." , updated_date = CURRENT_TIMESTAMP() WHERE id = ".$_POST['id']."";
    $result = $conn->query($sql);
    // echo $sql;
    if($result == TRUE){
        echo 'success';
    }
?>