<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
</head>

<body>
<!-- Return to home page of Directors page -->
<a href="main.php">Return to home page</a><br>
<a href="all.php?category=Director">Return to Directors page</a><br>
<?php 
    // DISPLAY INFO ABOUT DIRECTOR
    // first name and last name
    $first = $_GET["first"];
    $last = $_GET["last"];
    echo "<h1>$first $last</h1>";
    // dob
    // dod
    // links to movies directed

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

    // create query to get all director info
    $query = "select * from Director where first='$first' and last='$last'";
    
    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);

    // fetch each tuple from the query results
    $row = mysql_fetch_array($resource, MYSQL_ASSOC);
    echo "Full Name: $first $last<br>";
    echo "Born: ".$row["dob"]."<br>";
    if ($row["dod"]) echo "Died: ".$row["dod"]."<br>";
    $did = $row["id"];

    echo "<h3>Movies</h3>";
    $addToMovieURL = "add_to_movie.php?from=director&first=$first&last=$last&id=$did";
    echo "<a href=$addToMovieURL>Add $first $last to a movie!</a><br><br>";
    // query for all movies director directed
    $query = "select title from Movie, MovieDirector where did=$did and mid=id order by title asc";

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

    // show all movies director directed
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

