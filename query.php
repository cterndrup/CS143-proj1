<!DOCTYPE html>
<html>
<head><title>Query Interface for MySQL Movie DB</title></head>

<body>
<!-- instructions for using query interface -->
<p>Enter a MySQL query in the text area below to query the Movie database!</p>
<p>Example: SELECT title FROM Movie WHERE rating='PG-13';</p>

<form method="GET" action="query.php">
<!-- TEXTAREA for entering query -->
<textarea name="query" rows=10 cols=50></textarea><br>
<!-- submit button -->
<input type="submit" value="Submit">
</form>

<!-- Process user input to query database -->
<!-- It is assumed for Part B that all input is safe -->
<?php

?>

<!-- Presents results in HTML Table -->

</body>
</html>
