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
$date = date('Y-m-d', strtotime($_POST['date']));

if (isset($_POST['add'])) {
    if ($operation == 'Property') {
        $sql = "
            INSERT INTO `property`(`name`, `description`, `image_url`, `price_tax_included` ,`price_tax_excluded`, `quantity`, `category_id`, `date`, `sys_date`) 
            VALUES ('$name','$description', '$image_url', '$price_tax_included', '$price_tax_excluded', '$quantity', '$category', '$date', '$date');
        ";
    }

    if ($operation == 'Category') {
        $sql = "
        INSERT INTO `category`(`name`, `description`, `sys_date`) 
        VALUES ('$name','$description', '" . date('Y-m-d') . "');
    ";
    }

    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['color'] = "#ccffd1"; //success     
        header('Location: ../add-property.php');
    } else {
        $_SESSION['color'] = "#f98686";  //error
        $_SESSION['message'] = "Error:" . $sql . "<br>" . $conn->error;
        header('Location: add.php');
    }
}

if (isset($_POST['edit'])) {

    if ($operation == 'Property') {
        $sql = "
            UPDATE `property` SET `name` = '$name', `description`= '$description', `image_url`= '$image_url', `price_tax_included`= '$price_tax_included', `price_tax_excluded`= '$price_tax_excluded',`quantity`= '$quantity', `category_id`= '$category', `date`= '$date' WHERE id = " . $id . ";";
    }

    if ($operation == 'Category') {
        $sql = "
        UPDATE `category` SET `name` = '$name', `description` = '$description' WHERE id = " . $id . ";";
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
        $row = $conn->query("SELECT COUNT(*) AS i FROM `property` WHERE id = ". $id)->fetch_assoc();        
        if($row['i'] > 0){
            $_SESSION['color'] = "#f98686";  //error
            $_SESSION['message'] = "You cannot delete this category because there are some properties assigned to it. 
            Please remove all properties from this category before deleting it.";
            header('Location: ../add-property.php?id='.$id.'.2');
            return; //Stop code execution
        }    
    }

    if($operation == 'Property'){
        $row = $conn->query("SELECT COUNT(*) AS i FROM `consume` WHERE property_id = ".$id)->fetch_assoc();
        if($row['i'] > 0){
            $_SESSION['color'] = "#f98686"; //error
            $_SESSION['message'] = "You cannot delete this property because there are some consume data assigned to it.
            Please remove all consume data from this property before deleting it.";
            header('Location: ../add-property.php?id='.$id.'.1');
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

