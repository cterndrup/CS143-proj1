<!-- main page -->
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
        <li><a href="all.php?category=Movie">Movies</a></li>
        <li><a href="all.php?category=Actor">Actors</a></li>
        <li><a href="all.php?category=Director">Directors</a></li>
    </ul>
</div>

<!-- search box for keyword search interface -->
<div class="search">
<form action="search.php" method="GET">
    Search for a movie, actor, or director!<br>
    <input type="text" name="search"><br>
    <input type="submit" value="Search"><br>
    <input type="hidden" name="origin" value="main">
</form>
</div>

</body>
</html>
