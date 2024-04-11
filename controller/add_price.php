<?php
include('../db/config.php');

function isNull($value)
{
    if (is_null($value) || empty($value)) {
        return Null;
    } else {
        return date('Y-m-d', strtotime($value));
    }
}

$output = '';

$product_id = $_POST['product_id'];
$location = $_POST['location'];
$name = $_POST['name'];
$date = isNull($_POST['date']);
$description = $_POST['description'];
$price_tax_included = str_replace(',', '', $_POST['price_tax_included']);
$price_tax_excluded = str_replace(',', '', $_POST['price_tax_excluded']);
$sys_date = date('Y-m-d');

$sql1 = "
    INSERT INTO `product_price`(`product_id`, `location`, `name`, `date`, `price_tax_included`, `price_tax_excluded`, `note`,  `sys_date`) 
    VALUES ('$product_id','$location','$name','$date','$price_tax_included', '$price_tax_excluded','$note','$sys_date');
    ";

// die($sql1);  

$result1 = $conn->query($sql1);

if ($result1 == TRUE) {
    $last_id = mysqli_insert_id($conn);
    $sql2 = "SELECT id, product_id, location, name, DATE_FORMAT(date,'%d-%m-%Y') AS date , 
        price_tax_included, price_tax_excluded, note FROM `product_price` WHERE id=" . $last_id . "";

    $sql3="SELECT name AS 'location' FROM  `location`";
    foreach ($conn->query($sql2) as $row) {
        $output .= '
            <tr id="' . $row['id'] . '">                                  
                <td><input type="checkbox" class="select-item-2" value=' . $row['id'] . '></td>
                <td>
                    <select class="update_for_price" id="location">                                                             
            ';

        foreach ($conn->query($sql3) as $row1) {
            $selected =  ($row['location'] == $row1['location']) ? 'selected' : '';
            $output .= '<option ' . $selected . ' value="' . $row1['location'] . '">' . $row1['location'] . '</option>';
        }

        $output .= '
                    </select>
                </td>
                <td class="editable2 update_for_price" id="date" style="background-color: <?php echo $date_color_code; ?>">' . $row['date'] . '</td>
                <td contenteditable="true" id="price_tax_included" class="update_for_price focus-select input-number">
                    ' .  $row['price_tax_included']   . '
                </td>
                <td contenteditable="true" id="price_tax_excluded" class="update_for_price focus-select input-number">
                ' .  $row['price_tax_excluded']   . '
                </td>
                <td contenteditable="true" id="note" class="update_for_price focus-select">
                ' .  $row['note']   . '
                </td>
            </tr>
            ';
    }
} else {
}



echo $output;
