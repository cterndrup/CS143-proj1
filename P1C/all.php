<!DOCTYPE html>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<a href="main.php">Back to home page</a><br><br>
<?php
    // get all of category specified in url
    $category = $_GET["category"];

    // search box
    echo "<h3>Search</h3>";
    echo '<form action="search.php" method="GET">';
    echo "Search for a $category!";
    echo '<input type="text" name="query">';
    echo '<input type="submit" value="Search"><br>';
    echo "</form>";
    
    // add movie, actor, or director
    $addURL = "add.php?category=$category";
    echo "<a href='$addURL'>Add new $category!</a><br>";
    if ($category == "Movie") {
        $addURL = "add_to_movie.php?from=all&category=Movie&person_type=";
        $addActorToMovieURL = $addURL."actor";
        $addDirectorToMovieURL = $addURL."director";
        echo "<a href='$addActorToMovieURL'>Add Actor to $category</a><br>";
        echo "<a href='$addDirectorToMovieURL'>Add Director to $category</a><br>";
    } 

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
   
   // create query to retrieve all movies, actors, or directors 
   if ($category == "Movie") {
        $query = "select title, year from $category order by title asc";
    }
    else if ($category == "Actor") {
        $query = "select first, last from $category order by last asc";
    }
    else if ($category == "Director") {
        $query = "select first, last from $category order by last asc";
    }

    $headline = $category."s";
    echo "<h1>$headline</h1>";
    // issue query
    $resource = mysql_query($query, $db_connection);
    if (!$resource) {
        $error = mysql_error($db_connection);
        exit("<br><strong>Error: ".$error."</strong>");
    } 
    // exit if no results returned from query
    if (!mysql_num_rows($resource)) exit(0);

    // fetch each tuple from the query results
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        if ($category == "Movie") {
            $title = $row["title"];
            $year = $row["year"];
            $titleURL = preg_replace("/ /", "+", $title);
            $movieURL = "movie.php?title=$titleURL";
            echo "<a href=$movieURL>$title ($year)</a><br><br>";
        }
        else if ($category == "Actor") {
            $first = $row["first"];
            $last = $row["last"];
            $actorURL = "actor.php?first=$first&last=$last";
            echo "<a href=$actorURL>$first $last<br><br>";
        }
        else if ($category == "Director") {
            $first = $row["first"];
            $last = $row["last"];
            $directorURL = "director.php?first=$first&last=$last";
            echo "<a href=$directorURL>$first $last<br><br>";
        }
    }

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

</body>
</html>
