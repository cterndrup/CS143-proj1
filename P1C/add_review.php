<!DOCTYPE HTML>
<html>
<head>
    <title>Colin's Movie DB</title>
    <link rel="stylesheet" style="text/css" href="universal.css">
</head>

<body>
<a href='index.php'>Back to home page</a><br><br>
<h1>Add a movie review!</h1>
<form action='review.php' method='GET'>
    <strong>Your name: </strong><input type='text' name='name' size='20'><br>
<?php
    if ($_GET["title"]) {
        $title = $_GET["title"];
        echo "<strong>Movie:</strong> <input type='text' name='title' value='$title' size='30' readonly><br>";
    }
    else {
        echo "<strong>Movie:</strong> <input type='text' name='title' size='30'><br>";
    }
?>
    <strong>Rating (1 to 5): </strong><input type='text' name='rating'><br>
    <strong>Comments: <br></strong><textarea rows='10' cols='50' name='comment'></textarea><br>
    <input type='submit' value='Submit!'>
</body>
</html>
