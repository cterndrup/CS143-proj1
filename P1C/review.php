<!DOCTYPE HTML>
<html>
<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<!-- If insert is successful, display message and review -->
<!-- If not, display appropriate error and prompt user back to prev page -->
<?php
    // function to sanitize form input before its used in query
    function sanitize_input($input, $type, $link_id) {
       if ($input == "") return "NULL";
       else {
           if ($type == "string") {
               $sanitized_input = mysql_real_escape_string($input, $link_id);
               if (!$sanitized_input) {
                   exit("<br><strong>Error in user input: try again</strong>");
               }
               else return "'".$sanitized_input."'";
           }
           else return $input;
       } 
    }
    
    if ($_GET) {
        // link to return to movie page
        $title = trim($_GET["title"]);
        $titleURL = preg_replace("/  /", "+", $title);
        $movieURL = "movie.php?title=$titleURL";
        echo "<a href='$movieURL'>Return to $title</a><br>";

        // connect to MySQL server
        $db_connection = mysql_connect("localhost", "cs143", "");
        if (!$db_connection) exit("<br><strong>Error: Failure to connect to MySQL server</strong>");

        // select MySQL database
        $db = mysql_select_db("CS143", $db_connection);
        if (!$db) {
            $error = mysql_error($db_connection);
            echo "<br><strong>$error</strong>";
            mysql_close($db_connection);
            exit(1);
        }
    
        // query the db for the movie id
        $query = "select id from Movie where title='$title'";
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            echo "<br><strong>$error</strong>";
            mysql_close($db_connection);
            exit(1);
        }
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        $mid = $row["id"];

        // query the db to insert new review into Review table
        $name = sanitize_input(trim($_GET["name"]), "string", $db_connection);
        $time = date('Y-m-d H:i:s');
        $rating = sanitize_input(trim($_GET["rating"]), "number", $db_connection);
        $comment = sanitize_input(trim($_GET["comment"]), "string", $db_connection);
        $insert_comment = "insert into Review values ($name, '$time', $mid, $rating, $comment)";
        $result = mysql_query($insert_comment, $db_connection);
        if (!$result) {
            $error = mysql_error($db_connection);
            echo "<br><strong>$error</strong>";
            mysql_close($db_connection);
            exit(1);
        }
    
        // show successful insert message
        $name = trim($_GET["name"]);
        echo "<h1>Thanks for submitting a review, $name!</h1>";
   
        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            $error = mysql_error($db_connection);
            echo "<br><strong>$error</strong>";
            exit(1);
        }
    } else {
        // page reached without URL parameters
        echo "<a href='index.php'>Back to home page</a>";
    }
?>
</body>
</html>
