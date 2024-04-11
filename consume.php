<?php
include('db/config.php');
include('includes/header.php');

$product_id = $_GET['product_id'];
// echo $property_id;    
// $property_name = $_POST['name'];
// $description = $_POST['description'];    
// $price = $_POST['price'];
// $quantity = $_POST['quantity'];

$res = $conn->query("SELECT value FROM `setting` WHERE product_id = " . $product_id . "");
if ($res->num_rows > 0) {
    $select_option = $res->fetch_assoc()['value'];
} else {
    $select_option = 'Consume';
}

$sql1 = "SELECT name, description, image_url, price_tax_included, price_tax_excluded, quantity, category_id FROM `product` WHERE id =" . $product_id . "";
// die($sql1);
$result = $conn->query($sql1);
$product = $result->fetch_assoc();

$product_name = $product['name'];
$description = $product['description'];
$image_url = $product['image_url'];
$price_tax_included = $product['price_tax_included'];
$price_tax_excluded = $product['price_tax_excluded'];
$quantity = $product['quantity'];
$category_id = $product['category_id'];

$sql2 = "SELECT id, name, DATE_FORMAT(shopping_date,'%d-%m-%Y') AS shopping_date , DATE_FORMAT(consume_date,'%d-%m-%Y') AS consume_date, DATE_FORMAT(eaten_date,'%d-%m-%Y') AS eaten_date, DATE_FORMAT(expire_date,'%d-%m-%Y') AS expire_date, price_tax_excluded ,price_tax_included, note FROM `consume` WHERE product_id=" . $product_id . "";
// die($sql2);

$sql3 = "SELECT id, product_id, location, name, DATE_FORMAT(date,'%d-%m-%Y') AS date , price_tax_excluded ,price_tax_included, note FROM `product_price` WHERE product_id=" . $product_id . " ORDER BY DATE_FORMAT(date, '%m-%y-%d')";
// die($sql3);

$sql4 = "SELECT name AS 'location' FROM  `location`";

$sql5 = "SELECT tax_percentage FROM `category` WHERE id = " . $category_id . "";
$tax_percentage = $conn->query($sql5)->fetch_assoc()['tax_percentage'];

?>

