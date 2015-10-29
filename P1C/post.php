<!DOCTYPE HTML>
<html>
<head><title>Colin's Movie DB</title></head>

<body>
<?php
    // functions to print form based on whether new movie, actor, or
    // director will be added to database
    function print_form($category) {
        echo "<form action='post.php' method='POST'>";
        if ($category == "Movie") {
            echo "Title: <input type='text' name='title'><br>";
            echo "Year: <input type='text' name='year'><br>";
            echo "Rating: <input type='text' name='rating'><br>";
            echo "Company: <input type='text' name='company'><br>";
        }
        else if ($category == "Actor") {
            echo "First Name: <input type='text' name='first'><br>";
            echo "Last Name: <input type='text' name='last'><br>";
            echo "Sex: <input type='text' name='sex'><br>";
            echo "Born: <input type='text' name='dob'><br>";
            echo "Died: <input type='text' name='dod'><br>";
        }
        else if ($category == "Director") {
            echo "First Name: <input type='text' name='first'><br>";
            echo "Last Name: <input type='text' name='last'><br>";
            echo "Born: <input type='text' name='dob'><br>";
            echo "Died: <input type='text' name='dod'><br>";
        }
        echo "<input type='submit' value='Add'>";
        echo "</form>";
    }

    // function to create query based on if movie, actor, or director
    // will be added to database
    function create_query($category) {
        if ($category == "Movie") {
            $title = $_POST["title"];
            $year = $_POST["year"];
            $rating = $_POST["rating"];
            $company = $_POST["company"];
        }
        else {
            $first = $_POST["first"];
            $last = $_POST["last"];
            $sex = $_POST["sex"];
            $dob = $_POST["dob"];
            $dod = $_POST["dod"];
        }
        
    }

    // get what is to be added from URL
    $category = $_GET["category"];
    $plural = $category."s";
    
    // get next movie id or person id
    

    $categoryURL = "all.php?category=$category";
    echo "<a href='$categoryURL'>Back to $plural</a><br>";  
    echo "<h1>Add a new $category!</h1>";

    // display form for adding new Movie, Actor, Director
    print_form($category);

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

    // query the db to insert new review into Review table
    $query = create_query($category);
    $result = mysql_query(, $db_connection);
    if (!$result) {
        echo mysql_error($db_connection);
        mysql_close($db_connection);
        exit(1);
    }
    
    // show successful insert message or appropriate error message
   
    // close connection to MySQL server
    $closed = mysql_close($db_connection);
    if (!$closed) {
        echo mysql_error($db_connection);
        exit(1);
    }
    
?>
</body>
</html>
