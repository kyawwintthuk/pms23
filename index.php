<?php
// if(!isset($_SESSION['login'])){
//     header("Location: login.php");
// }

include('db/config.php');
include('includes/header.php');
// echo $_SERVER['HTTP_USER_AGENT'];

?>
<!-- <div class="container" style="margin-top: 153px;"> -->
<div class="container container-expand">

    <div style="background-color: #e9fdff; margin-bottom: 24px;">
    <!-- 20230724 Monday KWT 15:16 PM start -->
        <input type="radio" name="category" id="all" style="margin: 0px;" value=""  checked="true">
        <label for="all" style="padding-right: 7px;" class="font-style">All</label>
        <?php
        $sql = "SELECT * FROM `category`";
        foreach ($conn->query($sql) as $row) {
        ?>
            <input type="radio" name="category" id="<?= $row['name'] ?>"  value="<?= $row['id'] ?>" style="margin: 0px;">
            <label for="<?= $row['name'] ?>" style="padding-right: 7px;" class="font-style"><?= $row['name'] ?></label>
        <?php } ?>
    </div>
     <!-- 20230724 Monday KWT 15:16 PM end -->

    <div id="product_details">
        <!-- <div> -->
        <div style="text-align:center;">
            <img id="loading_spinner" src="images/loading-spinner.gif">
            <div class="my_update_panel"></div>
        </div>
    </div>

    <!-- <div class="card">
        <div class="card-header">
            <span id="category">食料</span>
        </div> -->
    <!-- <div class="card-body"> -->
    <!-- <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono52604948-220928-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono01775077-140616-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono01775052-140616-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="//jp.images-monotaro.com/Monotaro3/pi/full/mono19596298-151028-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
            </div> -->

    <!-- <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono52604948-220928-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono01775077-140616-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://jp.images-monotaro.com/Monotaro3/pi/middle/mono01775052-140616-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <img src="//jp.images-monotaro.com/Monotaro3/pi/full/mono19596298-151028-02.jpg" alt="Test">
                        </div>
                        <div class="card-body">
                            <span>華やぎのお米・ひとめぼれ 米袋</span>
                        </div>
                    </div>
                </div>
            </div> -->
    <!-- </div> -->
    <!-- </div> -->
</div>

<script type="text/javascript">
    // window.addEventListener("orientationchange", function() {
    //     // alert("the orientation of the device is now " + screen.orientation.angle);
    // });

    $(document).ready(function() {

        fetch_product();

        $('input[type=radio]').on('change', function() {            
            fetch_product();
        });

        function handleImageLoadError(){
            var defaultImageUrl = 'images/loading-spinner.gif ';
            $(this).attr('src', defaultImageUrl);
        }

        function fetch_product() {

            var selected_value = $('input[type=radio]:checked').val();                     

            $.ajax({
                url: 'controller/fetch_product.php',
                method: 'GET',
                data: {
                    category_id : selected_value
                },
                async: true, // avoid blocking the page rendering
                success: function(data) {
                    $("#product_details").html(data);

                    $("#product_details img").each(function(){
                        $(this).on('error', handleImageLoadError);
                    });

                    var timer;
                    // 2023-02-20 16:23 Monday
                    $('#product_details').on('mousedown touchstart', '.product', function() {
                        var id = $(this).data('id');
                        timer = setTimeout(function() {
                            // alert(id);
                            var url = "add-product.php?id=" + id + ".1"; //1 : product
                            window.location.href = url;
                        }, 1 * 1000);
                    }).on("mouseup mouseleave touchend touchmove", function() {
                        clearTimeout(timer);
                    });

                    $('#product_details').on('mousedown touchstart', '.category', function() {
                        var id = $(this).data('id');
                        timer = setTimeout(function() {
                            var url = "add-product.php?id=" + id + ".2"; // 2 : category
                            window.location.href = url;
                        }, 1 * 1000);
                    }).on("mouseup mouseleave touchend touchmove", function() {
                        clearTimeout(timer);
                    });
                }
            })
        }

        // setInterval(function() {
        //     fetch_product();
        // }, 1000); //it will refresh your data every 1 sec
    });
</script>
<?php
include('includes/footer.php');
?>