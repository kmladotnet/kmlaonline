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

        $query = "CREATE TABLE IF NOT EXISTS dept_justice_article (
                article_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                st_grade int NOT NULL,
                st_id int DEFAULT 0,
                st_name TINYTEXT NOT NULL,
                ac_name TINYTEXT NOT NULL,
                article char(255) NOT NULL);";

        $result = $db -> query($query);
        if($result === TRUE) echo "<p>WOW</p>";
        else echo "<p>What happened?</p>";

        $query = "insert into dept_justice_article ".
                "(st_grade, st_name, ac_name, article) ".
                "VALUES ".
                "('".$grade."', '".$name."', '".$accuser."', '".$article."')";

        $result = $db -> query($query);

        if($result) {
            echo $db->affected_rows." article inserted into database.";
        } else {
            echo "An error has occured. The item was not added.";
        }

        $query = "select * from dept_justice_article";
        $result = $db -> query($query);
        $num_results = $result->num_rows;

        echo "<p>Number of articles found: ".$num_results."</ p>";

        for ($i=0; $i < $num_results; $i++){
            $row = $result->fetch_assoc();
            echo "<p><strong>".($i + 1).". Grade: ";
            echo htmlspecialchars(stripslashes($row['st_grade']));
            echo "</strong><br />Name: ";
            echo stripcslashes($row['st_name']);
            echo "<br />Accuser";
            echo stripcslashes($row['ac_name']);
            echo "<br />Article: ";
            echo stripcslashes($row['article']);
            echo "</p>";
        }

        $result -> free();
    ?>
</body>
</html>