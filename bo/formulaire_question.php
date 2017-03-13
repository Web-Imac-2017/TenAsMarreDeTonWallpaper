<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODELS . 'question.php';
?>

<?php
try{
    $bdd = Database::get();
    $query='SELECT * FROM Categorie ';
    $res=$bdd->prepare($query);
    $res->execute();
}catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}
$entries=$res->fetchAll(PDO::FETCH_ASSOC);
$nb=$res->rowCount();
$res->closeCursor();
?>

<?php
$page['title'] = "Question";
include('header.php');
?>

<form action="../api/question/add" method="post">
    <table>
        <tr>
            <td>Question courte <span style="color:red;">*</span>:</td>
            <td><input type="text" name="q_courte" class="form-control" /></td>
        </tr>
        <tr>
            <td>Question longue <span style="color:red;">*</span>:</td>
            <td><input type="text" name="q_longue" class="form-control" /></td>
        </tr>
        <tr>
            <?php
            foreach($entries as $entry):
            ?>
            <td>Categorie <span style="color:red;">*</span>:</td>
            <td><input type="checkbox" name="case[]"></td>
            <?php echo $entry['nom'];?>
            <?php
            endforeach;
            ?>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Ajouter" name="submit" class="btn btn-primary"/></td>
        </tr>
    </table>
</form>

<?php
if(isset($_POST['submit'])) {
    if(isset($_POST['q_longue']) && isset($_POST['q_courte']) && isset($_POST['case']) && isset($_SESSION['id'])) {
        try{
            $question=new Question();
            $question->add($_POST['qcourt'], $_POST['qlong'], 0, $_SESSION['id'], $_POST['case']);

            echo "question long :".$_POST['qlong']."</br>";
            echo "question court :".$_POST['qcourt']."</br>";
            foreach ($_POST['case'] as $nom):
            echo "cases : ".$nom."</br>";
            endforeach;

        }catch (Exception $e){
            die('Erreur : ' . $e->getMessage());
        } 
    }
}
?>

<?php
include('footer.php');
?>
