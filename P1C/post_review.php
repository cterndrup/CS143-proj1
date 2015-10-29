<!DOCTYPE HTML>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<!-- If insert is successful, display message and review -->
<!-- If not, display appropriate error and prompt user back to prev page -->
<?php
    // link to return to movie page
    $title = $_POST["title"];
    $titleURL = preg_replace("/  /", "+", $title);
    $movieURL = "movie.php?title=$titleURL";
    echo "<a href='$movieURL'>Return to $title</a><br>";

    // connect to MySQL server
    $db_connection = mysql_connect("localhost", "cs143", "");
    if (!$db_connection) exit("Error: Failure to connect to MySQL server");

    // select MySQL database
    $db = mysql_select_db("CS143", $db_connection);
    if (!$db) {
        echo mysql_error($db_connection);
        mysql_close($db_connection);
        exit(1);
    }

    // query the db to insert new review into Review table
    $name = $_POST["name"];
    $time = date('Y-m-d H:i:s'); 
    $mid = $_POST["mid"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];
    // need to escape ' in name and comment if they exist
    $esc_name = mysql_real_escape_string($name, $db_connection);
    $esc_comment = mysql_real_escape_string($comment, $db_connection);
    $insert_comment = "insert into Review values ('$esc_name', '$time', $mid, $rating, '$esc_comment')";
    $result = mysql_query($insert_comment, $db_connection);
    if (!$result) {
        echo mysql_error($db_connection);
        mysql_close($db_connection);
        exit(1);
    }
    
    // show successful insert message
    echo "<h1>Thanks for submitting a review, $name!</h1>";
   
    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        echo mysql_error($db_connection);
        exit(1);
    }
?>
</body>
</html>
