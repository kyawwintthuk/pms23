<?php
include('../db/config.php');

if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile')) {
    $total_img = 4;
} else {
    $total_img = 6;
}
$i = 1;
$output = '';

//   20230724 Monday KWT 15:16 PM  -- start 
$category_id = $_GET['category_id'];

if($category_id == ''){
    $sql1 = "SELECT id, name, description, tax_percentage FROM `category`";
}else{
    $sql1 = "SELECT id, name, description, tax_percentage FROM `category` WHERE id = " . $category_id . "";
}
//   20230724 Monday KWT 15:16 PM  -- end 

foreach ($conn->query($sql1) as $row) {

    $sql2 = "SELECT IFNULL(COUNT(*),0) AS total FROM `product` WHERE category_id=" . $row['id'] . " GROUP BY category_id;";

    $result = $conn->query($sql2);
    $total_item = ($result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;

    $output .= '
        <div class="card">
            <div class="card-header category" style="background-color: #f0f0f0;" data-id="' . $row['id'] . '">
                <span id="category">' . $row['name'] . ' ' . $row['tax_percentage'] . '% </span>
                <span id="item_total" style="float:right;">[ ' . $total_item . ' ] </span>
            </div>
            <div class="card-body">
                <div class="row">
    ';

    $sql3 = "SELECT id, name, description , image_url, price_tax_excluded, price_tax_included, quantity FROM `product` WHERE category_id = " . $row['id'] . "";
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

        $sql4 = "SELECT date, product_id, location, name, COALESCE(price_tax_included,0) AS 'price_tax_included', COALESCE(price_tax_excluded,0) AS 'price_tax_excluded' FROM `product_price` WHERE product_id = '" . $row['id'] . "' AND name = '" . $row['name'] . "' AND date = (SELECT MAX(date) FROM `product_price` WHERE product_id = '" . $row['id'] . "' AND name = '" . $row['name'] . "');";
        // die($sql4);
        $result = $conn->query($sql4);
        if ($result->num_rows > 0) {
            $row1 = $result->fetch_assoc();
            $location = $row1['location'];
            $price_tax_included = $row1['price_tax_included'];
            $price_tax_excluded = $row1['price_tax_excluded'];
        } else {
            $location = "...";
            $price_tax_included = 0;
            $price_tax_excluded = 0;
        }

        if ($i > $total_img) {
            $output .= '</div>
            <div class="row">';
            $i = 1;
        }

        // 20230803 Thursday -- not available add -- KWT 
        if(!empty($row['image_url'])):
            $image_url = $row['image_url'];
        else:
            $image_url = 'images/not_available.jpg ';
        endif;
        

        $output .= '            
        <div class="product col-md-' . (12 / $total_img) . '" onclick="location=\'consume.php?product_id=' . $row['id'] . '\'" data-id="' . $row['id'] . '">
                <div class="row">
                    <div class="card" style="border:none; padding: 0px;">
                        <div class="card-header" style="border:none; padding: 0px;">
                            <div class="location">
                                <span id="location_name">'. $location .'</span>
                            </div>
                            <div class="price">
                                <span id="price_tax_included_">[ ' . number_format($price_tax_included) . ' ]</span>
                                <span id="price_tax_excluded_"> ' . number_format($price_tax_excluded) . ' </span>
                            </div>                           
                            <img src="' . $image_url . '" alt="' . $row['name'] . '">
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
