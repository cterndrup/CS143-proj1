<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
</head>

<body>
<?php
    // DISPLAY INFO ABOUT ACTOR/ACTRESS
    // first name and last name
    $first = $_GET["first"];
    $last = $_GET["last"];
    echo "<h1>$first $last</h1>";
    // sex
    // dob
    // dod
    // links to all movies actor appears in
    
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

    // create query to get all actor info
    $query = "select * from Actor where first='$first' and last='$last'";
    
    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);

    // fetch Actor tuple from the query results
    $row = mysql_fetch_array($resource, MYSQL_ASSOC);
    echo "Full Name: ".$row["first"]." ".$row["last"]."<br>";
    echo "Sex: ".$row["sex"]."<br>";
    echo "Born: ".$row["dob"]."<br>";
    if ($row["dod"]) echo "Died: ".$row["dod"]."<br>";

    echo "<h3>Movies</h3>";
    // query for all movies actor/actress has appeared in
    $aid = $row["id"];
    $query = "select title from Movie, MovieActor where aid=$aid and id=mid order by title asc";

    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) { 
        echo "N/A";
        exit(0);
    }

    // show all movies actor/actress has appeared in
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        $title = $row["title"];
        $titleURL = preg_replace("/ /", "+", $title);
        $movieURL = "movie.php?title=$titleURL";
        echo "<a href='$movieURL'>$title</a><br>";
    }

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

