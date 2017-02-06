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
        }//123
?>
</body>
</html>