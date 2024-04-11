<?php
// 20230626 Monday ～ 20230629 Thursday
//20230702 Sunday 01:23 PM 
include('../db/config.php');
$id = $_POST['id'];  
$column_name = $_POST['column_name'];
$value = $_POST['value'];

if ($column_name == 'date')
{
    $value = date('Y-m-d', strtotime($_POST['value']));
} else if($column_name == 'price_tax_included' || $column_name == 'price_tax_excluded') {
    $value = str_replace(',', '', $_POST['value']);
}else{
    $value = $_POST['value'];
}

$sql = " UPDATE `product_price` SET `" . $column_name . "` = '" . $value . "' WHERE id = '" . $id . "'; ";

// echo ($sql);

$result = $conn->query($sql);

if($result == TRUE){
    echo 'True';
}else{
    echo 'False';
}