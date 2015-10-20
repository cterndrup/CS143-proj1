<!DOCTYPE html>
<html>
<head><title>Query Interface for MySQL Movie DB</title></head>

<body>
<h1>Colin's Movie Database</h1>
<!-- instructions for using query interface -->
<h3>Enter a MySQL query in the text area below to query the Movie database!</h3>
<p>Example: SELECT title FROM Movie WHERE rating='PG-13';</p>
<h3>Available Relations</h3>
<ul>
<li>Movie(id, title, year, rating, company)</li>
<li>Actor(id, last, first, sex, dob, dod)</li>
<li>Director(id, last, first, dob, dod)</li>
<li>MovieGenre(mid, genre)</li>
<li>MovieDirector(mid, did)</li>
<li>MovieActor(mid, aid, role)</li>
<li>Review(name, time, mid, rating, comment)</li>
<li>MaxPersonID(id)</li>
<li>MaxMovieID(id)</li>
</ul>

<!-- FORM for user input -->
<form method="GET" action="query.php">
<!-- TEXTAREA for entering query -->
<textarea name="query" rows=10 cols=50></textarea><br>
<!-- submit button -->
<input type="submit" value="Submit">
</form>

<!-- Process user input to query database -->
<!-- It is assumed for Part B that all input is safe -->
<?php
if ($_GET["query"]) {
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
    // get user input
    $query = $_GET["query"];

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
    echo '<table border="1" style="width:50%">';
    // get table headers
    $r = mysql_fetch_row($resource);
    $num_cols = mysql_num_fields($resource);
    for ($i=0; $i<$num_cols; $i++) {
        $field_name = mysql_field_name($resource, $i);
        echo "<th>$field_name</th>";
    }
    $reset_ptr = mysql_data_seek($resource, 0); // reset internal result pointer
    if (!$reset_ptr) exit(mysql_errno());
    
    // fetch each tuple from the query results
    while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        echo "<tr>";
        foreach ($row as $attr) {
            echo "<td>$attr</td>";
        }
        echo "</tr>";
    }
    echo '</table>';

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
}
?>

</body>
</html>
