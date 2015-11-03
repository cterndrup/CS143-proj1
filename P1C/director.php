<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<!-- Return to home page of Directors page -->
<a href="index.php">Return to home page</a><br>
<a href="all.php?category=Director">Return to Directors page</a><br>
<?php 
    // DISPLAY INFO ABOUT DIRECTOR
    // first name and last name
    if ($_GET) {
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
        echo "<strong>First Name:</strong> $first<br>";
        echo "<strong>Last Name:</strong> $last<br>";
        echo "<strong>Date of Birth:</strong> ".$row["dob"]."<br>";
        if ($row["dod"]) echo "<strong>Date of Death:</strong> ".$row["dod"]."<br>";
        else echo "<strong>Date of Death:</strong> Still Alive<br>";
        $did = $row["id"];

        echo "<h3>Movies</h3>";
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
            $addToMovieURL = "add_to_movie.php?from=director&first=$first&last=$last&id=$did";
            echo "<br><br><a href=$addToMovieURL>Add $first $last to a movie!</a><br>";
            exit(0);
        }

        // show all movies director directed
        while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
            $title = $row["title"];
            $titleURL = preg_replace("/ /", "+", $title);
            $movieURL = "movie.php?title=$titleURL";
            echo "<a href='$movieURL'>$title</a><br>";
        }

        $addToMovieURL = "add_to_movie.php?from=director&first=$first&last=$last&id=$did";
        echo "<br><a href=$addToMovieURL>Add $first $last to a movie!</a><br>";

        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
        }
    }
?>

