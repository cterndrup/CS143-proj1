-- Script to create tables for movie database

-- Movie table
-- constraints: id is a primary key, and thus no two tuples 
-- in the Movie table may have the same id
CREATE TABLE Movie (
    id INTEGER PRIMARY KEY,
    title VARCHAR(100),
    year INTEGER,
    rating VARCHAR(10),
    company VARCHAR(50));

-- Actor table
-- constraints: id is a primary key, and thus no two tuples
-- in the Actor table may have the same id
CREATE TABLE Actor (
    id INTEGER PRIMARY KEY,
    last VARCHAR(20),
    first VARCHAR(20),
    sex VARCHAR(6),
    dob DATE,
    dod DATE);

-- Director table
-- constraints: id is a primary key, and thus no two tuples
-- in the Director table may have the same id
CREATE TABLE Director(
    id INTEGER PRIMARY KEY,
    last VARCHAR(20),
    first VARCHAR(20),
    dob DATE,
    dod DATE);

-- MovieGenre table
-- constraints: mid references Movie.id, and thus each mid
-- value in the MovieGenre table must reference an id value
-- in the Movie table
CREATE TABLE MovieGenre(
    mid INTEGER REFERENCES Movie(id),
    genre VARCHAR(20));

-- MovieDirector table
-- constraints: 
-- (1) mid references Movie.id, and thus each mid
-- value in the MovieDirector table must reference an id value
-- in the Movie table
-- (2) did references Director.id, and thus each did
-- value in the MovieDirector table must reference an id value
-- in the Director table
CREATE TABLE MovieDirector(
    mid INTEGER REFERENCES Movie(id),
    did INTEGER REFERENCES Director(id));

-- MovieActor table
-- constraints:
-- (1) mid references Movie.id, and thus each mid
-- value in the MovieActor table must reference an id value
-- in the Director table
-- (2) aid references Actor.id, and thus each aid
-- value in the MovieActor table must reference and id value
-- in the Actor table
CREATE TABLE MovieActor(
    mid INTEGER REFERENCES Movie(id),
    aid INTEGER REFERENCES Actor(id),
    role VARCHAR(50));

-- Review table
-- constraints:
-- mid references Movie.id, and thus each mid
-- value in the Review table must reference an id value
-- in the Movie table
CREATE TABLE Review(
    name VARCHAR(20),
    time TIMESTAMP,
    mid INTEGER REFERENCES Movie(id),
    rating INTEGER,
    comment VARCHAR(500));

-- MaxPersonID table
CREATE TABLE MaxPersonID(id INTEGER);

-- MaxMovieID table
CREATE TABLE MaxMovieID(id INTEGER);

