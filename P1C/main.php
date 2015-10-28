<!DOCTYPE HTML>
<html>
<head>
<title>Colin's Movie DB</title>
<!-- stylesheet -->
<link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>

<h1>Colin's Movie DB</h1>
<div class="list">
    <ul>
        <li><a href="movies.php">Movies</a></li>
        <li><a href="actors.php">Actors</a></li>
    </ul>
</div>

<!-- search box for keyword search interface -->
<div class="search">
<form action="search.php" method="GET">
    Search!<br>
    <input type="text" name="query"><br>
    <input type="submit" name="search">
</form>
</div>

</body>
</html>
