-- sql script to run three queries on CS143 movie db

-- find names of all the actors in 'Die Another Day'
-- output format: <firstname> <lastname>
SELECT CONCAT_WS(' ', first, last)
FROM Actor, MovieActor
WHERE Actor.id=MovieActor.aid AND MovieActor.mid=(
    SELECT id
    FROM Movie
    WHERE title='Die Another Day'
);

-- find the count of all actors who acted in multiple movies
SELECT COUNT(*)
FROM (
    SELECT aid
    FROM MovieActor
    GROUP BY aid
    HAVING COUNT(mid) >=2
) AS MultiActors;

-- find the titles of all the PG-13 and R rated Sci-Fi movies
-- display the titles with their ratings
SELECT title, rating
FROM Movie, MovieGenre
WHERE Movie.id=MovieGenre.mid AND genre='Sci-Fi'
AND (rating='PG-13' OR rating='R');
