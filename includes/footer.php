<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<div class="button-corner">

    <a href="add-product.php" class="button">+</button>

</div>

</body>

</html>
<script>
    // 20231012 Thursday 1711PM KWT slide up and down when scroll to button
    var lastScrollTop = 0;

    $(window).scroll(function () {
        var currentScroll = $(this).scrollTop();
        if (currentScroll > lastScrollTop) { // scroll down
            // 上にスライドしながら非表示にする
            $(".button-corner").slideUp(100);
        } 
        else  {
            // 下にスライドしながら表示する
            $(".button-corner").slideDown(100);
        }
        lastScrollTop = currentScroll;
    });
</script>
