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
    Search!<br>
    <input type="text" name="query"><br>
    <input type="submit" value="Search">
</form>
</div>

</body>
</html>
