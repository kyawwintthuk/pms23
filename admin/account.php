<?php
    session_start();
    if (!isset($_SESSION['admin_login'])) {
        header("Location: login.php");
    }
    
    $message = $_SESSION['message'] ?? '';
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['message']);
    unset($_SESSION['old']);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- CSS CDN -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" /> -->
    <!-- datetimepicker jQuery CDN -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"> -->
    </script>
    <script src="../js/jquery.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/style.css">

    <script src="https://www.google.com/jsapi"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <div class="navbar-brand">
                Account
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Authority</a>
                    </li>                  
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">User-Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="padding: 10px;">
                    
                    <?php if ($message): ?>
                        <div><?= $message ?></div>
                    <?php endif; ?>
                    <form action="./controller/signup.php" method="POST">
                        <div class="form-group mb-3 input-wrap">
                             <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="form-control" required autofocus>
                            <label id="label" for="email">Email Address</label>
                        </div>

                        <div class="form-group mb-3 input-wrap">
                            <input type="text" name="username" class="form-control" required>
                            <label id="label" for="username">Username</label>
                        </div>

                        <div class="form-group mb-3 input-wrap">
                            <input type="password" id="password" name="password" placeholder=" " class="form-control" required>
                            <label id="label" for="password">Password</label>
                        </div>

                        <div class="form-group mb-3 input-wrap">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder=" " class="form-control" required>
                            <label id="label" for="confirm_password">Confirm Password</label>
                        </div>

                        <div class="form-group mb-3 input-wrap">
                             <select name="role" required>
                                <option value="admin" <?= (isset($old['role']) && $old['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= (isset($old['role']) && $old['role'] == 'user') ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">                    
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>

</body>

</html>
<style>
    /* Toggle switch  */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }



    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }



    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    @media (min-width: 400px) {
        body {
            margin-top: 161px;
            font-size: 44px;
        }

        .navbar-brand {
            font-size: 61px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 118px;
            height: 64px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 50px;
            width: 44px;
            left: 9px;
            bottom: 6px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(60px);
            -ms-transform: translateX(60px);
            transform: translateX(60px);
        }
    }

    @media (min-width: 1000px) {
        body {
            margin-top: 87px;
            font-size: 16px;
        }

        .navbar-brand {
            font-size: 30px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
    }
</style>
<script type="text/javascript">
    
</script>