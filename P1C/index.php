<!-- index page -->
<!DOCTYPE HTML>
<html>
<head>
<title>Colin's Movie DB</title>
<!-- stylesheet -->
<link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>

<h1>Colin's Movie Database</h1>
<h4>Click one of the links below to see all Movies, Actors, or Directors!</h4>
<div class="hlist">
    <ul>
        <li><a href="all.php?category=Movie">Movies</a></li>
        <li><a href="all.php?category=Actor">Actors</a></li>
        <li><a href="all.php?category=Director">Directors</a></li>
    </ul>
</div>

<!-- search box for keyword search interface -->
<div class="search">
<form action="search.php" method="GET">
    <strong>Search for a movie or an actor!</strong><br>
    <input type="text" name="search"><br>
    <input type="submit" value="Search"><br>
    <input type="hidden" name="origin" value="index">
</form>
</div>

<h3>More</h3>
<div class="vlist">
    <ul>
        <li><a href="add.php?category=Movie">Add Movie</a></li>
        <li><a href="add.php?category=Actor">Add Actor</a></li>
        <li><a href="add.php?category=Director">Add Director</a></li>
        <li><a href="add_to_movie.php?from=index&person_type=actor">Add Actor to Movie</a></li>
        <li><a href="add_to_movie.php?from=index&person_type=director">Add Director to Movie</a></li>
        <li><a href="add_review.php">Add Review to Movie</a></li>
    </ul>
</div>

</body>
</html>
