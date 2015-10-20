-- database modification to violate each constraint
-- specified in table creation (see create.sql script)
-- with description of each violation

-- --------------------PRIMARY KEY CONSTRAINTS-------------------
-- primary key constraint: id is a primary key in the Movie table
-- violating command:
INSERT INTO Movie VALUES(2, "Harry Potter", 2001, "PG", NULL);
-- resulting output: ERROR 1062 (23000) at line 8: Duplicate entry '2' 
-- for key 'PRIMARY' 
-- why its a violation: there is already a movie in the Movie table with 
-- this movie ID and because id is a primary key, there may only
-- be one movie in the Movie table with a given id number

-- primary key constraint: id is a primary key in the Actor table
-- violating command:
INSERT INTO Actor VALUES(10, "Colin", "Terndrup", "Male", "1982-01-01", NULL);
-- resulting output: ERROR 1062 (23000) at line 17: Duplicate entry '10'
-- for key 'PRIMARY'
-- why its a violation: there is already an actor in the Actor table with
-- this actor id and because id is a primary key, there may only be one
-- actor in the Actor table with a given id number

-- primary key constraint: id is a primary key in the Director table
-- violating command:
INSERT INTO Director VALUES(16, "Colin", "Terndrup", "1982-01-01", NULL);
-- resulting output: ERROR 1062 (23000) at line 26: Duplicate entry '16'
-- for key 'PRIMARY'
-- why its a violation: there is already a director in the Director table
-- with this director id and because id is a primary key, there may only be
-- one director in the director table with a given id number



-- --------------------REFERENTIAL INTEGRITY CONSTRAINTS-------------------
-- referential integrity constraint: MovieGenre.mid references Movie.id
-- violating command:
UPDATE MovieGenre SET mid=1000000 WHERE mid=2;
-- resulting output:
-- ERROR 1452 (23000) at line 38: Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieGenre`, CONSTRAINT `MovieGenre_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
-- why its a violation: The movie with id=2 cannot be updated to a value
-- that does not appear in the id column of the Movie table because
-- MovieGenre.mid references Movie.id. In this case, it is known
-- 1000000 is much higher than the number of movies in the database, so
-- attempting to set a movie id to a number that can't possibly be an
-- id in the Movie table will cause a referential integrity violation

-- referential integrity constraint: MovieDirector.mid references Movie.id
-- violating command:
INSERT INTO MovieDirector VALUES(-10, 100);
-- resulting output:
-- ERROR 1452 (23000) at line 50: Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
-- why its a violation: The mid value of any MovieDirector tuple must belong
-- to the set of id values in the Movie table. Because id values in the Movie
-- table are all greater than 0, attempting to insert a tuple into the 
-- MovieDirector table with a value less than 0 will cause a referential
-- integrity violation.

-- referential integrity constraint: MovieDirector.did references Director.id
-- violating command:
INSERT INTO MovieDirector VALUES(10, -5);
-- resulting output:
-- ERROR 1452 (23000) at line 61: Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
-- why its a violation: The did value of any MovieDirector tuple must belong
-- to the set of id values in the Director table. Because id values in the 
-- Director table are all greater than 0, attempting to insert a tuple into the 
-- MovieDirector table with a value less than 0 will cause a referential
-- integrity violation.

-- referential integrity constraint: MovieActor.mid references Movie.id
-- violating command: 
UPDATE MovieActor SET mid=1000000 WHERE aid=8568;
-- resulting output:
-- ERROR 1452 (23000) at line 72: Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
-- why its a violation: The mid value of any MovieActor typle must belong
-- to the set of id values in the Movie table. Because it is known there are 
-- far fewer than 1000000 movies in the database, then it is also known there 
-- is not a movie in the Movie table with id=1000000, which causes a referential
-- violation to occur when attempting to set the mid to 1000000 for aid=8568
-- in the MovieActor table.

-- referential integrity constraint: MovieActor.aid references Actor.id
-- violating command:
DELETE FROM Actor WHERE id=8568;
-- resulting output:
-- ERROR 1451 (23000) at line 84: Cannot delete or update a parent row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `Actor` (`id`))
-- why its a violation: The aid value of any MovieActor tuple must belong 
-- to the set of id values in the Actor table. If a tuple with id=8568 
-- is deleted from the Actor table, then there exists at least one tuple
-- in the MovieActor table with aid=8568, which is no longer in the set
-- of id values in the Actor table, causing a referential integrity violation.

-- referential integrity constraint: Review.mid references Movie.id
-- violating command:
INSERT INTO Review VALUES("Colin Terndrup", "2015-10-20 12:02:02", 1000000, 4, "Absolutely terrible.");
-- resulting output:
-- ERROR 1452 (23000) at line 95: Cannot add or update a child row: a foreign key constraint fails (`CS143`.`Review`, CONSTRAINT `Review_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
-- why its a violation: The mid value of any Review tuple must belong to
-- the set of id values in the Movie table. Since it is known there are 
-- less than 1000000 movies in the database, no movie in the Movie table
-- has id 1000000 and an mid value is attempting to be entered that does
-- not belong to the set of id values in the Movie table, causing a
-- referential integrity violation.
-- resulting output:



-- ---------------------CHECK CONSTRAINTS-------------------
-- check constraint: Movie.title is not null
-- violating command:
-- INSERT INTO Movie(4750, NULL, 2009, 4, NULL);
-- why its a violation: The above command attempts to insert a new movie
-- into the Movie table with a NULL value for the title, which causes a 
-- violation because the check constraint specifies each movie must
-- have a title.

-- check constraint: Actor.dob is not null
-- violating command:
-- UPDATE Actor SET dob=NULL WHERE id=8568;
-- why its a violation: The above command attempts to set the dob of 
-- the actor with id=8568 to NULL, which causes a violation because the 
-- check constraint specifies each actor must have a dob.

-- check constraint: Director.dob is not null
-- violating command:
-- UPDATE Director SET dob=NULL WHERE id=16;
-- why its a violation: The above command attempts to set the dob of 
-- the director with id=16 to NULL, which causes a violation because the 
-- check constraint specifies each director must have a dob.

-- check constraint: Review.rating is between 1 and 5
-- violating command: 
-- INSERT INTO Review VALUES("Colin Terndrup", "2015-10-20 12:24:05", 10, -1, "Great film.");
-- why its a violation: The above command attempts to insert a new review
-- into the Review table with a rating of -1, which causes a violation
-- because the check constraint specifies each rating must be a value 
-- between 1 and 5, inclusive.
