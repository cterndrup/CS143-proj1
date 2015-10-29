<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
</head>

<body>
<?php
    // DISPLAY INFO ABOUT MOVIE
    // Movie title
    $title = $_GET["title"];
    echo "<h1>$title</h1>";
    // Release year
    // MPAA rating
    // Production Company
    // links to actors/actresses in movie
    // average score of movie based on user feedback
    // show all user comments
    // add comment button, sends user to add comment/review page for movie

    // connect to MySQL server
    $db_connection = mysql_connect("localhost", "cs143", "");
    if (!$db_connection) {
        exit("<br><strong>Error: Could not connect to MySQL server</strong>");
    }

    // select which database to access
    $database = "CS143";
    $db_access= mysql_select_db($database, $db_connection);
    if (!$db_access) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    }

    // create query to get all movie info
    $title = "'".$title."'";
    $query = "select * from Movie where title=".$title;
    
    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);

    // populate html table with results from query
    echo "<br>";
    //echo '<table border="1" style="width:50%">';
    // get table headers
    //$r = mysql_fetch_row($resource);
    //$num_cols = mysql_num_fields($resource);
    //for ($i=0; $i<$num_cols; $i++) {
        //$field_name = mysql_field_name($resource, $i);
        //echo "<th>$field_name</th>";
    //}
    //$reset_ptr = mysql_data_seek($resource, 0); // reset internal result pointer
    //if (!$reset_ptr) exit(mysql_errno());
    
    // fetch each tuple from the query results
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        //echo "<tr>";
        foreach ($row as $attr) {
            //echo "<td>$attr</td>";
            echo "$attr<br>";
        }
        //echo "</tr>";
    }
    //echo '</table>';
    
    // show all actors/actresses

    // show all user comments and allow user to add comment

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

