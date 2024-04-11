<?php
include('../db/config.php');

if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile')) {
    $total_img = 4;
} else {
    $total_img = 6;
}
$i = 1;
$output = '';

$sql1 = "SELECT id, name, description FROM `category`";

foreach ($conn->query($sql1) as $row) {

    $sql2 = "SELECT IFNULL(COUNT(*),0) AS total FROM `property` WHERE category_id=".$row['id']." GROUP BY category_id;";

    $result = $conn->query($sql2);
    $total_item = ($result->num_rows > 0)? $result->fetch_assoc()['total'] : 0;
     
    $output .= '
        <div class="card">
            <div class="card-header category" style="background-color: #f0f0f0;" data-id="'.$row['id'].'">
                <span id="category">' . $row['name'] . '</span>
                <span id="item_total" style="float:right;">[ '.$total_item.' ] </span>
            </div>
            <div class="card-body">
                <div class="row">
    ';

    $sql3 = "SELECT id, name, description , image_url, price_tax_excluded, price_tax_included, quantity FROM `property` WHERE category_id = " . $row['id'] . "";
    // echo $sql2;
    // $output .= '
    //     <div class="card">
    //         <div class="card-header">
    //             <span id="category">食料</span>
    //         </div>
    //         <div class="card-body">
    //             <div class="row">
    // ';
    
    foreach ($conn->query($sql3) as $row) {        
        if ($i > $total_img) {
            $output .= '</div>
            <div class="row">';
            $i = 1;
        }

        $output .= '            
        <div class="property col-md-' . (12 / $total_img) . '" onclick="location=\'consume.php?property_id='.$row['id'].'\'" data-id="'.$row['id'].'">
                <div class="row">
                    <div class="card" style="border:none; padding: 0px;">
                        <div class="card-header" style="border:none; padding: 0px;">
                            <div class="price">
                                <span id="price_tax_included_">['. $row['price_tax_included'] .'] </span>
                                <span id="price_tax_excluded_">'. $row['price_tax_excluded'] .'</span>
                            </div>                           
                            <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">
                        </div>
                        <div class="card-body" style="padding: 10px 0px;">
                            <span> ' . substr($row['name'], 0, 30) . '...' . '</span>
                        </div>
                    </div>
                </div>
            </div>
        ';

        $i++;
    }
    $i = 1;

    $output .= '
                </div>
            </div>
        </div>
        <br>
    ';
}





echo $output;
