<!DOCTYPE HTML>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<?php
    // script to add actors, directors to movie
    
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

    // function to print appropriate form depending on from where
    // user navigated to this page
    function print_form($from, $first=NULL, $last=NULL, $id=NULL) {
        if ($from == "main" or $from == "all") {
            echo "<form action=add_to_movie.php method='GET'>";
            echo "First Name: <input type='text' name='first'><br>";
            echo "Last Name: <input type='text' name='last'><br>";
            echo "Movie: <input type='text' name='title'><br>";
            if ($from == "all" and $_GET["person_type"] == "actor") {
                echo "Role: <input type='text' name='role'><br>";
            }
            echo "<input type='submit' value='Add!'>";
            if ($_GET["person_type"]) {
                $person_type = $_GET["person_type"];
                echo "<input type='hidden' name='person_type' value='$person_type'>";
            }
            echo "<input type='hidden' name='from' value='self'>";
            echo "</form>";
        }
        else if ($from == "actor" or $from == "director") {
            echo "<form action=add_to_movie.php method='GET'>";
            echo "First Name: <input type='text' name='first' value='$first' readonly><br>";
            echo "Last Name: <input type='text' name='last' value='$last' readonly><br>";
            echo "Movie: <input type='text' name='title'><br>";
            if ($from == "actor") echo "Role: <input type='text' name='role'><br>";
            echo "<input type='submit' value='Add'>";
            echo "<input type='hidden' name='id' value='$id'>";
            echo "<input type='hidden' name='from' value='self'>";
            echo "<input type='hidden' name='person_type' value='$from'>";
            echo "</form>";
        }
        else {
            exit("print_form called incorrectly: see definition for correct use");
        }
    }

    // links back to page this page was visited from
    if ($_GET["from"] == "main" or $_GET["from"] == "all") {
        $returnURL = $_GET["from"] == "main" ? "main.php" : "all.php?category=Movie"; 
        $previous_page = $_GET["from"] == "main" ? $_GET["from"] : "Movies";
        echo "<a href='$returnURL'>Back to $previous_page page</a><br>";
        print_form($_GET["from"]);
    } else if ($_GET["from"] == "actor" or $_GET["from"] == "director") {
        $person_type = $_GET["from"];
        $first = $_GET["first"];
        $last = $_GET["last"];
        $id = $_GET["id"];
        $personURL = "$person_type.php?first=$first&last=$last";
        echo "<a href='$personURL'>Back to $first $last</a><br><br>";
        print_form($person_type, $first, $last, $id);
    } else if ($_GET["from"] == "self") {
        // submit user's entry into MovieActor or MovieDirector

        // links back to actor or director's page
        $person_type = $_GET["person_type"];
        $first = $_GET["first"];
        $last = $_GET["last"];
        $personURL = "$person_type.php?first=$first&last=$last";
        echo "<a href='$personURL'>Back to $first $last</a><br><br>";
    
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
        
        // find the id of the movie submitted
        $title = $_GET["title"];
        $s_title = sanitize_input($title, "string", $db_connection);
        $query = "select id from Movie where title=$s_title";
        $resource = mysql_query($query, $db_connection);
        if (!$resource) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
        $num_results = mysql_num_rows($resource);
        if ($num_results == 0) {
            echo "<h1>Movie $title does not exist in the database</h1>";
            mysql_close($db_connection);
            exit(1);
        }
        $row = mysql_fetch_array($resource, MYSQL_ASSOC);
        $mid = sanitize_input($row["id"], "number", $db_connection);

        // find the id of the actor or director submitted if needed
        $person_type = ucfirst($person_type);
        $s_first = sanitize_input($first, "string", $db_connection);
        $s_last = sanitize_input($last, "string", $db_connection);
        $id = 0; // initialize variable to be set in if/else below
        if (!$_GET["id"]) {
            // query to find actor with name submitted
            $query = "select id from $person_type where first=$s_first and last=$s_last"; 
            $resource = mysql_query($query, $db_connection);
            if (!$resource) {
                echo mysql_error($db_connection);
                mysql_close($db_connection);
                exit(1);
            }
            $num_results = mysql_num_rows($resource);
            if ($num_results == 0) {
                echo "<h1>$person_type $first $last does not exist in the database</h1>";
                mysql_close($db_connection);
                exit(1);
            }
            $row = mysql_fetch_array($resource, MYSQL_ASSOC);
            $id = sanitize_input($row["id"], "number", $db_connection); 
        } else {
            $id = sanitize_input($_GET["id"], "number", $db_connection);
        }

        // query to add actor or director to movie
        $table = "Movie".$person_type;
        if ($person_type == "Actor") {
            $role = sanitize_input($_GET["role"], "string", $db_connection);
            $query = "insert into $table values ($mid, $id, $role)";
            $result = mysql_query($query, $db_connection);
            if (!$result) {
                echo mysql_error($db_connection);
                mysql_close($db_connection);
                exit(1);
            }
        } else { // otherwise person_type == "director"
            $query = "insert into $table values ($mid, $id)";
            $result = mysql_query($query, $db_connection);
            if (!$result) {
                echo mysql_error($db_connection);
                mysql_close($db_connection);
                exit(1);
            }
        }

        // print success message
        echo "<h1>$first $last added successfully to $title!</h1>";

        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            echo mysql_error($db_connection);
            exit(1);
        }
         
    } else {
        // if this page is visited without parameters passed in URL
    }

?>
</body>
</html>

