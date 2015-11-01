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
                   exit("Error in user input: try again");
               }
               else return "'".$sanitized_input."'";
           }
           else return $input;
       } 
    }
    
    // link to return to movie page
    $title = $_GET["title"];
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
    
    // query the db for the movie id
    $query = "select id from Movie where title='$title'";
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        echo mysql_error($db_connection);
        mysql_close($db_connection);
        exit(1);
    }
    $row = mysql_fetch_array($resource, MYSQL_ASSOC);
    $mid = $row["id"];

    // query the db to insert new review into Review table
    $name = sanitize_input($_GET["name"], "string", $db_connection);
    $time = date('Y-m-d H:i:s');
    $rating = sanitize_input($_GET["rating"], "number", $db_connection);
    $comment = sanitize_input($_GET["comment"], "string", $db_connection);
    $insert_comment = "insert into Review values ($name, '$time', $mid, $rating, $comment)";
    $result = mysql_query($insert_comment, $db_connection);
    if (!$result) {
        echo mysql_error($db_connection);
        mysql_close($db_connection);
        exit(1);
    }
    
    // show successful insert message
    $name = $_GET["name"];
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
