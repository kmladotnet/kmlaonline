<?php
function redirWithBody($failReason){
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_POST['prev_url'])?>" id="poster">
        <?php
        foreach($_POST as $key=>$val){
            $val=htmlspecialchars($val);
            echo "<input type='hidden' name='$key' valaue='$val' />";
        }
        ?>
        <input type="hidden" name="error_occured" value="<?php echo htmlspecialchars(json_encode($failReason)) ?>" />
        <input type="submit" id="submitter" value="Click here if the page doesn't continue" />
    </form>
    <script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
    <?php
}

function validName($name){
    if(strpos($name, '(') === false) return false;
    if(strpos($name, ')') === false) return false;
    return true;
}

$name = $_POST['name_1'];
$article_var = $_POST['article_kind_1'];
echo $article_var;
$failReason = array();

if(!validName($name)) echo "<p>Your entry is wrong</p>";
$n_student = $student->getIdByStudentId(substr($name, strpos($name, '(') + 1, 6));
if($n_student === false) echo "<p> Invalid entry </p>";
else echo $n_student["n_id"];
$n_article = $article_kind->getArticleIdByExplicitName($article_var);
if($n_article === false) echo "<p> Invalid article kind </p>";
else echo "<p>" . $n_article["ak_eng"] . " " . $n_article["ak_id"] . "</p>";