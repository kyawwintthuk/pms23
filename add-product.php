<?php

include('db/config.php');
include('includes/header.php');
session_start();

$isEdit = false;

if (isset($_GET['id'])) {
    $isEdit = true;
    $value = $_GET['id'];
    $id_array = explode('.', $value);
    $id = $id_array[0];
    $status = $id_array[1];
    // echo $id;

    if ($status == 1) {
        $sql1 = "SELECT * FROM `product` WHERE id=" . $id;
        // echo $sql1;
        $result = $conn->query($sql1);
        $product = $result->fetch_assoc();
    } else if ($status == 2) {
        $sql2 = "SELECT * FROM `category` WHERE id=" . $id;
        // echo $sql2;
        $result2 = $conn->query($sql2);
        $category = $result2->fetch_assoc();
    }    
}

?>

<div class="container container-expand">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form action="controller/add_product.php" method="POST">
                    <div class="card-header" id="status" <?= (isset($_SESSION['color'])) ? 'style="background-color: ' . $_SESSION['color'] . '"' : '' ?>>
                        <h1>
                            <div class="add_radio_buttons">
                                <input type="radio" name="operation" id="operaton_product" value="Product" <?= ($isEdit) ? ($status == 2) ? 'disabled unchecked' : 'checked' : 'checked' ?>>
                                <label for="operaton_product" <?= ($isEdit && $status == 2) ? 'style="color: #bcbcbc;"' : '' ?>>Product</label>

                                <input type="radio" name="operation" id="operation_category" value="Category" <?= ($isEdit) ? ($status == 1) ? 'disabled unchecked' : 'checked' : '' ?>>
                                <label for="operation_category" <?= ($isEdit && $status == 1) ? 'style="color: #bcbcbc;"' : '' ?>>Category</label>

                                <input type="radio" name="operation" id="operation_location" value="Location" <?= ($isEdit) ? ($status == 1 || $status == 2) ? 'disabled unchecked' : 'checked' : '' ?>>
                                <label for="operation_location" <?= ($isEdit && ($status == 1 || $status == 2)) ? 'style="color: #bcbcbc;"' : '' ?>>Location</label>
                            </div>
                        </h1>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<span id='message'>" . $_SESSION['message'] . "</span>";
                            unset($_SESSION['message']);
                        }

                        if (isset($_SESSION['color'])) {
                            unset($_SESSION['color']);
                        }
                        ?>
                    </div>
                    <div class="card-body">
                        <div id="image-container" style="width: 189px;" class="for-product-block">
                            <img src="images/default_image.png">
                        </div>
                        <div id="data_item">
                            <div class="form-group mb-3 input-div for-product-block">
                                <select name="category" id="category" style="float: left;">
                                    <?php
                                    foreach ($conn->query("SELECT * FROM `category`") as $row) { ?>
                                        <option value="<?= $row['id'] ?>" <?= ($isEdit) ? ($row['id'] == $product['category_id']) ? 'selected' : '' : ''  ?>><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <br>
                            <div class="form-group mb-3 input-wrap input-div for-product-block">
                                <input type="text" id="image_url" name="image_url" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['image_url'] . '"' : '' ?> autofocus>
                                <label id="label" for="image_url">Image URL</label>
                            </div>
                            <input type="hidden" id="id" name="id" <?php echo ($isEdit) ? 'value="' . $id . '"' : '' ?>>
                            <div class="form-group mb-3 input-wrap input-div ">
                                <input type="text" id="name" name="name" placeholder=" " class="input form-control" <?= ($isEdit) ? ($status == 1) ?  'value="' . $product['name'] . '"' : 'value="' . $category['name'] . '"' : '' ?>>
                                <label id="label" for="name">Name</label>
                            </div>
                            <div class="form-group mb-3 input-wrap input-div">
                                <input type="text" id="description" name="description" placeholder=" " class="input form-control" <?= ($isEdit) ? ($status == 1) ? 'value="' . $product['description'] . '"' : 'value="' . $category['description'] . '"' : '' ?>>
                                <label id="label" for="description">Description</label>
                            </div>                         
                            <div class="form-group mb-3 input-wrap input-div for-product-block">
                                <input type="text" id="quantity" name="quantity" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['quantity'] . '"' : '' ?>>
                                <label id="label" for="quantity">Quantity</label>
                            </div>
                            <div class="form-group mb-3 input-wrap input-div for-product-block">
                                <input type="text" id="date" name="date" placeholder=" " class="input form-control" value="<?= date('d-m-Y') ?>" <?= ($isEdit && $status == 1) ? 'value="' . $product['date'] . '"' : '' ?>>
                                <label id="label" for="date">Date</label>
                            </div>                            
                            <div class="form-group mb-3 input-wrap input-div for-category-block">
                                <input type="text" id="tax_percentage" name="tax_percentage" placeholder=" " class="input form-control" <?= ($isEdit) ? ($status == 2) ? 'value="' . $category['tax_percentage'] . '"' : '' : 'value="8"' ?>>
                                <label id="label" for="tax_percentage">Tax %</label>
                            </div>
                            <?php
                            if ($isEdit) {
                                $result = $conn->query("SELECT location FROM `product_location` WHERE product_id = " . $product['id'] . "");
                                if ($result && $result->num_rows > 0) {
                                    // $location = $result->fetch_assoc()['location'];                                
                                    // foreach ($arr as $loc) {
                                    $sql = "SELECT id, DATE_FORMAT(MAX(date), '%Y-%m-%d') AS date, date AS date1, location,  price_tax_included, price_tax_excluded  FROM `product_price` WHERE product_id =  " . $product['id'] . " AND name = '" . $product['name'] . "' GROUP BY location ORDER BY date DESC;";
                                    // echo $sql;
                                    foreach ($conn->query($sql) as $row) {
                            ?>
                                        <div class="form-group mb-3 input-wrap input-div for-product-block" data-id="<?= $row['id'] ?>" data-product-id="<?= $product['id'] ?>" data-location="<?= $row['location'] ?>" data-date="<?= $row['date1'] ?>">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td colspan="2"><?= $row['location'] . ' [' . $row['date'] . ']' ?></td>
                                                </tr>
                                                <tr data-id="<?= $row['id'] ?>">
                                                    <td contenteditable="true" style="background-color: #ebf1fa; width: 100px; overflow: hidden; white-space: nowrap;" class="forPrice" contenteditable="true" id="price_tax_excluded"><?= $row['price_tax_excluded'] ?></td>
                                                    <td contenteditable="true" style="background-color: #fff9d6; width: 100px; overflow: hidden; white-space: nowrap;" class="forPrice" contenteditable="true" id="price_tax_included"><?= $row['price_tax_included'] ?></td>                                                    
                                                </tr>
                                            </table>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    // $location = null;
                                    ?>
                                    <div class="form-group mb-3 input-wrap input-div for-product-block">
                                        <a href="product-location.php">Go to product location!</a>
                                    </div>

                            <?php
                                }
                            }
                            ?>


                            <!-- <td>
                                <input type="hidden" name="location" value="<?= $loc ?>">
                                <input type="text" id="price_tax_included" name="price_tax_included" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['price_tax_included'] . '"' : '' ?> style="background-color: #fff9d6;">
                                <label id="label" for="price_tax_included" style="color: #b50e0e;">[ <?= $loc ?> ] tax</label>
                            </td>
                            <td>
                                <input type="text" id="price_tax_excluded" name="price_tax_excluded" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['price_tax_excluded'] . '"' : '' ?> style="background-color: #ebf1fa;">
                                <label id="label" for="price_tax_excluded" style="color: #0c1f3e;">[ <?= $loc ?> ] no tax</label>
                            </td> -->

                            <!-- <div class="form-group mb-3 input-wrap input-div for-radio-btn">
                                <input type="text" id="price_tax_included" name="price_tax_included" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['price_tax_included'] . '"' : '' ?> style="background-color: #fff9d6;">
                                <label id="label" for="price_tax_included" style="color: #b50e0e;">Price (Tax Included)</label>
                            </div>
                            <div class="form-group mb-3 input-wrap input-div for-radio-btn">
                                <input type="text" id="price_tax_excluded" name="price_tax_excluded" placeholder=" " class="input form-control" <?= ($isEdit && $status == 1) ? 'value="' . $product['price_tax_excluded'] . '"' : '' ?> style="background-color: #ebf1fa;">
                                <label id="label" for="price_tax_excluded" style="color: #0c1f3e;">Price (Tax Excluded)</label>
                            </div> -->



                            <?php
                            if ($isEdit) {
                            ?>
                                <button type="submit" name="edit" class="btn btn-primary font-style" style="float: right;">Edit</button>
                                <button type="submit" name="delete" class="btn btn-danger font-style" onclick="return confirm('Are you sure?')" style="float: right;">Delete</button>
                            <?php
                            } else {
                            ?>
                                <button type="submit" name="add" class="btn btn-primary font-style" style="float: right;">Add</button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // var radio_button = "<?php echo isset($_SESSION['radio_button']) ? $_SESSION['radio_button'] : ''; ?>";

        var _top = $(window).scrollTop();

        $(window).scroll(function() {
            var _cur_top = $(window).scrollTop();
            if (_top < _cur_top) { // scroll = down
                $(".button-corner").slideUp(100);
            } else {
                $(".button-corner").slideDown(100);
            }
        }).scroll();


        radioButton($('input[name=operation]:checked').val());

        var imagePath = $('#image_url').val();
        var img = $('<img>');
        img.attr('src', imagePath);
        img.on('error', function() {
            $(this).attr('src', 'images/default_image.png');
        });
        $('#image-container').empty().append(img);


        $('#date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('input[type=radio]').on('change', function() {
            radioButton($('input[name=operation]:checked').val());
            // var value = $('input[name=operation]:checked').val();
            // if (value == 'Property') {
            //     $('.for-radio-btn').css('display', 'block');
            // }

            // if (value == 'Category') {
            //     $('.for-radio-btn').css('display', 'none');
            // }
        });

        // var status = "<?php echo isset($_SESSION['status']) ? $_SESSION['status'] : ''; ?>";
        // var message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";

        // if (status == 'Success') {
        //     $("#status").css('background-color', '#ccffd1');
        //     // unset($_SESSION['status']);
        // } else if (status == 'Fail') {
        //     $("#status").css('background-color', '#f98686');
        //     $("#message").html(message);
        //     // unset($_SESSION['status']);
        // }

        setTimeout(function() {
            $('#status').css('background-color', 'initial');
            $('#message').css('color', '#fa2525');
        }, 3000);

        $('#image_url').on('input', function() {
            var imagePath = $(this).val();
            var img = $('<img>');
            img.attr('src', imagePath);
            img.on('error', function() {
                $(this).attr('src', 'images/default_image.png');
            });
            $('#image-container').empty().append(img);
        });

        // $('#price_tax_included').focusout(function() {
        //     alert('focusout');
        // });
        var pre_value = '';
        $('.forPrice').focus(function() {
            pre_value = $(this).text();
            // console.log(pre_value);
        });

        $('.forPrice').focusout(function() {
            var td = $(this);
            var text = td.html();
            var id = td.closest('tr').data('id');
            var product_id = td.closest('div').data('product-id');
            var location = td.closest('div').data('location');
            var date = td.closest('div').data('date');
            var column_name = td.attr('id');
            var value = td.text();
            // alert(id+'/ '+product_id+'/ '+location+'/ '+date);
            // console.log(column_name+'/ '+value);
            // console.log(pre_value+'/ '+value);
            if (pre_value != value) {
                ajax('controller/update_price.php', 'POST', {
                    'id': id,
                    'product_id': product_id,
                    'location': location,
                    'date': date,
                    'column_name': column_name,
                    'value': value,
                }).then(function(data) {
                    if (data) {
                        console.log(data);
                    }
                }).catch(function(error) {
                    console.error(error);
                });
            }

        });
    });

    function radioButton(value) {
        // var value = $('input[name=operation]:checked').val();
        if (value == 'Product') {
            $('.for-product-block').css('display', 'block');
            $('.for-category-block').css('display', 'none');
        }

        if (value == 'Category') {
            $('.for-product-block').css('display', 'none');
            $('.for-category-block').css('display', 'block');
        }

        if (value == 'Location') {
            $('.for-product-block').css('display', 'none');
            $('.for-category-block').css('display', 'none');
        }
    }
</script>

<?php

include('includes/footer.php');

?>