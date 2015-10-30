<!DOCTYPE HTML>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<?php

    // function to create query based on if movie, actor, or director
    // will be added to database
    function create_query($category, $id) {
        if ($category == "Movie") {
            $title = $_POST["title"];
            $year = $_POST["year"];
            $rating = $_POST["rating"];
            $company = $_POST["company"];
            $query = "insert into Movie values($id, '$title', $year, '$rating', '$company')";
        }
        else {
            $first = $_POST["first"];
            $last = $_POST["last"];
            $dob = $_POST["dob"];
            $dod = $_POST["dod"];
            if ($category == "Actor") {
                $sex = $_POST["sex"];
                $query = "insert into Actor values($id, '$last', '$first', '$sex', '$dob', '$dod')";
            }
            else {
                $query = "insert into Director values($id, '$last', '$first', '$dob', '$dod')";
            }
        }
        return $query; 
    }

    if ($_POST) {
        // determine what is being posted based on data sent from form 
        $category = 0;
        if ($_POST["title"]) {
            $category = "Movie";
        } else if ($_POST["sex"]) {
            $category = "Actor";
        } else {
            $category = "Director";
        }
        
        // links to navigate back to add page
        $addURL = "add.php?category=$category";
        echo "<a href='$addURL'>Back to add $category page</a><br>";

        // handle posting of form
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

        // get next movie id or person id
        $query = 0;
        $update = 0;
        if ($category == "Movie") {
            $query = "select id from MaxMovieID";
            $update = "update MaxMovieID set id=id+1";
        }
        else {
            $query = "select id from MaxPersonID";
            $update = "update MaxPersonID set id=id+1";
        }
   
        // issue query to get next movie or person ID 
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        $new_id = $row["id"];
    
        // query the db to insert into appropriate table
        $query = create_query($category, $new_id);
        $result = mysql_query($query, $db_connection);
        if (!$result) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
    
        // show successful insert message or appropriate error message
        if ($category == "Movie") {
            $title = $_POST["title"];
            echo "<h1>$title added successfully!</h1>";
        }
        else {
            $full_name = $_POST["first"]." ".$_POST["last"];
            echo "<h1>$full_name successfully added!</h1>";
        }
    
        // update id value in database
        $result = mysql_query($update, $db_connection);
        if (!$result) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
 
        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            echo mysql_error($db_connection);
            exit(1);
        }
    
    }
    
?>
</body>
</html>
