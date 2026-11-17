<?php
session_start();
$code = $_POST['code'];
if ($code == "code-4") {
    $_SESSION['admin_login'] = 'admin';
    echo 'success';
}
