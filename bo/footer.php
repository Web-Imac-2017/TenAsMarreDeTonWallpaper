</div>


<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#deco").on("click", function () {
            $.ajax({
                url: "/Tenasmarredetonwallpaper/api/membre/logout",
            });
            location.reload();
            window.location.href=window.location.href;
        });
    });

</script>

</body>
</html>