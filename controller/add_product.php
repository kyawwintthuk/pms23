<?php
include('../db/config.php');
session_start();

$id = isset($_POST['id']) ? $_POST['id'] : '';

$operation = $_POST['operation'];
$category = $_POST['category'];
$name = $_POST['name'];
$description = $_POST['description'];
$image_url = $_POST['image_url'];
$price_tax_included = $_POST['price_tax_included'];
$price_tax_excluded = $_POST['price_tax_excluded'];
$quantity = $_POST['quantity'];
$tax_percentage = $_POST['tax_percentage'];
$date = date('Y-m-d', strtotime($_POST['date']));

if (isset($_POST['add'])) {
    if ($operation == 'Product') {
        $sql = "
            INSERT INTO `product`(`name`, `description`, `image_url`, `price_tax_included` ,`price_tax_excluded`, `quantity`, `category_id`, `date`, `sys_date`) 
            VALUES ('$name','$description', '$image_url', '$price_tax_included', '$price_tax_excluded', '$quantity', '$category', '$date', '$date');
        ";
    }

    if ($operation == 'Category') {
        $sql = "
        INSERT INTO `category`(`name`, `description`, `tax_percentage`, `sys_date`) 
        VALUES ('$name','$description', '$tax_percentage', '" . date('Y-m-d') . "');
    ";
    }

    if ($operation == 'Location') {
        $sql = "
            INSERT INTO `location`(`name`,`description`,`sys_date`)
            VALUES ('$name','$description', '" . date('Y-m-d') . "');
        ";
    }

    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['color'] = "#ccffd1"; //success   
        $_SESSION['radio_button']  = $operation;
        header('Location: ../add-product.php');
    } else {
        $_SESSION['color'] = "#f98686";  //error
        $_SESSION['radio_button']  = $operation;
        $_SESSION['message'] = "Error:" . $sql . "<br>" . $conn->error;
        header('Location: ../add-product.php');
    }
}

if (isset($_POST['edit'])) {

    if ($operation == 'Product') {
        $sql = "
            UPDATE `product` SET `name` = '$name', `description`= '$description', `image_url`= '$image_url', `price_tax_included`= '$price_tax_included', `price_tax_excluded`= '$price_tax_excluded',`quantity`= '$quantity', `category_id`= '$category', `date`= '$date' WHERE id = " . $id . ";";
    }

    if ($operation == 'Category') {
        $sql = "
        UPDATE `category` SET `name` = '$name', `description` = '$description', `tax_percentage` = '$tax_percentage' WHERE id = " . $id . ";";
    }

    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['color'] = "#ccffd1"; //success        
        header('Location: ../index.php');
    } else {
        $_SESSION['color'] = "#f98686";  //error
        $_SESSION['message'] = "Error:" . $sql . "<br>" . $conn->error;
        header('Location: add.php');
    }
    // $id = '';
    // die($sql);
}

if (isset($_POST['delete'])) {
    if($operation == 'Category'){        
        $row = $conn->query("SELECT COUNT(*) AS i FROM `product` WHERE id = ". $id)->fetch_assoc();        
        if($row['i'] > 0){
            $_SESSION['color'] = "#f98686";  //error
            $_SESSION['message'] = "You cannot delete this category because there are some products assigned to it. 
            Please remove all products from this category before deleting it.";
            header('Location: ../add-product.php?id='.$id.'.2');
            return; //Stop code execution
        }    
    }

    if($operation == 'Product'){
        $row = $conn->query("SELECT COUNT(*) AS i FROM `consume` WHERE product_id = ".$id)->fetch_assoc();
        if($row['i'] > 0){
            $_SESSION['color'] = "#f98686"; //error
            $_SESSION['message'] = "You cannot delete this product because there are some consume data assigned to it.
            Please remove all consume data from this product before deleting it.";
            header('Location: ../add-product.php?id='.$id.'.1');
            return; //Stop code execution
        }
    }

    $sql = "DELETE FROM `" . $operation . "` WHERE id = " . $id;
    // die($sql);
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['color'] = "#ccffd1"; //success
        header('Location: ../index.php');
    } else {
        $_SESSION['color'] = "#f98686";  //error
        $_SESSION['message'] = "Error:" . $sql . "<br>" . $conn->error;
        header('Location: add.php');
    }
}

