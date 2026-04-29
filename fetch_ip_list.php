<?php
    include('db/config.php');
    session_start();
    $ip_address = $_POST['ip_address'];
    $device_name = $_POST['device_name'];

    $sql = "SELECT * FROM `ip_list` WHERE ip_address='" . $ip_address . "' AND device_name='" . $device_name . "'";
    if ($conn->query($sql)->num_rows > 0) { 
        if (!$conn->query($sql." AND authority = 1")->num_rows > 0) {
            // header('Location: 403.php');
            echo '403';
        }else{
            $_SESSION['status'] = '202';
            echo '202';
        }
    } else {
        $sql = "INSERT INTO `ip_list` (`ip_address`, `device_name`, `date`) VALUES ('$ip_address','$device_name',CURRENT_TIMESTAMP())";
        echo $sql;
        $conn->query($sql);
        // header('Location: 403.php');
        echo '403';
    }
?>