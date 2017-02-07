<!DOCTYPE html>
<html>
<head>
    <title>Book-O-Roma Search Results</title>
</head>
<body>
    <h1>Book-O-Rama Search Results</h1>
    <?php
        $searchtype = $_POST['searchtype'];
        $searchterm = trim($_POST['serachterm']);

        if(!$searchterm || !$searchterm) {
            echo 'You have not entered search details. Please go back and try again.';
            exit;
        }

        include "lib.php";

        //Gets the current configuration setting of magic_quotes_gpc
        if(!get_magic_quotes_gpc()) {
            $searchtype = addslashes($searchtype);
            $searchterm = addslashes($searchterm);
        }



        if(mysqli_connect_errno()) {
            echo 'Error: Could not connect to database. Please try again later.';
            exit;
        }

        $query = "select * from books where ".$searchtype." like '%".$searchterm."%'";
        $result = $db -> query($query);
        $num_results = $result->num_rows;

        echo "<p>Number of books found: ".$num_results."</p";

        for ($i=0; $i < $num_results; $i++){
            $row = $result->fetch_assoc();
            echo "<p><strong>".($i + 1).". Titel: ";
            echo htmlspecialchars(stripslashes($row['title']));
            echo "</strong><br />Author: ";
            echo stripcslashes($row['author']);
            echo "<br />ISBN: ";
            echo stripcslashes($row['isbn']);
            echo "<br />PRICE: ";
            echo stripcslashes($row['price']);
            echo "</p>";
        }

        $result -> free();
        $db -> close();
    ?>
</body>
</html>