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
    if($student->getIdByStudentId(substr($name, strpos($name, '(') + 1, 6)) === false) return false;
 }

 $failReason = array();
 if(!validName($_POST['name_1'])) echo "<p>Your entry is wrong</p>";