<?php
include('db/config.php');
include('includes/header.php');

$product_id = $_GET['product_id'];
// echo $property_id;    
// $property_name = $_POST['name'];
// $description = $_POST['description'];    
// $price = $_POST['price'];
// $quantity = $_POST['quantity'];


$sql1 = "SELECT name, description, image_url, price_tax_included, price_tax_excluded, quantity FROM `product` WHERE id =" . $product_id . "";
// die($sql1);
$result = $conn->query($sql1);
$product = $result->fetch_assoc();

$product_name = $product['name'];
$description = $product['description'];
$image_url = $product['image_url'];
$price_tax_included = $product['price_tax_included'];
$price_tax_excluded = $product['price_tax_excluded'];
$quantity = $product['quantity'];

$sql2 = "SELECT id, name, DATE_FORMAT(shopping_date,'%d-%m-%Y') AS shopping_date , DATE_FORMAT(consume_date,'%d-%m-%Y') AS consume_date, DATE_FORMAT(eaten_date,'%d-%m-%Y') AS eaten_date, DATE_FORMAT(expire_date,'%d-%m-%Y') AS expire_date, price_tax_excluded ,price_tax_included FROM `consume` WHERE product_id=" . $product_id . "";
// die($sql2);
?>
<div class="container container-expand">
    <h4>
        <?php
        echo $product['name'] . ' : ';
        if ($product['quantity'] != '') {
        ?>
            <span style="color: #ef4141; background-color: #ffecec;"><?= $product['price_tax_included'] . '</span> / <span style="color: #0c1f3e; background-color: #ebf1fa;">'. $product['price_tax_excluded'] .'</span> [' . $product['quantity'] . '] ' ?>
        <?php }
        if ($product['price_tax_included'] != '' && $product['quantity'] == '') {
        ?>
           <span style="color: #ef4141; background-color: #ffecec;"> <?= $product['price_tax_included'] ?></span>
           <span style="color: #0c1f3e; background-color: #ebf1fa;"> / <?= $product['price_tax_excluded'] ?></span>
        <?php } ?>
    </h4>
    <?php if ($product['description'] != '') {
        echo '<span class="font-style">['.$product['description'].']</span>';
    }  ?>    
    <table class="table">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th scope="col">Shopping Date</th>
                <th scope="col">Consume Date</th>
                <th scope="col">Eaten Date</th>
                <th scope="col">Expire Date</th>
                <th scope="col"><span style="color: #ef4141;">Price Tax</span></th>
                <th scope="col"><span style="color: #165fd7;">Price NoTax</span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($conn->query($sql2) as $row) {
                $consume_date_color_code = ($row['consume_date'] == '00-00-0000') ? '#ffefef' : 'initial';
                $eaten_date_color_code = ($row['eaten_date'] == '00-00-0000') ? '#ffefef' : 'initial';
                $expire_date_color_code = ($row['expire_date'] == '00-00-0000') ? '#ffefef' : 'initial';
            ?>

                <tr id="<?= $row['id'] ?>">
                    <td><input type="checkbox" class="select-item" value="<?= $row['id'] ?>"></td>
                    <td class="editable" id="shopping_date"><?= $row['shopping_date'] ?></td>
                    <td class="editable" id="consume_date" style="background-color: <?php echo $consume_date_color_code; ?>"><?= $row['consume_date'] ?></td>
                    <td class="editable" id="eaten_date" style="background-color: <?php echo $eaten_date_color_code; ?>"><?= $row['eaten_date'] ?></td>
                    <td class="editable" id="expire_date" style="background-color: <?php echo $expire_date_color_code; ?>"><?= $row['expire_date'] ?></td>
                    <td contenteditable="true" id="price_tax_included"><?= $row['price_tax_included'] ?></td>
                    <td contenteditable="true" id="price_tax_excluded"><?= $row['price_tax_excluded'] ?></td>
                </tr>

            <?php } ?>
            <tr id="consume_data" style="background-color: #efefef;">
                <td></td>
                <td><input class="input form-control" type="text" id="add_shopping_date" value="<?= date('d-m-Y') ?>"></td>
                <td><input class="input form-control" type="text" id="add_consume_date"></td>
                <td><input class="input form-control" type="text" id="add_eaten_date"></td>
                <td><input class="input form-control" type="text" id="add_expire_date"></td>
                <td><input class="input form-control" type="text" id="add_price_tax_included"></td>
                <td><input class="input form-control" type="text" id="add_price_tax_excluded"></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" id="action-button" name="action-button" class="btn btn-primary font-style" style="float: right;" onclick="action_button()">Add</button>
