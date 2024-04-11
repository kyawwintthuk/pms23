<?php

include('../db/config.php');
$id = $_POST['id'];
$column_name = $_POST['column_name'];

if ($column_name == 'price_tax_included' || $column_name == 'price_tax_excluded' || $column_name == 'note') {
    $value = $_POST['value'];
} else {
    $value = date('Y-m-d', strtotime($_POST['value']));
}

$sql = " UPDATE `consume` SET `" . $column_name . "` = '" . $value . "' WHERE id = " . $id . "; ";

// echo ($sql);

$result = $conn->query($sql);

if($result == TRUE){
    echo 'True';
}
