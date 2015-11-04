<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<!-- Return to home page or actors page -->
<a href="index.php">Return to home page</a><br>
<a href="all.php?category=Movie">Return to Movies</a><br>
<?php
    // DISPLAY INFO ABOUT MOVIE
    // Movie title
    if ($_GET) {
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
        echo "<strong>Released:</strong> ".$row["year"]."<br>";
        echo "<strong>MPAA Rating:</strong> ".$row["rating"]."<br>";
        echo "<strong>Company:</strong> ".$row["company"]."<br>";
        
        // get movie director
        $mid = $row["id"];
        $query = "select first, last from MovieDirector, Director where did=id and mid=$mid order by last asc, first asc";
        // issue query
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            exit("<br><strong>Error: ".$error."</strong>");
        } 
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        $nrows = mysql_num_rows($resource);
        if (empty($row)) echo "<strong>Director:</strong> N/A<br>";
        else {
            echo "<strong>Director:</strong>";
            mysql_data_seek($resource, 0);
            $i = 1;
            while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                $first = $row["first"];
                $last = $row["last"];
                if ($i < $nrows) echo " $first $last,";
                else echo " $first $last";
                $i++;
            }
            echo "<br>";
        }
        
        // get movie genre
        $query = "select genre from MovieGenre where mid=$mid";
        // issue query
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            exit("<br><strong>Error: ".$error."</strong>");
        } 
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        $nrows = mysql_num_rows($resource);
        if (empty($row["genre"])) echo "<strong>Genre:</strong> N/A<br>";
        else {
            echo "<strong>Genre:</strong>";
            mysql_data_seek($resource, 0);
            $i = 1;
            while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                $genre = $row["genre"];
                if ($i < $nrows) echo " $genre,";
                else echo " $genre";
                $i++;
            }
            echo "<br>";
        }

        // get average user rating for movie
        $query = "select ROUND(AVG(rating),1) from Review where mid=$mid";
        // issue query
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            exit("<br><strong>Error: ".$error."</strong>");
        } 
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        if ($row["ROUND(AVG(rating),1)"]) echo "<strong>Average User Rating:</strong> ".$row["ROUND(AVG(rating),1)"];
        else echo "<strong>Average User Rating:</strong> N/A"; 
   
        echo "<h3>Actors/Actresses</h3>"; 
        // query for all actors/actresses in movie
        $query = "select Actor.first, Actor.last, MovieActor.role from Actor, MovieActor where MovieActor.mid=$mid and MovieActor.aid=Actor.id order by Actor.last asc";

        // issue query
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            exit("<br><strong>Error: ".$error."</strong>");
        } 
        // exit if no results returned from query
        if (!mysql_num_rows($resource)) {
            echo "N/A";
        }
        else {
            // show all actors/actresses in movie
            while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                $first = $row["first"];
                $last = $row["last"];
                $role = $row["role"];
                $actorURL = "actor.php?first=$first&last=$last";
                echo "<a href='$actorURL'>$first $last</a> as $role<br>";
            }
        }

        // Reviews section
        echo "<h3>Reviews</h3>"; 
        /*echo "<h4>Add a Review!</h4>";
        echo "<form action='review.php' method='GET'>";
        echo "<strong>Your name:</strong> <input type='text' name='name'><br>";
        echo "<strong>Movie:</strong> <input type='text' name='title' value='$title' readonly><br>";
        echo "<strong>Rating (1 to 5):</strong> <input type='text' name='rating'><br>";
        echo "<strong>Comments:</strong> <br><textarea rows='10' cols='50' name='comment'></textarea><br>";
        echo "<input type='submit' value='Submit!'>";
        echo "</form>";*/

        // query for all user comments and allow user to add comment
        $query = "select * from Review where mid=$mid order by time desc";

        // issue query
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            $error = mysql_error($db_connection);
            exit("<br><strong>Error: ".$error."</strong>");
        } 
        // if there are results returned from query, then show them
        if (mysql_num_rows($resource)) {
            // show all user comments
            while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                $reviewer = $row["name"];
                $review_time = $row["time"];
                $review_rating = $row["rating"];
                $review_comment = $row["comment"];
                echo "<br><strong>Reviewer:</strong> $reviewer<br>";
                echo "<strong>Time:</strong> $review_time<br>";
                echo "<strong>Rating:</strong> $review_rating<br>";
                echo "<strong>Comments:</strong> $review_comment<br>";
            }
        }

        // provide link to add a review
        echo "<br><a href='add_review.php?title=$title'>Add a review to $title!</a><br>";
    
        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
        }
    }
?>

