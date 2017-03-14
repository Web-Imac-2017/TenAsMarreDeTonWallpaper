<?php
$page['title'] = "Profil";
include('header.php');
?>
<span id="userId" style="display:none"><?php echo $_SESSION['user']['id'] ?></span>
<div class="row">
    <h2 class="sub-header"></h2>
    <div class="col-md-4">
        <form id="change">
            <div class="form-group">
                <label>Votre pseudo : </label>
                <input type="text" class="form-control pseudo" value='' />
            </div>
            <div class="form-group">
                <label>Votre adresse mail : </label>
                <input type="mail" class="form-control mail" value='' />
            </div>
            <div class="form-group">
                <label>Ancien mot de passe : </label>
                <input type="password" class="form-control mdp1" value='' />
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe : </label>
                <input type="password" class="form-control mdp2" value='' />
            </div>
            <input type="submit" value="Modifier" name="submit" class="btn btn-info" />
        </form>
    </div>
    <div class="col-md-4" id="nb_questions">
        <div class="circle"></div><br />
        <span>questions ajoutées</span>
    </div>
    <div class="col-md-4" id="nb_wallpapers">
        <div class="circle"></div><br />
        <span>wallpapers ajoutés</span>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var url = "/TenAsMarreDeTonWallpaper/api/membre/get/" + $("#userId").text();
        reload();

        function reload() {
            $.ajax({
                url: url,
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    var chaine = "";
                    var res = JSON.parse(data);
                    $(".sub-header").html("Bonjour " + res.data[0].pseudo);
                    $("#change .pseudo").attr('value', res.data[0].pseudo);
                    $("#change .mail").attr('value', res.data[0].mail);
                    $("#nb_questions .circle").html(res.data[0].nb_questions_ajoutees);
                    $("#nb_wallpapers .circle").html(res.data[0].nb_wallpapers_ajoutes);
                }
            });
        };
    });

</script>

<?php
include('footer.php');
?>
