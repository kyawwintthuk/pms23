<?php
    $servername = "sql108.epizy.com";
    $username = "epiz_33536210";
    $password = "wGR8ACZJcL";
    $dbname = "epiz_33536210_pms";

    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, "utf8");
    if($conn->connect_error){
        die("Connection Failed".$conn->connect_error);
    }

?>