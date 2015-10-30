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

    // get what is to be added from URL
    $category = $_GET["category"];
    $plural = $category."s";

    $categoryURL = "all.php?category=$category";
    echo "<a href='$categoryURL'>Back to $plural</a><br>";  
    echo "<h1>Add a new $category!</h1>";

    // display form for adding new Movie, Actor, Director
    print_form($category);
    
?>
</body>
</html>
