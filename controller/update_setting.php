<?php
// 2023-07-13 Thursday 17:00PM
// To store radio button checked value

include('../db/config.php');
$product_id = $_POST['product_id'];
$value = $_POST['value'];

$sql = "INSERT INTO `setting` (`product_id`,`value`) VALUES ('$product_id','$value')
        ON DUPLICATE KEY UPDATE `product_id`=VALUES(`product_id`), `value` = vALUES(`value`)";

$result = $conn->query($sql);
if($result){
    if($conn->affected_rows > 0){
        echo 'Inserted';
    } else {
        echo 'Updated';
    }
}else {
    echo 'Error: ' . $conn->error;
}

