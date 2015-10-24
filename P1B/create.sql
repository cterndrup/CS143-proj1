-- Script to create tables for movie database

-- Movie table
-- constraints: 
-- (1) id is a primary key, and thus no two tuples 
-- in the Movie table may have the same id
-- (2) the check constraint ensures every movie must have a title
CREATE TABLE Movie (
    id INTEGER PRIMARY KEY,
    title VARCHAR(100),
    year INTEGER,
    rating VARCHAR(10),
    company VARCHAR(50),
    CHECK(title IS NOT NULL)
) ENGINE=INNODB;

-- Actor table
-- constraints: 
-- (1) id is a primary key, and thus no two tuples
-- in the Actor table may have the same id
-- (2) the check constraint ensures each actor must have a dob
CREATE TABLE Actor (
    id INTEGER PRIMARY KEY,
    last VARCHAR(20),
    first VARCHAR(20),
    sex VARCHAR(6),
    dob DATE,
    dod DATE,
    CHECK(dob IS NOT NULL)
) ENGINE=INNODB;

-- Director table
-- constraints: 
-- (1) id is a primary key, and thus no two tuples
-- in the Director table may have the same id
-- (2) the check constraint ensures each director must have a dob
CREATE TABLE Director(
    id INTEGER PRIMARY KEY,
    last VARCHAR(20),
    first VARCHAR(20),
    dob DATE,
    dod DATE,
    CHECK(dob IS NOT NULL)
) ENGINE=INNODB;

-- MovieGenre table
-- constraints: mid references Movie.id, and thus each mid
-- value in the MovieGenre table must reference an id value
-- in the Movie table
CREATE TABLE MovieGenre(
    mid INTEGER,
    genre VARCHAR(20),
    FOREIGN KEY(mid)
        REFERENCES Movie(id)
        ON DELETE RESTRICT 
        ON UPDATE RESTRICT
) ENGINE=INNODB;

-- MovieDirector table
-- constraints: 
-- (1) mid references Movie.id, and thus each mid
-- value in the MovieDirector table must reference an id value
-- in the Movie table
-- (2) did references Director.id, and thus each did
-- value in the MovieDirector table must reference an id value
-- in the Director table
CREATE TABLE MovieDirector(
    mid INTEGER,
    did INTEGER,
    FOREIGN KEY(mid)
        REFERENCES Movie(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    FOREIGN KEY(did)
        REFERENCES Director(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=INNODB;

-- MovieActor table
-- constraints:
-- (1) mid references Movie.id, and thus each mid
-- value in the MovieActor table must reference an id value
-- in the Director table
-- (2) aid references Actor.id, and thus each aid
-- value in the MovieActor table must reference and id value
-- in the Actor table
CREATE TABLE MovieActor(
    mid INTEGER,
    aid INTEGER,
    role VARCHAR(50),
    FOREIGN KEY(mid)
        REFERENCES Movie(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    FOREIGN KEY(aid)
        REFERENCES Actor(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=INNODB;

-- Review table
-- constraints:
-- (1) mid references Movie.id, and thus each mid
-- value in the Review table must reference an id value
-- in the Movie table
-- (2) the check constraint ensures each rating is between 1
-- and 5 stars 
CREATE TABLE Review(
    name VARCHAR(20),
    time TIMESTAMP,
    mid INTEGER,
    rating INTEGER,
    comment VARCHAR(500),
    FOREIGN KEY(mid)
        REFERENCES Movie(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    CHECK(rating >= 1 AND rating <= 5)
) ENGINE=INNODB;

-- MaxPersonID table
CREATE TABLE MaxPersonID(id INTEGER) ENGINE=INNODB;

-- MaxMovieID table
CREATE TABLE MaxMovieID(id INTEGER) ENGINE=INNODB;

