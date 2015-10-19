-- database modification to violate each constraint
-- specified in table creation (see create.sql script)
-- with description of each violation


-- primary key constraint: id is a primary key in the Movie table
-- violating command:
-- resulting output:

-- primary key constraint: id is a primary key in the Actor table
-- violating command:
-- resulting output:

-- primary key constraint: id is a primary key in the Director table
-- violating command:
-- resulting output:


-- referential integrity constraint: MovieGenre.mid references Movie.id
-- violating command:
-- resulting output:

-- referential integrity constraint: MovieDirector.mid references Movie.id
-- violating command:
-- resulting output:

-- referential integrity constraint: MovieDirector.did references Director.id
-- violating command:
-- resulting output:

-- referential integrity constraint: MovieActor.aid references Actor.id
-- violating command:
-- resulting output:

-- referential integrity constraint: Review.mid references Movie.id
-- violating command:
-- resulting output:


-- check constraint: Movie.title is not null
-- violating command:

-- check constraint: MovieActor.role is not null
-- violating command:

-- check constraint: Actor.dob is not null
-- violating command:

-- check constraint: Director.dob is not null
-- violating command:

-- check cosntraint: Review.name is not null
-- violating command:
