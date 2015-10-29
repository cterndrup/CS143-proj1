<!DOCTYPE html>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<a href="main.php">Back to home page</a>
<?php
    // search box
    echo '<form action="search.php" method="GET">';
    echo "Search for a _____!";
    echo '<input type="text" name="query">';
    echo '<input type="submit" value="Search"><br>';
    echo "</form>";

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
    // get all of category specified in url
    if ($_GET["category"]) {
        $category = $_GET["category"];
        if ($category == "Movie") {
            $query = "select title, year from ".$category;
        }
        else if ($category == "Actor") {
            $query = "select first, last from ".$category;
        }
        else if ($category == "Director") {
            $query = "select first, last from ".$category;
        }
    }
    else { exit("ERROR: link failed"); }

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
            $row_attrs = $row_attrs." ".$attr;
        }
        //echo "</tr>";
        if ($category == "Movie") {
            $title = preg_replace("/ /", "+", $row["title"]);
            $movieURL = "movie.php?title=".$title;
            echo "<br><a href=$movieURL>$row_attrs</a><br>";
            unset($row_attrs);
        }
        else if ($category == "Actor") {
            $first = $row["first"];
            $last = $row["last"];
            $actorURL = "actor.php?first=".$first."&"."last=".$last;
            echo "<br><a href=$actorURL>$row_attrs<br>";
            unset($row_attrs);
        }
        else if ($category == "Director") {
            $first = $row["first"];
            $last = $row["last"];
            $directorURL = "director.php?first=".$first."&"."last=".$last;
            echo "<br><a href=$directorURL>$row_attrs<br>";
            unset($row_attrs);
        }
    }
    //echo '</table>';

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

</body>
</html>
