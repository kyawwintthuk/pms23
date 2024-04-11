<?php
$property_id = $_POST['id'];   
$property_name = $_POST['name'];
$description = $_POST['description'];    
$price = $_POST['price'];
$quantity = $_POST['quantity'];

echo $property_id.'/'.$property_name.'/'.$description.'/'.$price.'/'.$quantity; 
?>