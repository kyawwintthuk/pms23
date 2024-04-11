<?php
include('../db/config.php');
$product_name = $_POST['product_name'];
$ids = $_POST['Ids'];
$sys_date = date('Y-m-d');

$location = null;
foreach ($ids as $id) {
    $arr = explode(',', $id);
    $product_id = $arr[0];      // product id → $arr[0]
    $location .= $arr[1] . ','; // location → $arr[1]

    // Check if product ID and location already exist
    $sql1 = "SELECT * FROM `product_price` WHERE `product_id` = '$arr[0]' AND `location` = '$arr[1]'";
    // echo $sql1.'<br>';
    $result1 = $conn->query($sql1);

    if ($result1->num_rows == 0) {
        $sql2 = "INSERT INTO `product_price` (`product_id`, `location`, `name`, `date`, `price_tax_included`, `price_tax_excluded`, `note`, `sys_date`)
        VALUES ('$product_id', '$arr[1]', '$product_name', '$sys_date',  0, 0, '', '$sys_date')";
        // echo $sql2;
        $result2 = $conn->query($sql2);

        if ($result2) {
            echo 'Inserted';
        } else {
            echo 'Error: ' . $conn->error;
        }
    } 
}

$location = rtrim($location, ',');

$sql3 = "INSERT INTO `product_location` (`product_id`, `name`,`location`,`category_id`,`location_id`,`sys_date`)
        VALUES ('$product_id','$product_name','$location', 1,1,'$sys_date') 
        ON DUPLICATE KEY UPDATE `name`= VALUES(`name`) , `location`= VALUES(`location`)";
// echo($sql);
$result3 = $conn->query($sql3);
if ($result3) {
    if ($conn->affected_rows > 0) {
        echo 'Inserted';
    } else {
        echo 'Updated';
    }
} else {
    echo 'Error: ' . $conn->error;
}
