<!DOCTYPE HTML>
<html>
<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<?php
    // HANDLE AND DISPLAY SEARCH RESULTS
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

    // function to create query depending on where search was run from
    function create_query($search_input, $origin, $link_id) {
        $query = 0;
        if ($origin == "actor" or $origin == "director") {
            $search_input = explode(" ", $search_input);
            $count = count($search_input);
            $table = $origin == "actor" ? "Actor" : "Director";
            if ($count >= 2) {
                $first = sanitize_input($search_input[0], "string", $link_id);
                $last = sanitize_input($search_input[1], "string", $link_id);
                $query = "select *  from $table where (first=$first and last=$last) or (first=$last and last=$first)";
            } else if ($count == 1) {
                $name = sanitize_input($search_input[0], "string", $link_id);
                $query = "select * from $table where first=$name or last=$name";
            }
            else {
                exit("create query called incorrectly: see def for correct use");
            }
        } else if ($origin == "movie") {
              $title = mysql_real_escape_string($search_input, $link_id);
              $query = "select * from Movie where title like '%$title%'";
        } else {
            exit("create query called incorrectly: see def for correct use");
        } 
        return $query;
    }


 
    // retrieve user input and what page it came from
    $search_input = $_GET["search"];
    $origin = $_GET["origin"];
    
    // link back to previous page
    if ($origin == "main") {
        $returnURL = "main.php";
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
    if (empty($search_input)) exit("No text entered in search box. Return to previous page and try again.");

    // search header
    echo "<h1>Search results for '$search_input'</h1>";

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
    
    // from main
        // search for input in actor, movie, director
    if ($origin == "main") {
        // actor
        echo "<h2>Actors</h2>";
        $query = create_query($search_input, "actor", $db_connection); 
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
                $actorURL = "actor.php?first=$first&last=$last";
                echo "<li><a href='$actorURL'>$first $last</a></li>";
            }
            echo "</ul>";
        }

        echo "<h2>Movies</h2>";
        // movie
        $query = create_query($search_input, "movie", $db_connection);
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
                $title = $row["title"];
                $movieURL = "movie.php?title=$title";
                echo "<li><a href='$movieURL'>$title</a></li>";
            }
            echo "</ul>";
        }

        echo "<h2>Directors</h2>";
        // director
        $query = create_query($search_input, "director", $db_connection);
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
        }
    }

    // from actor
        // search for input in actor
    else if ($origin == "actor") {
        // actor 
        echo "<h2>Actors</h2>";
        $query = create_query($search_input, $origin, $db_connection); 
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
        }
    }
    
    else {
        // page reached without URL parameters
    }

    // close database connection
    $closed = mysql_close($db_connection);
    if (!$closed) {
        echo mysql_error($db_connection);
        exit(1);
    }
?>
</body>
</html>
