<?php
    session_start();
    include('../../db/config.php');

    $email            = trim($_POST['email'] ?? '');
    $username            = trim($_POST['username'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role             = trim($_POST['role'] ?? 'user');

    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (strlen($password) < 3) {
        $errors[] = "Password must be at least 3 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if ($errors) {
        $_SESSION['message'] = "<p style='color:red;'>" . implode('<br>', $errors) . "</p>";
        // Save form input to session so user data is not lost
        $_SESSION['old'] = [
            'email' => $email,
            'role' => $role
        ];
        header("Location: ../account.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, username, password, role, date) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmt) {
        $_SESSION['message'] = "<p style='color:red;'>Database error: " . $conn->error . "</p>";
        header("Location: ../account.php");
        exit;
    }

    $stmt->bind_param('ssss', $email, $username , $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<p style='color:green;'>Account created successfully!</p>";
        unset($_SESSION['old']); // clear old data on success
    } else {
        if ($conn->errno === 1062) {
            $_SESSION['message'] = "<p style='color:red;'>Email already registered.</p>";
        } else {
            $_SESSION['message'] = "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
        $_SESSION['old'] = [
            'email' => $email,
            'role' => $role
        ];
    }

    $stmt->close();
    $conn->close();

    header("Location: ../account.php");
    exit;

?>