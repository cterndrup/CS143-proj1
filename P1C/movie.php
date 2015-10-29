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
    $qtitle = "'$title'";
    $query = "select * from Movie where title=".$qtitle;
    
    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);

    // fetch movie tuple from the query results
    $row = mysql_fetch_array($resource, MYSQL_ASSOC);
    echo "Released: ".$row["year"]."<br>";
    echo "Rated: ".$row["rating"]."<br>";
    echo "Produced by: ".$row["company"]."<br>";
   
    echo "<h3>Actors/Actresses</h3>"; 
    // query for all actors/actresses in movie
    $mid = $row["id"];
    $query = "select Actor.first, Actor.last from Actor, MovieActor where MovieActor.mid=$mid and MovieActor.aid=Actor.id order by Actor.last asc";

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

    // show all actors/actresses in movie
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        $first = $row["first"];
        $last = $row["last"];
        $full_name = "$first $last";
        $actorURL = "actor.php?first=$first&last=$last";
        echo "<a href='$actorURL'>$full_name</a><br>";
    }
   
    // Reviews section
    $actionURL = "post_review.php";
    echo "<h3>Reviews</h3>"; 
    echo "<h4>Add a Review!</h4>";
    echo "<form action='$actionURL' method='POST'>";
    echo "Your name: <input type='text' name='name'><br>";
    echo "Movie: <input type='text' name='movie' value='$title' readonly><br>";
    echo "Rating (1 to 5): <input type='text' name='rating'><br>";
    echo "Comments: <br><textarea rows='10' cols='50' name='comment'></textarea><br>";
    echo "<input type='submit' value='Submit!'>";
    echo "</form>";

    // query for all user comments and allow user to add comment
    $query = "select * from Review where mid=".$mid;

    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);
    
    // show all user comments and allower user to add comment
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        foreach ($row as $attr) {
            echo "$attr ";
        }
        echo "<br><br>";
    }
    
    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

