<?php
include('db/config.php');
include('includes/header.php');

$sql = "SELECT p.id, p.name as product_name, cg.name as category_name, p.image_url, c.shopping_date, c.consume_date, c.eaten_date, c.expire_date
        FROM `product` p, `consume` c, `category` cg 
        WHERE p.id = c.product_id
        AND   p.category_id = cg.id
        AND '2023-03-17' > c.expire_date 
        AND c.expire_date > 0 
        AND c.eaten_date = 0;";
?>
<div class="container container-expand">
    <?php
    $output = '';
    $category_name = ''; 
    $i = 0;    
    foreach ($conn->query($sql) as $row) {
        // $start_date = new DateTime($row['expire_date']);
        // $result = $conn->query("SELECT CURRENT_TIMESTAMP() AS now")->fetch_assoc();   
        // $diff_date = $start_date->diff(new DateTime($result['now']));
        // if($diff_date->y != '' && $diff_date->y !=0){
        //     $expire_date = $diff_date->y. 'days ';
        // }

        if($category_name != $row['category_name']){  
            $output.= ($output != '') ? '</div></div><br>' : '';
            $output.= '
            <div class="card">
                <div class="card-header">                           
                    <span>'.$row['category_name'].'</span>
                </div> 
                <div class="card-body">                          
            ';
        }

        $output .= '                    
                <div class="row"> 
                    <table class="expiry">
                        <tr onclick="location=\'consume.php?product_id='.$row['id'].'\'">
                            <td id="imgCol"><img src="' . $row['image_url'] . '">          </td>
                            <td>
                                <span>' . $row['product_name'] . '</span> <br>
                                <span> Expire Date : '. $row['expire_date'] .'</span> <br>                                
                            </td>
                        </tr>
                    </table>                  
                </div>';               
                
        $category_name = $row['category_name'];
    }


    echo $output;
    ?>
</div>

<?php
include('includes/footer.php');
?>