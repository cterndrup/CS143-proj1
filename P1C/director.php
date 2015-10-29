<!DOCTYPE HTML>
<html>

<head>
    <title>Colin's Movie DB</title>
</head>

<body>
<?php 
    // DISPLAY INFO ABOUT DIRECTOR
    // first name and last name
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

    // create query to get all movie info
    $first = "'".$first."'";
    $last = "'".$last."'";
    $query = "select * from Director where first=".$first." and last=".$last;
    
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
            echo "$attr<br>";
        }
        //echo "</tr>";
    }
    //echo '</table>';

    // show all movies director directed

    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        exit("<br><strong>Error: Could not close connection to MySQL server</strong>");
    }
?>