</div>
<script type="text/javascript">
    // https://stackoverflow.com/questions/51098954/how-to-add-date-picker-to-content-editable-td
    $(document).ready(function() {

        $('#select-all').click(function() {
            var checked = $(this).prop('checked');
            $('.select-item').prop('checked', checked);

            var buttonLabel = (checked) ? 'Delete' : 'Add';
            $('#action-button').html(buttonLabel);
            if (checked) {
                $('#action-button').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button').removeClass('btn-danger').addClass('btn-primary');
            }
        });

        $('.select-item').change(function() {
            var anyChecked = $('.select-item:checked').length > 0;
            var buttonLabel = anyChecked ? 'Delete' : 'Add';
            $('#action-button').html(buttonLabel);
            if (anyChecked) {
                $('#action-button').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button').removeClass('btn-danger').addClass('btn-primary');
            }
        });


        $(".editable").click(function() {
            // console.log($(this));
            var $td = $(this);
            handleEditableCell($td);
            // var $td = $(this);
            // var ini_value = $(this).text();
            // var text = $(this).html();
            // var column_name = $td.attr('id');
            // // console.log(column_name);
            // var $input = $('<input class="input form-control" type="text" value="' + text + '"/>');
            // $td.html('').append($input);

            // $input.datepicker({
            //     dateFormat: 'dd-mm-yy',
            //     onClose: function(dateText, inst) {
            //         $td.html(dateText.split('-').join('-'));
            //         $td.attr('disabled', false);
            //         var id = $td.closest('tr').attr('id');
            //         var value = $td.text();

            //         if (ini_value != value) {
            //             $.ajax({
            //                 url: 'controller/update_consume.php',
            //                 method: 'POST',
            //                 data: {
            //                     'id': id,
            //                     'column_name': column_name,
            //                     'value': value
            //                 },
            //                 success: function(data) {
            //                     if (data) {
            //                         console.log('updated');
            //                     }
            //                 }
            //             })
            //         }

            //         // console.log(id + '/' + column_name + '/' + value);
            //     }
            // }).datepicker('show');
        });

        $('#price_tax_included').blur(function() {
            var $id = $(this);
            handleEditableCellForPrice($id);
            // var id = $(this).closest('tr').attr('id');
            // var column_name = $(this).attr('id');
            // var value = $(this).text();

            // console.log(id + column_name + value);

            // $.ajax({
            //     url: 'controller/update_consume.php',
            //     method: 'POST',
            //     data: {
            //         'id': id,
            //         'column_name': column_name,
            //         'value': value
            //     },
            //     success: function(data) {
            //         if (data) {
            //             console.log('updated');
            //         }
            //     }
            // })

        });

        $('#price_tax_excluded').blur(function() {
            var $id = $(this);
            handleEditableCellForPrice($id);
        });

        $("#add_shopping_date").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $("#add_consume_date").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $("#add_eaten_date").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $("#add_expire_date").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        // $(".editable").blur(function() {

        //     var id = $(this).closest("tr").attr('id');
        //     var value = $(this).text();

        //     console.log(id + '-' + '-' + value);
        // });

    });

    function action_button() {
        var actionButton = $('#action-button').text();
        if (actionButton == 'Add') {
            ajax('controller/add_consume.php', 'POST', {
                'name': '<?= $product_name ?>',
                'shopping_date': $('#add_shopping_date').val(),
                'consume_date': $('#add_consume_date').val(),
                'eaten_date': $('#add_eaten_date').val(),
                'expire_date': $('#add_expire_date').val(),
                'price_tax_included': $('#add_price_tax_included').val(),
                'price_tax_excluded': $('#add_price_tax_excluded').val(),
                'product_id': '<?= $product_id ?>'
            }).then(function(data) {
                var $row = $(data);

                $('#consume_data').before($row);

                $row.find('.editable').click(function() {
                    // console.log($(this));
                    // var $td = $(this);
                    handleEditableCell($(this));
                });

                $row.find('#price_tax_included').blur(function() {
                    // var $td = $(this);
                    handleEditableCellForPrice($(this));
                });

                $row.find('#price_tax_excluded').blur(function() {
                    // var $td = $(this);
                    handleEditableCellForPrice($(this));
                });
            }).catch(function(error) {
                console.error(error);
            });
        }

        if (actionButton == 'Delete') {
            var selectedIds = $('.select-item:checked').map(function() {
                return $(this).val();
            }).get();

            console.log(selectedIds);

            ajax('controller/delete_consume.php', 'POST', {
                'ids': selectedIds
            }).then(function(data) {
                // console.log(data);
                if (data) {
                    $('.select-item:checked').closest('tr').remove();
                    $('#action-button').text('Add');
                    $('#action-button').removeClass('btn-danger').addClass('btn-primary');
                }

            }).catch(function(error) {
                console.error(error);
            });
        }        
    }

    function handleEditableCell($td) {
        var ini_value = $td.text();
        var text = $td.html();
        var column_name = $td.attr('id');
        var $input = $('<input class="input form-control" type="text" value="' + text + '"/>');
        $td.html('').append($input);

        $input.datepicker({
            dateFormat: 'dd-mm-yy',
            onClose: function(dateText, inst) {
                $td.html(dateText.split('-').join('-'));
                $td.attr('disabled', false);
                var id = $td.closest('tr').attr('id');
                var value = $td.text();

                if (ini_value != value) {
                    // console.log(id + '/' + value + '/' + column_name);
                    // $.ajax({
                    //     url: 'controller/update_consume.php',
                    //     method: 'POST',
                    //     data: {
                    //         'id': id,
                    //         'column_name': column_name,
                    //         'value': value
                    //     },
                    //     success: function(data) {
                    //         if (data) {
                    //             console.log('updated');
                    //         }
                    //     }
                    // });
                    ajax('controller/update_consume.php', 'POST', {
                        'id': id,
                        'column_name': column_name,
                        'value': value
                    }).then(function(data) {
                        if (data) {
                            $td.css('background-color', 'initial');
                            console.log('updated');
                        }
                    }).catch(function(error) {
                        console.error(error);
                    });
                }
            }
        }).datepicker('show');
    }

    function handleEditableCellForPrice($td) {
        var id = $td.closest('tr').attr('id');
        var column_name = $td.attr('id');
        var value = $td.text();

        // console.log(id + column_name + value);

        // $.ajax({
        //     url: 'controller/update_consume.php',
        //     method: 'POST',
        //     data: {
        //         'id': id,
        //         'column_name': column_name,
        //         'value': value
        //     },
        //     success: function(data) {
        //         if (data) {
        //             console.log('updated');
        //         }
        //     }
        // })

        ajax('controller/update_consume.php', 'POST', {
            'id': id,
            'column_name': column_name,
            'value': value
        }).then(function(data) {
            if (data) {
                console.log('updated');
            }
        }).catch(function(error) {
            console.error(error);
        });
    }
</script>
<?php
include('includes/footer.php');
?>