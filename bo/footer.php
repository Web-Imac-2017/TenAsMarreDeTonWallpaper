</div>


<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body #deco").on("click", function() {
            $.ajax({
                url: "/Tenasmarredetonwallpaper/api/membre/logout",
                success: function(data) {
                    location.reload();
                }
            });
        });
    });

</script>

</body>
</html>