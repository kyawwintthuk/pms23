<?php
session_start();
include('./db/config.php');
$id = 1;
// PHP program to get IP address of client
$ip_address = $_SERVER['REMOTE_ADDR'];
$device_name = $_SERVER['HTTP_USER_AGENT'];
//echo $ip_address.'/'.$device_name;
// $sql = "SELECT * FROM `ip_list` WHERE ip_address='" . $ip_address . "' AND device_name='" . $device_name . "' AND authority = 1";
// if(!$conn->query($sql)-> num_rows > 0){
//     header('Location: 403.php');
// }
$server_ip = $_SERVER['SERVER_ADDR'];
if($server_ip === '127.0.0.1' || $server_ip === '::1'){
  $title = 'localhost';
}else{
  $title = 'PMS 23';
}

$monthsColorsCode = $conn->query("SELECT option2 FROM `master` WHERE type='MonthsColors' AND option1='".date('m')."'")->fetch_assoc()['option2'];
$monthsColorsCodeCSS = "style='color: $monthsColorsCode;'";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </script>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/1200px-Flag_of_Japan.svg.png">

    <script src="https://www.google.com/jsapi"></script>

    <!-- Search box -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <title><?= $title ?></title>
</head>

<!-- <body oncontextmenu="return false" style="background-color: #f7f7f7;"> -->
<body style="background-color: #f7f7f7;">
    <section class="vh-100" style="height: 90vh!important;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h1 class="mb-5" <?= $monthsColorsCodeCSS ?>>PMS 23</h1>                                                         
                            <div class="form-group mb-3 input-wrap" style="padding-bottom:20px;">
                                <input type="text" id="username" name="username" placeholder=" " class="form-control" value="pms23" >
                                <label id="label" for="username" style="color: #877d7d;">Username</label>
                            </div>

                            <div class="form-group mb-3 input-wrap">
                                <input type="password" id="password" name="password" placeholder="●●●●●●●●●" class="form-control" autofocus>
                                <label id="label" for="password" style="color: #877d7d;">Password</label>
                            </div>
                            <br>
                         


                            <!-- Checkbox -->
                            <!-- <div class="form-check d-flex justify-content-start mb-4"> -->
                            <!-- <input class="form-check-input" type="checkbox" value="" id="form1Example3" /> -->
                            <!-- <label class="form-check-label" for="form1Example3"> Remember password </label> -->
                            <!-- </div> -->

                            <!-- <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button> -->
                            <!-- <br> -->
                            <!-- <hr class="my-4"> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<script>
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80)) {
            // alert("You can't print!");
            e.cancelBubble = true;
            e.preventDefault();

            e.stopImmediatePropagation();
        }
    });

    if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
        $.ajax({
            url: "destroy_session.php",
            method: 'GET'
        });
    }

    $('#username, #password').on('input', function() {
        $.ajax({
            url: "controller/fetch_login.php",
            method: "POST",
            data: {
                username: $('#username').val(),
                password: $(this).val()
            },
            success: function(data) {
                var response = data.trim();
                if (response.indexOf('success:') === 0) {
                    var redirectUrl = response.split(':')[1];
                    window.location.href = redirectUrl;
                }else{
                    $('.card-body').css('border-left', '10px solid #ff0000');
                }                 
            },
            error: function(xhr) {
                // If PHP redirects, this might be triggered
                // because the AJAX request is redirected
                if (xhr.status === 0 || xhr.status === 200) {
                    // The redirect might have happened
                    // No action needed - browser will follow redirect
                    return;
                }
                $('.card-body').css('border-left', '10px solid #ff0000');              
            }
        });
    });
</script>