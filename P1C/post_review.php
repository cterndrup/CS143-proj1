<!DOCTYPE HTML>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<!-- If insert is successful, display message and review -->
<!-- If not, display appropriate error and prompt user back to prev page -->
<?php
    // connect to MySQL server
    mysql_connect()
    // select MySQL database
    mysql_select_db()
    // query the db to insert new review into Review table
    mysql_db_query()
    // fetch result row form query... what happens on insert?
    mysql_fetch_array()
    // close connection to MySQL server
    mysql_close()
?>
</body>
</html>
