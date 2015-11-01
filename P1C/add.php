<!DOCTYPE HTML>
<html>
<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" type="text/css" href="universal.css">
</head>

<body>
<?php
    // functions to print form based on whether new movie, actor, or
    // director will be added to database
    function print_form($category) {
        echo "<form action='add.php' method='GET'>";
        echo "<input type='hidden' name='new' value='$category'>";
        if ($category == "Movie") {
            echo "<strong>Title:</strong> <input type='text' name='title'><br>";
            echo "<strong>Year:</strong> <input type='text' name='year'><br>";
            echo "<strong>Rating:</strong> <select type='text' name='rating'>";
            echo "<option>G</option><option>PG</option>";
            echo "<option>PG-13</option><option>R</option>";
            echo "<option>NC-17</option><option>surrendere</option>";
            echo "</select><br>";
            echo "<strong>Genre:</strong> <br>";
            echo "<input type='checkbox' name='genre[]' value='Action'> Action ";
            echo "<input type='checkbox' name='genre[]' value='Adult'> Adult ";
            echo "<input type='checkbox' name='genre[]' value='Adventure'> Adventure ";
            echo "<input type='checkbox' name='genre[]' value='Animation'> Animation ";
            echo "<input type='checkbox' name='genre[]' value='Comedy'> Comedy ";
            echo "<input type='checkbox' name='genre[]' value='Crime'> Crime ";
            echo "<input type='checkbox' name='genre[]' value='Documentary'> Documentary ";
            echo "<input type='checkbox' name='genre[]' value='Drama'> Drama ";
            echo "<input type='checkbox' name='genre[]' value='Family'> Family ";
            echo "<input type='checkbox' name='genre[]' value='Fantasy'> Fantasy <br>";
            echo "<input type='checkbox' name='genre[]' value='Horror'> Horror ";
            echo "<input type='checkbox' name='genre[]' value='Musical'> Musical ";
            echo "<input type='checkbox' name='genre[]' value='Mystery'> Mystery ";
            echo "<input type='checkbox' name='genre[]' value='Romance'> Romance ";
            echo "<input type='checkbox' name='genre[]' value='Sci-Fi'> Sci-Fi ";
            echo "<input type='checkbox' name='genre[]' value='Short'> Short ";
            echo "<input type='checkbox' name='genre[]' value='Thriller'> Thriller ";
            echo "<input type='checkbox' name='genre[]' value='War'> War ";
            echo "<input type='checkbox' name='genre[]' value='Western'> Western<br> ";
            echo "<strong>Company</strong>: <input type='text' name='company'><br>";
        }
        else if ($category == "Actor") {
            echo "<strong>First Name:</strong> <input type='text' name='first'><br>";
            echo "<strong>Last Name:</strong> <input type='text' name='last'><br>";
            echo "<strong>Sex:</strong> <input type='text' name='sex'><br>";
            echo "<strong>Born:</strong> <input type='text' name='dob'><br>";
            echo "<strong>Died:</strong> <input type='text' name='dod'><br>";
        }
        else if ($category == "Director") {
            echo "<strong>First Name:</strong> <input type='text' name='first'><br>";
            echo "<strong>Last Name:</strong> <input type='text' name='last'><br>";
            echo "<strong>Born:</strong> <input type='text' name='dob'><br>";
            echo "<strong>Died:</strong> <input type='text' name='dod'><br>";
        }
        echo "<input type='submit' value='Add'>";
        echo "</form>";
    }

    // function to create query based on if movie, actor, or director
    // will be added to database
    function create_query($category, $id, $link_id) {
        if ($category == "Movie") {
            $title = sanitize_input($_GET["title"], "string", $link_id);
            $year = sanitize_input($_GET["year"], "number", $link_id);
            $rating = sanitize_input($_GET["rating"], "string", $link_id);
            $company = sanitize_input($_GET["company"], "string", $link_id);
            $query = "insert into Movie values($id, $title, $year, $rating, $company)";
        }
        else {
            $first = sanitize_input($_GET["first"], "string", $link_id);
            $last = sanitize_input($_GET["last"], "string", $link_id);
            $dob = sanitize_input($_GET["dob"], "string", $link_id);
            $dod = sanitize_input($_GET["dod"], "string", $link_id);
            if ($category == "Actor") {
                $sex = sanitize_input($_GET["sex"], "string", $link_id);
                $query = "insert into Actor values($id, $last, $first, $sex, $dob, $dod)";
            }
            else {
                $query = "insert into Director values($id, $last, $first, $dob, $dod)";
            }
        }
        return $query; 
    }

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


    if ($_GET["category"]) {
        // get what is to be added from URL
        $category = $_GET["category"];
        $plural = $category."s";

        $categoryURL = "all.php?category=$category";
        echo "<a href='$categoryURL'>Back to $plural</a><br>";  
        echo "<h1>Add a new $category!</h1>";

        // display form for adding new Movie, Actor, Director
        print_form($category);

    } else if ($_GET["new"]) {
        // determine what is being posted based on data sent from form 
        $category = $_GET["new"];
        
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
        $query = create_query($category, $new_id, $db_connection);
        $result = mysql_query($query, $db_connection);
        if (!$result) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
    
        // update id value in database
        $result = mysql_query($update, $db_connection);
        if (!$result) {
            echo mysql_error($db_connection);
            mysql_close($db_connection);
            exit(1);
        }
 
        // enter genres into MovieGenre table if movie was added
        if ($category == "Movie") {
            foreach ($_GET["genre"] as $genre) {
                $query = "insert into MovieGenre values ($new_id, '$genre')";
                $result = mysql_query($query, $db_connection);
                if (!$result) {
                    echo mysql_error($db_connection);
                    mysql_close($db_connection);
                    exit(1);
                }
            }
        }

        // show successful insert message or appropriate error message
        if ($category == "Movie") {
            $title = $_GET["title"];
            echo "<h1>$title added successfully!</h1>";
        }
        else {
            $full_name = $_GET["first"]." ".$_GET["last"];
            echo "<h1>$full_name successfully added!</h1>";
        }
    
        // close connection to MySQL server
        $closed = mysql_close($db_connection);
        if (!$closed) {
            echo mysql_error($db_connection);
            exit(1);
        }
 
    } else {
        // if pages accessed with no parameters passed in URL
        echo "<a href='main.php'>Back to home page</a>";
    }
    
?>
