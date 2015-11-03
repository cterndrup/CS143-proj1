<!DOCTYPE HTML>
<html>
<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<?php
    // HANDLE AND DISPLAY SEARCH RESULTS

    // function to create query depending on where search was run from
    function create_query($search_input, $origin, $link_id) {
        $query = 0;
        if ($origin == "actor" or $origin == "director") {
            $table = $origin == "actor" ? "Actor" : "Director";
            $search_input = mysql_real_escape_string($search_input, $link_id);
            $query = "select * from $table where CONCAT_WS(' ', first, last) like '%$search_input%'  or CONCAT_WS(' ', last, first) like '%$search_input%' order by last asc, first asc";
        } else if ($origin == "movie") {
              $title = mysql_real_escape_string($search_input, $link_id); 
              $query = "select * from Movie where title like '%$title%' order by title asc";
        } else {
            exit("<br><strong>create_query called incorrectly: see def for correct use</strong>");
        } 
        return $query;
    }


 
    // retrieve user input and what page it came from
    if ($_GET) {
        $search_input = trim($_GET["search"]);
        $origin = ($_GET["origin"]);
    
        // link back to previous page
        if ($origin == "index") {
            $returnURL = "index.php";
            echo "<a href='$returnURL'>Back to home page</a><br>";
        } else if ($origin == "actor") {
            $returnURL = "all.php?category=Actor";
            echo "<a href='$returnURL'>Back to Actors page</a><br>";
        } else if ($origin == "movie") {
            $returnURL = "all.php?category=Movie";
            echo "<a href='$returnURL'>Back to Movies page</a><br>";
        }  else if ($origin == "director") {
            $returnURL = "all.php?category=Director";
            echo "<a href='$returnURL'>Back to Directors page</a><br>";
        } 

        // check if search input is empty 
        if (empty($search_input)) exit("<br><strong>No text entered in search box. Return to previous page and try again.</strong>");

        // search header
        echo "<h1>Search results for '$search_input'</h1>";

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
    
        // from index
        // search for input in actor, movie, director
        if ($origin == "index") {
            
            // movie
            echo "<h2>Movies</h2>";
            $query = create_query($search_input, "movie", $db_connection);
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                $error = mysql_error($db_connection);
                echo "<br><strong>$error</strong>";
                mysql_close($db_connection);
                exit(1);
            } 
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'";
            } else {
                mysql_data_seek($resource, 0);
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $title = $row["title"];
                    $year = $row["year"];
                    $movieURL = "movie.php?title=$title";
                    echo "<li><a href='$movieURL'>$title ($year)</a></li>";
                }
                echo "</ul>";
            }

            // actor
            echo "<h2>Actors</h2>";
            $query = create_query($search_input, "actor", $db_connection); 
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                $error = mysql_error($db_connection);
                echo "<br><strong>$error</strong>";
                mysql_close($db_connection);
                exit(1);
            }
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'"; 
            } else {
                mysql_data_seek($resource, 0);
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $first = $row["first"];
                    $last = $row["last"];
                    $actorURL = "actor.php?first=$first&last=$last";
                    echo "<li><a href='$actorURL'>$first $last</a></li>";
                }
                echo "</ul>";
            }

            //echo "<h2>Directors</h2>";
            // director
            /*$query = create_query($search_input, "director", $db_connection);
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                echo mysql_error($db_connection);
                mysql_close($db_connection);
                exit(1);
            }
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'";
            } else { 
                mysql_data_seek($resource, 0);
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $first = $row["first"];
                    $last = $row["last"];
                    $directorURL = "director.php?first=$first&last=$last";
                    echo "<li><a href='$directorURL'>$first $last</a></li>";
                }
                echo "</ul>";
            }*/
        }

        // from actor
            // search for input in actor
        else if ($origin == "actor") {
            // actor 
            echo "<h2>Actors</h2>";
            $query = create_query($search_input, $origin, $db_connection); 
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                $error = mysql_error($db_connection);
                echo "<br><strong>$error</strong>";
                mysql_close($db_connection);
                exit(1);
            }
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'";
            } else {
                mysql_data_seek($resource, 0);
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $first = $row["first"];
                    $last = $row["last"];
                    $actorURL = "actor.php?first=$first&last=$last";
                    echo "<li><a href='$actorURL'>$first $last</a></li>";
                }
                echo "</ul>";
            }
        }

        // from movie
            // search for input in movie
        else if ($origin == "movie") {
            // movie
            echo "<h2>Movies</h2>";
            $query = create_query($search_input, $origin, $db_connection);
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                $error = mysql_error($db_connection);
                echo "<br><strong>$error</strong>";
                mysql_close($db_connection);
                exit(1);
            }
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'";
            } else { 
                mysql_data_seek($resource, 0);
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $title = $row["title"];
                    $movieURL = "movie.php?title=$title";
                    echo "<li><a href='$movieURL'>$title</a></li>";
                }
                echo "</ul>";
            }
        }

        // from director
            // search for input in director
        else if ($origin == "director") {
            // director
            echo "<h2>Directors</h2>";
            $query = create_query($search_input, $origin, $db_connection);
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                $error = mysql_error($db_connection);
                echo "<br><strong>$error</strong>";
                mysql_close($db_connection);
                exit(1);
            }
            if (!mysql_fetch_array($resource, MYSQL_ASSOC)) {
                echo "No results found for '$search_input'";
            } else {
                mysql_data_seek($resource, 0); 
                echo "<ul>";  
                while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
                    $first = $row["first"];
                    $last = $row["last"];
                    $directorURL = "director.php?first=$first&last=$last";
                    echo "<li><a href='$directorURL'>$first $last</a></li>";
                }
                echo "</ul>";
            }
        }

        // close database connection
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
