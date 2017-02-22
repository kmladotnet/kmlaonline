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

$failReason = array();

if(!validName($name)) echo "<p>Your entry is wrong</p>";
$n_student = $student->getIdByStudentId(substr($name, strpos($name, '(') + 1, 6));
if($n_student === false){
    echo "<p> Invalid entry </p>";
    $failReason["name_1"] = "존재하지 않는 학생입니다.";
}
else echo $n_student["n_id"];
$n_article = $article_kind->getArticleIdByExplicitName($article_var);
if($n_article === false){
    echo "<p> Invalid article kind </p>";
    $failReason["article_kind_1"] = "존재하지 않는 항목입니다.";
}
else echo "<p>" . $n_article["ak_eng"] . " " . $n_article["ak_id"] . "</p>";
if(count($failReason) > 0){
    if(isAjax()){
        //ajaxDie($failReason);
    }else{
        //redirectWith("redirectWithBody", $failReason);
    }
} else{
    if(false !== $mid = $article->addArticle(intval($n_student["n_id"]), intval($n_article["ak_id"]), $_POST["accuse_date"], $_POST["accuser"])){
            if(isAjax()){
                ajaxOK(array(), "/user/success");
                //echo "<p> NOT FAIL?!! </p>";
            }else {
                redirectTo("/user/success");
                //echo "<p> NOT FAIL?!! </p>";
            }
    }else{
        if(isAjax()){
            //ajaxDie(array(), "알 수 없는 오류가 발생하였습니다.");
        }else{
            $failReason['__other']="알 수 없는 오류가 발생하였습니다.";
            //redirectWith("redirWithBody",$failReason);
            echo "<p> WHY FAIL???!!!! </p>";
        }
    }
}