<!DOCTYPE html>
<html>
<head>
    <title>New Article Result</title>
</head>
<body>
    <h1>New Point Entry Result</h1>
    <?php
        $grade = $_POST['grade'];
        $name = $_POST['name'];
        $accuse_date = $_POST['accuse_date'];
        $accuser = $_POST['accuser'];
        $article = $_POST['article'];

        if(!$grade | !$name | !$accuse_date | !$accuser | !$article){
            echo "You have not entered all the required details.<br />"
                ."Please go back and try again.";
            exit;
        }

        if (!get_magic_quotes_gpc()){
            $grade = addslashes($grade);
            $name = addslashes($name);
            $accuse_date = addslashes($accuse_date);
            $accuser = addslashes($accuser);
            $article = addslashes($article);
        }

        include("lib.php");

        if (mysqli_connect_errno()) {
            echo "Error: Could not connect to database. Please try again later.";
            exit;
        }

        $query = "CREATE TABLE dept_justice_article (".
                "article_id BIGINT NOT NULL AUTO_INCREMENT,".
                "st_grade int NOT NULL,".
                "st_id int DEFAULT 0,".
                "st_name TINYTEXT NOT NULL,".
                "ac_name TINYTEXT NOT NULL,".
                "article char(255) NOT NULL)";

        $result = $db -> query($query);
        if($result) echo "<p>WOW</p>";
        else echo "<p>What happened?</p>";
        /*$query = "insert into dept_justice_article ".
                "(st_grade, st_name, ac_name, article) ".
                "VALUES ".
                "('".$grade."', '".$name."', '".$accuser."', '".$article."')";

        $result = $db -> query($query);

        if($result) {
            echo $db->affected_rows." article inserted into database.";
        } else {
            echo "An error has occured. The item was not added.";
        }

        $db->close();*/
    ?>
</body>
</html>