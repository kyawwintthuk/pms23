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

$name = $_POST['name'];
$shopping_date = isNull($_POST['shopping_date']);
$consume_date = isNull($_POST['consume_date']);
$eaten_date = isNull($_POST['eaten_date']);
$expire_date = isNull($_POST['expire_date']);
// $expire_date = isNull($_POST['note']);
// $price_tax_included = $_POST['price_tax_included'];
// $price_tax_excluded = $_POST['price_tax_excluded'];
$note = $_POST['note'];
$product_id = $_POST['product_id'];
$sys_date = date('Y-m-d');

$sql1 = "
    INSERT INTO `consume`(`name`, `shopping_date`, `consume_date`, `eaten_date`, `expire_date`, `note`, `product_id`, `sys_date`) 
    VALUES ('$name','$shopping_date','$consume_date','$eaten_date','$expire_date', '$note', '$product_id','$sys_date');
    ";

    // die($sql1);  

$result1 = $conn->query($sql1);

if ($result1 == TRUE) {
    $last_id = mysqli_insert_id($conn);
    $sql2 = "SELECT id, name, DATE_FORMAT(shopping_date,'%d-%m-%Y') AS shopping_date , 
        DATE_FORMAT(consume_date,'%d-%m-%Y') AS consume_date, DATE_FORMAT(eaten_date,'%d-%m-%Y') AS eaten_date, 
        DATE_FORMAT(expire_date,'%d-%m-%Y') AS expire_date, note FROM `consume` WHERE id=" . $last_id . "";              
    foreach ($conn->query($sql2) as $row) {
        $consume_date_color_code = ($row['consume_date'] == '00-00-0000') ? '#ffefef' : 'initial';
        $eaten_date_color_code = ($row['eaten_date'] == '00-00-0000') ? '#ffefef' : 'initial';
        $expire_date_color_code = ($row['expire_date'] == '00-00-0000') ? '#ffefef' : 'initial';

        $output .= '
            <tr id="' . $row['id'] . '">
                <td><input type="checkbox" class="select-item-1" value="'. $row['id'] .'"></td>  
                <td class="editable" id="shopping_date">' . $row['shopping_date'] . '</td>
                <td class="editable" id="consume_date" style="background-color: ' . $consume_date_color_code . '">' . $row['consume_date'] . '</td>
                <td class="editable" id="eaten_date" style="background-color: ' . $eaten_date_color_code . '">' . $row['eaten_date'] . '</td>
                <td class="editable" id="expire_date" style="background-color: ' . $expire_date_color_code . '">' . $row['expire_date'] . '</td>                
                <td contenteditable="true" id="note" class="update_consume_note focus-select"> '. $row['note'] .' </td>
            </tr>
            ';
    }
} else {
}



echo $output;
