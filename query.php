<!DOCTYPE html>
<html>
<head><title>Query Interface for MySQL Movie DB</title></head>

<body>
<!-- instructions for using query interface -->
<p>Enter a MySQL query in the text area below to query the Movie database!</p>
<p>Example: SELECT title FROM Movie WHERE rating='PG-13';</p>
<p>Available Relations</p>
<ul>
<li>Movie</li>
<li>Actor</li>
<li>Director</li>
<li>MovieGenre</li>
<li>MovieDirector</li>
<li>MovieActor</li>
<li>Review</li>
<li>MaxPersonID</li>
<li>MaxMovieID</li>
</ul>
<h2>Add Schema above to improve instructions</h2>

<form method="GET" action="query.php">
<!-- TEXTAREA for entering query -->
<textarea name="query" rows=10 cols=50></textarea><br>
<!-- submit button -->
<input type="submit" value="Submit">
</form>

<!-- Process user input to query database -->
<!-- It is assumed for Part B that all input is safe -->
<?php
// connect to MySQL server
$db_connection = mysql_connect("localhost", "cs143", "");
if (!$db_connection) echo "Failed to connect to MySQL server.";

// select which database to access
$database = "CS143";
$db_access= mysql_select_db($database, $db_connection);
if (!$db_access) echo "Could not access $database database";

// get user input
$query = $_GET["query"];

// issue query
$resource = mysql_query($query, $db_connection);
if (!$resource) echo "Query: $query failed.";
else echo "Query: $query succeeded.";

// retrieve results from query
echo '<table border="1" style="width:50%">';
// get table headers
//echo "<tr> <th> ... </th> </tr>"; // add table headers
while ($row = mysql_fetch_array($resource, MYSQL_ASSOC)) {
        echo "<tr>";
    foreach ($row as $entry) {
        echo "<td>$entry</td>";
    }
        echo "</tr>";
}
echo '</table>';

// close connection to MySQL
$closed = mysql_close($db_connection);
if (!$closed) echo "Failure to close connection to MySQL server.";
?>

<!-- Presents results in HTML Table -->

</body>
</html>
