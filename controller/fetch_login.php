<?php
    session_start();
    include('../db/config.php');
    $username = $_POST['username'];
    $password = $_POST['password'];
    // $code = $_POST['code'];

    // $sql = "SELECT password FROM users WHERE role ='admin'";
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['login'] = 'success';
        echo 'success:index.php';
    }
?>