<?php
session_start();
include('../db/config.php');
$id= 1;
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

    <!-- Search box -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>

<!-- <body oncontextmenu="return false" style="background-color: #f7f7f7;"> -->
<body style="background-color: #000;">
    <section class="vh-100" style="height: 90vh!important;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;" style="background-color: #ffddcf;">
                        <div class="card-body p-5 text-center" style="background-color: #a5a5a5;">

                            <h1 class="mb-5">IMS</h1>
                            <div class="panel">
                                <div class="table-bordered table-responsive text-center">

                                    <table class="table table-bordered" style="border: 1px solid #ddd !important;">
                                        <tbody>
                                            <?php
                                            for ($i = 1; $i <= 4; $i++) {
                                            ?>
                                                <tr style="background-color: #e7e4e4;">
                                                    <?php
                                                    for ($j = 1; $j <= 4; $j++) {
                                                    ?>
                                                        <td <?= 'id="code-' . $id . '"' ?> class="code" style="background-color: #fff; border: 4px solid #afacac;"><?= rand(0, 9); ?></td>
                                                    <?php
                                                        $id++;
                                                    }
                                                    ?>
                                                </tr>
                                            <?php }  ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div> 
                            <div class="panel">
                                <div class="table-bordered table-responsive text-center">

                                    <table class="table table-bordered" style="border: 1px solid #ddd !important;">
                                        <tbody>
                                            <?php
                                            for ($i = 1; $i <= 4; $i++) {
                                            ?>
                                                <tr style="background-color: #e7e4e4;">
                                                    <?php
                                                    for ($j = 1; $j <= 4; $j++) {
                                                    ?>
                                                        <td <?= 'id="code-' . $id . '"' ?> class="code" style="background-color: #fff; border: 4px solid #afacac;"><?= rand(0, 9); ?></td>
                                                    <?php
                                                        $id++;
                                                    }
                                                    ?>
                                                </tr>
                                            <?php }  ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>    
                            <div class="form-outline mb-4">
                                <input type="password" id="password" class="form-control form-control-lg" placeholder="●●●●●●●●●"/>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<script>
    $(window).on("load", function() {
        $(document).on('click', ".code", function(){            
            $.ajax({
            url: "./controller/fetch_login.php",
            method: "POST",
            data: {              
                code: $(this).prop('id')
            },
            success: function(data) {
                if (data == "success") {
                    window.location.href = 'index.php';
                }
            }
        });
        });
    });

   

    // if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
    //     $.ajax({
    //         url: "../destroy_session.php",
    //         method: 'GET'
    //     });
    // }


</script>