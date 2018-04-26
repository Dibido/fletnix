--Conversiescript WTdb voor dynamische website
SET IDENTITY_INSERT WTdb.dbo.Person ON
INSERT INTO WTdb.dbo.Person (person_id, lastname, firstname, gender)
SELECT CAST(Id AS INT) person_id,
	LEFT(Lname,50)AS lastname,
		LEFT(Fname,50)AS firstname,
		Gender AS gender
FROM MYIMDB.dbo.Imported_Person
SET IDENTITY_INSERT WTdb.dbo.Person OFF

SET IDENTITY_INSERT WTdb.dbo.Person ON
INSERT INTO WTdb.dbo.Person (person_id, lastname, firstname, gender)
SELECT CAST(Id AS INT)+1000000 AS person_id,
	LEFT(Lname,50)AS lastname,
		LEFT(Fname,50)AS firstname,
		NULL AS gender
FROM MYIMDB.dbo.Imported_Directors
SET IDENTITY_INSERT WTdb.dbo.Person OFF
--movie
SET IDENTITY_INSERT WTdb.dbo.Movie ON
INSERT INTO WTdb.dbo.Movie (movie_id, title, publication_year, price)
SELECT CAST(Id AS INT) movie_id,
		LEFT (Name,255) title,
		CAST(Year AS INT) publication_year,
		5 AS price
	FROM MYIMDB.dbo.Imported_Movie
SET IDENTITY_INSERT WTdb.dbo.Movie OFF
--genre
INSERT INTO WTdb.dbo.Genre (genre_name )
SELECT DISTINCT LEFT(Genre,255) genre_name
FROM MYIMDB.dbo.Imported_Genre
--vul child tabellen
--movie_genre
INSERT INTO WTdb.dbo.Movie_Genre (movie_id, genre_name)
SELECT DISTINCT
		CAST(MYIMDB.dbo.Imported_Genre.Id AS INT) movie_id,
		LEFT (MYIMDB.dbo.Imported_Genre.Genre,255) genre_name
	FROM MYIMDB.dbo.Imported_Genre
	WHERE Id IN (SELECT Id FROM MYIMDB.dbo.Imported_Movie)
--movie_director
INSERT INTO WTdb.dbo.Movie_Director (movie_id, person_id)
SELECT 
	CAST(Mid AS INT) movie_id,
	CAST(Did AS INT)+1000000 person_id
FROM MYIMDB.dbo.Imported_Movie_Directors
--movie_cast
INSERT INTO WTdb.dbo.Movie_Cast (movie_id, person_id, role)
SELECT DISTINCT
	CAST(Mid AS INT) movie_id,
	CAST(Pid AS INT) person_id,
	LEFT (Role, 255) role
FROM MYIMDB.dbo.Imported_Cast
WHERE Mid IN (SELECT movie_id from WTdb.dbo.Movie)