<div class="container container-expand">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <h4>
                <?php
                echo $product['name'] . ' : ';
                if ($product['quantity'] != '') {
                ?>
                    <span style="color: #ef4141; background-color: #ffecec;">
                        <?= $product['price_tax_included'] . '</span> / <span style="color: #0c1f3e; background-color: #ebf1fa;">' . $product['price_tax_excluded'] . '</span> [' . $product['quantity'] . '] ' ?>
                    <?php }
                if ($product['price_tax_included'] != '' && $product['quantity'] == '') {
                    ?>
                        <span style="color: #ef4141; background-color: #ffecec;">
                            <?= $product['price_tax_included'] ?>
                        </span>
                        <span style="color: #0c1f3e; background-color: #ebf1fa;"> /
                            <?= $product['price_tax_excluded'] ?>
                        </span>
                    <?php } ?>
            </h4>
            <?php if ($product['description'] != '') {
                echo '<span class="font-style">[' . $product['description'] . ']</span>';
            } ?>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" id="status">
                    <h1>
                        <div class="add_radio_buttons">
                            <input type="radio" name="product_details" id="product_consume" value="Consume" checked>
                            <label for="product_consume" id="lbl_consume">Consume</label>

                            <input type="radio" name="product_details" id="product_price" value="Price" style="margin-left: 13px;">
                            <label for="product_price" id="lbl_price">Price</label>
                        </div>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="consume-panel">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-1"></th>
                                    <th scope="col">Shopping Date</th>
                                    <th scope="col">Consume Date</th>
                                    <th scope="col">Eaten Date</th>
                                    <th scope="col">Expire Date</th>
                                    <th scope="col">Note</th>
                                    <!-- <th scope="col"><span style="color: #ef4141;">Price Tax</span></th>
                        <th scope="col"><span style="color: #165fd7;">Price NoTax</span></th> -->
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
                                        <td><input type="checkbox" class="select-item-1" value="<?= $row['id'] ?>"></td>
                                        <td class="editable" id="shopping_date">
                                            <?= $row['shopping_date'] ?>
                                        </td>
                                        <td class="editable" id="consume_date" style="background-color: <?php echo $consume_date_color_code; ?>"><?= $row['consume_date'] ?>
                                        </td>
                                        <td class="editable" id="eaten_date" style="background-color: <?php echo $eaten_date_color_code; ?>"><?= $row['eaten_date'] ?></td>
                                        <td class="editable" id="expire_date" style="background-color: <?php echo $expire_date_color_code; ?>"><?= $row['expire_date'] ?></td>
                                        <td contenteditable="true" id="note" class="update_consume_note focus-select">
                                            <?= $row['note'] ?>
                                        </td>
                                    </tr>

                                <?php } ?>
                                <tr id="consume_data" style="background-color: #8eea90;">
                                    <td></td>
                                    <td><input class="input form-control" type="text" id="add_shopping_date" value="<?= date('d-m-Y') ?>"></td>
                                    <td><input class="input form-control" type="text" id="add_consume_date"></td>
                                    <td><input class="input form-control" type="text" id="add_eaten_date"></td>
                                    <td><input class="input form-control" type="text" id="add_expire_date"></td>
                                    <td><input class="input form-control" type="text" id="add_note"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" id="action-button-1" name="action-button" class="btn btn-primary font-style" style="float: right;" onclick="action_button(1)">Add</button>
                    </div>
                    <div class="price-panel" style="display: none;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-2"></th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">NoTax</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($conn->query($sql3) as $row) {
                                    // $date_color_code = ($row['date'] == '0000-00-00') ? '#ffefef' : 'initial';
                                ?>

                                    <tr id="<?= $row['id'] ?>">
                                        <td><input type="checkbox" class="select-item-2" value="<?= $row['id'] ?>"></td>
                                        <td>
                                            <select class="update_for_price" id="location">
                                                <?php
                                                foreach ($conn->query($sql4) as $row1) {
                                                ?>
                                                    <option <?php if ($row['location'] == $row1['location'])
                                                                echo 'selected'; ?> value="<?= $row1['location'] ?>"><?= $row1['location'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="editable2 update_for_price" id="date" style="background-color: <?php echo $date_color_code; ?>"><?= $row['date'] ?></td>
                                        <td contenteditable="true" id="price_tax_excluded" class="update_for_price calculate_tax_percentage focus-select input-number">
                                            <?= number_format($row['price_tax_excluded']) ?>
                                        </td>
                                        <td contenteditable="true" id="price_tax_included" class="update_for_price focus-select input-number">
                                            <?= number_format($row['price_tax_included']) ?>
                                        </td>
                                        <td contenteditable="true" id="note" class="update_for_price focus-select">
                                            <?= $row['note'] ?>
                                        </td>
                                    </tr>

                                <?php } ?>
                                <tr id="price_data" style="background-color: #ffd3d3;">
                                    <td></td>
                                    <td>
                                        <select id="add_location">
                                            <?php
                                            foreach ($conn->query($sql4) as $row) {
                                            ?>
                                                <option value="<?= $row['location'] ?>"><?= $row['location'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="input form-control" type="text" id="add_date" value="<?= date('d-m-Y') ?>"></td>
                                    <td><input class="input form-control input-number" type="text" id="add_price_tax_included" value="0"></td>
                                    <td><input class="input form-control input-number" type="text" id="add_price_tax_excluded" value="0"></td>
                                    <td><input class="input form-control" type="text" id="add_note"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" id="action-button-2" name="action-button" class="btn btn-primary font-style" style="float: right;" onclick="action_button(2)">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    let i = 1;

    // function switchOne() {
    //     $("#tab-1").css('background', '#fff');
    //     $("#tab-2").css('background', '#e3f2f3');
    //     $("#panel-1").css('display', 'block');
    //     $("#panel-2").css('display', 'none');
    //     i = 1;
    // }

    // function switchTwo() {
    //     $("#tab-1").css('background', '#e3f2f3');
    //     $("#tab-2").css('background', '#fff');
    //     $("#panel-1").css('display', 'none');
    //     $("#panel-2").css('display', 'block');
    //     i = 2;
    // }

    // https://stackoverflow.com/questions/51098954/how-to-add-date-picker-to-content-editable-td
    $(document).ready(function() {
        // $(function() {
        var tax_percentage = '<?php echo $tax_percentage ?>';
        var select_option = '<?php echo $select_option ?>';
        if (select_option == 'Consume') {
            $('.consume-panel').css('display', 'block');
            $('.price-panel').css('display', 'none');
            i = 1;
            $('input:radio[name="product_details"]').filter('[value="Consume"]').attr('checked', true);
            $('.add_radio_buttons').css('background', '#c5ffca');
            $('#lbl_consume').css('color', '#0ea336');
            $('#lbl_price').css('color', '#c3c3c3');

        } else if (select_option == 'Price') {
            $('.consume-panel').css('display', 'none');
            $('.price-panel').css('display', 'block');
            i = 2;
            $('input:radio[name="product_details"]').filter('[value="Price"]').attr('checked', true);
            $('.add_radio_buttons').css('background', '#ffefef');
            $('#lbl_price').css('color', '#c87171');
            $('#lbl_consume').css('color', '#c3c3c3');
        }

        $('input[type=radio]').on('change', function() {
            var value = $('input[name=product_details]:checked').val();
            // alert(value);

            if (value == 'Consume') {
                $('.consume-panel').css('display', 'block');
                $('.price-panel').css('display', 'none');
                $('.add_radio_buttons').css('background', '#c5ffca');
                $('#lbl_consume').css('color', '#0ea336');
                $('#lbl_price').css('color', '#c3c3c3');
                ajax('controller/update_setting.php', 'POST', {
                    'product_id': <?php echo $product_id ?>,
                    'value': value
                }).then(function(data) {
                    console.log(data);

                }).catch(function(error) {
                    console.error(error);
                });
                i = 1;
            }

            if (value == 'Price') {
                $('.consume-panel').css('display', 'none');
                $('.price-panel').css('display', 'block');
                $('.add_radio_buttons').css('background', '#ffefef');
                $('#lbl_price').css('color', '#c87171');
                $('#lbl_consume').css('color', '#c3c3c3');
                ajax('controller/update_setting.php', 'POST', {
                    'product_id': '<?php echo $product_id ?>',
                    'value': value
                }).then(function(data) {
                    console.log(data);

                }).catch(function(error) {
                    console.error(error);
                });

                i = 2;
            }


        });

        // 20230718 11:33AM KWT add tax % -->
        $(document).on('keyup', '.calculate_tax_percentage', function() {
            var row = $(this).closest('tr'); // Find the parent <tr> element
            var priceExcluded = row.find('#price_tax_excluded');
            var priceIncluded  = row.find('#price_tax_included');

            var id = row.attr('id');
            var inputText = $(this).text().trim(); // Get the input text and remove any leading/trail1ing spaces
            inputText = inputText.replace(/,/g, ''); // Remove commas from the input text

            if (inputText !== '') {
                var inputNumber = parseFloat(inputText); // Convert the input text to a number
                var cal_percentage = inputNumber * (tax_percentage / 100); // Calculate the tax
                // var result = Math.round(inputNumber + cal_percentage); // Calculate the result and round to the nearest whole number
                var result = Math.trunc(inputNumber + cal_percentage); 
                $(priceIncluded).text(result); // Display the result without decimal places

                ajax('controller/update_price.php', 'POST', {
                    'id': id,
                    'column_name': 'price_tax_included',
                    'value': result
                }).then(function(data) {
                    if (data) {
                        // console.log('updated');
                        console.log(data);
                    }
                }).catch(function(error) {
                    console.error(error);
                });
            } else {
                $(priceIncluded).text(''); // Clear the result if the input is empty
            }
        });
        // 20230718 11:33AM KWT add tax % --<

        $(document).on('focus', 'input[type=text]', function() {
            $(this).select();
        });

        $(document).on('input', '.input-number', function() {
            var value = $(this).text().replace(/,/g, ''); // Remove existing commas
            var formattedValue = parseFloat(value).toLocaleString();
            $(this).text(formattedValue);

            // Set the cursor position to the end
            var td = $(this)[0];
            var range = document.createRange();
            var sel = window.getSelection();
            range.selectNodeContents(td);
            range.collapse(false);
            sel.removeAllRanges();
            sel.addRange(range);
        });

        $(document).on('focus', '.focus-select', function() {
            var cell = this;
            var range, selection;
            if (document.body.createTextRange) {
                range = document.body.createTextRange();
                range.moveToElementText(cell);
                range.select();
            } else if (window.getSelection) {
                selection = window.getSelection();
                range = document.createRange();
                range.selectNodeContents(cell);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        });


        // $("#tabs").tabs();

        $(document).on('click', '#select-all-1', function() {
            var checked = $(this).prop('checked');
            $('.select-item-' + i).prop('checked', checked);

            var buttonLabel = (checked) ? 'Delete' : 'Add';
            $('#action-button-1').html(buttonLabel);
            if (checked) {
                $('#action-button-1').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button-1').removeClass('btn-danger').addClass('btn-primary');
            }
        });

        $(document).on('change', '.select-item-1', function() {
            var anyChecked = $('.select-item-1:checked').length > 0;
            var buttonLabel = anyChecked ? 'Delete' : 'Add';
            $('#action-button-1').html(buttonLabel);
            if (anyChecked) {
                $('#action-button-1').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button-1').removeClass('btn-danger').addClass('btn-primary');
            }
        });

        // For price -- start -->
        $(document).on('click', '#select-all-2', function() {
            var checked = $(this).prop('checked');
            $('.select-item-2').prop('checked', checked);

            var buttonLabel = (checked) ? 'Delete' : 'Add';
            $('#action-button-2').html(buttonLabel);
            if (checked) {
                $('#action-button-2').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button-2').removeClass('btn-danger').addClass('btn-primary');
            }
        });

        $(document).on('change', '.select-item-2', function() {
            var anyChecked = $('.select-item-2:checked').length > 0;
            var buttonLabel = anyChecked ? 'Delete' : 'Add';
            $('#action-button-2').html(buttonLabel);
            if (anyChecked) {
                $('#action-button-2').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#action-button-2').removeClass('btn-danger').addClass('btn-primary');
            }
        });
        // For price -- end --<

        $(document).on('click', '.editable', function() {
            // console.log($(this));
            var $td = $(this);
            handleEditableCell($td);
        });

        $(document).on('click', '.editable2', function() {
            // console.log($(this));
            var $td = $(this);
            handleEditableCell2($td);
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

        $("#add_date").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $(document).on('blur', '.update_consume_note', function() {
            var $id = $(this);
            handleEditableCellForConsumeNote($id);

        });

        $(document).on('blur', '.update_for_price', function() {
            var $id = $(this);
            handleEditableCellForPrice($id);
        });
    });

    function action_button(i) {
        // alert(i);
        var actionButton = $('#action-button-' + i).text();
        if (i == 1) { // 1 for consume

            if (actionButton == 'Add') {
                ajax('controller/add_consume.php', 'POST', {
                    'name': '<?= $product_name ?>',
                    'shopping_date': $('#add_shopping_date').val(),
                    'consume_date': $('#add_consume_date').val(),
                    'eaten_date': $('#add_eaten_date').val(),
                    'expire_date': $('#add_expire_date').val(),
                    // 'price_tax_included': $('#add_price_tax_included').val(),
                    // 'price_tax_excluded': $('#add_price_tax_excluded').val(),
                    'note': $('#add_note').val(),
                    'product_id': '<?= $product_id ?>'
                }).then(function(data) {
                    var $row = data;
                    // alert($row);

                    $('#consume_data').before($row);

                    $('#add_note').val('');

                    // $row.find('.editable').click(function() {
                    //     // console.log($(this));
                    //     // var $td = $(this);
                    //     handleEditableCell($(this));
                    // });

                    // $row.find('#price_tax_included').blur(function() {
                    //     // var $td = $(this);
                    //     handleEditableCellForPrice($(this));
                    // });

                    // $row.find('#price_tax_excluded').blur(function() {
                    //     // var $td = $(this);
                    //     handleEditableCellForPrice($(this));
                    // });
                }).catch(function(error) {
                    console.error(error);
                });
            }

            if (actionButton == 'Delete') {
                var selectedIds = $('.select-item-1:checked').map(function() {
                    return $(this).val();
                }).get();

                console.log(selectedIds);

                ajax('controller/delete_consume.php', 'POST', {
                    'ids': selectedIds
                }).then(function(data) {
                    // console.log(data);
                    if (data) {
                        $('.select-item-1:checked').closest('tr').remove();
                        $('#action-button-1').text('Add');
                        $('#action-button-1').removeClass('btn-danger').addClass('btn-primary');
                    }

                }).catch(function(error) {
                    console.error(error);
                });
            }

        } else { // 2 for price            

            if (actionButton == 'Add') {
                ajax('controller/add_price.php', 'POST', {
                    'product_id': '<?= $product_id ?>',
                    'location': $('#add_location :selected').text(),
                    'name': '<?= $product_name ?>',
                    'date': $('#add_date').val(),
                    'price_tax_included': $('#add_price_tax_included').val(),
                    'price_tax_excluded': $('#add_price_tax_excluded').val(),
                    'note': $('#add_note').val(),
                }).then(function(data) {
                    var $row = data;
                    $('#price_data').before($row);
                    // $row.find('.editable').click(function () {
                    //     handleEditableCell($(this));
                    // });

                    // $row.find('#price_tax_included').blur(function () {
                    //     handleEditableCellForPrice($(this));
                    // });

                    // $row.find('#price_tax_excluded').blur(function () {
                    //     handleEditableCellForPrice($(this));
                    // });
                }).catch(function(error) {
                    console.error(error);
                });
            }

            if (actionButton == 'Delete') {
                var selectedIds = $('.select-item-2:checked').map(function() {
                    return $(this).val();
                }).get();

                console.log(selectedIds);

                ajax('controller/delete_price.php', 'POST', {
                    'ids': selectedIds
                }).then(function(data) {
                    // console.log(data);
                    if (data) {
                        $('.select-item-2:checked').closest('tr').remove();
                        $('#action-button-2').text('Add');
                        $('#action-button-2').removeClass('btn-danger').addClass('btn-primary');
                    }

                }).catch(function(error) {
                    console.error(error);
                });
            }
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

    function handleEditableCell2($td) {
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
                    ajax('controller/update_price.php', 'POST', {
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

    function handleEditableCellForConsumeNote($td) {
        var id = $td.closest('tr').attr('id');
        var column_name = $td.attr('id');
        var value = $td.text();

        ajax('controller/update_consume.php', 'POST', {
            'id': id,
            'column_name': column_name,
            'value': value
        }).then(function(data) {
            if (data) {
                console.log(data);
            }
        }).catch(function(error) {
            console.error(error);
        });

    }

    function handleEditableCellForPrice($td) {
        var id = $td.closest('tr').attr('id');
        var column_name = $td.attr('id');
        var value = '';
        if (column_name == 'location') {
            value = $td.val();
        } else {
            value = $td.text();
        }

        // console.log(id + column_name + value);
        ajax('controller/update_price.php', 'POST', {
            'id': id,
            'column_name': column_name,
            'value': value
        }).then(function(data) {
            if (data) {
                // console.log('updated');
                console.log(data);
            }
        }).catch(function(error) {
            console.error(error);
        });
    }
</script>
<?php
include('includes/footer.php');
?>