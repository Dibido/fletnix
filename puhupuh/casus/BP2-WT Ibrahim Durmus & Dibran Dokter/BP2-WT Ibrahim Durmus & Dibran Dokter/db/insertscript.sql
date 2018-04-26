--insertscript WTdb
use WTdb
go
--registerpagina
insert into country(country_name) values ('Nederland');
--
insert into subscription(subscription_name, subscription_price, subscription_resolution, subscription_screen_count, subscription_screen_limit)
VALUES ('Basis', 8.99, '480p', 1, 1),
('Premium', 9.99, '1080p', 2, 2),
('Pro', 11.99, 'tot 4k', 3, 3);
go
--
--Inserten films met covers en data
SET IDENTITY_INSERT WTdb.dbo.Movie ON
insert into movie(movie_id, title, duration, description, publication_year, previous_part, price, URL, cover_image, movie)
values (2000000, '2001: A Space Odyssey', 149, 'Humanity finds a mysterious, obviously artificial object buried beneath the Lunar surface and, with the intelligent computer H.A.L. 9000, sets off on a quest.
', 1968, null, 22, 'https://www.youtube.com/embed/Z2UWOeBcsJI', '2001cover.jpg', '2001.avi'),
(2000001, 'Alien', 117, 'After a space merchant vessel perceives an unknown transmission as distress call, their landing on the source moon finds one of the crew attacked by a mysterious lifeform. Continuing their journey back to Earth with the attacked crew having recovered and the critter deceased, they soon realize that its life cycle has merely begun.
', 1979, null, 25, 'https://www.youtube.com/embed/LjLamj-b0I8', 'aliencover.jpg' , 'alien.avi'),
(2000002, 'Aliens', 137, 'The moon from Alien (1979) has been colonized, but contact is lost. This time, the rescue team has impressive firepower, but will it be enough?
', 1986, null, 23, 'https://www.youtube.com/embed/W857ys3BlRI', 'alienscover.jpg', 'aliens.avi'),
(2000003, 'Blade Runner', 117, 'A blade runner must pursue and try to terminate four replicants who stole a ship in space and have returned to Earth to find their creator.
', 1982, null, 24, 'https://www.youtube.com/embed/eogpIG53Cis', 'bladerunnercover.jpg', 'aliens.avi'),
(2000004, 'Inception', 248, 'A thief, who steals corporate secrets through use of dream-sharing technology, is given the inverse task of planting an idea into the mind of a CEO.
', 2010, null, 28, 'https://www.youtube.com/embed/d3A3-zSOBT4', 'inceptioncover.jpg', 'inception.avi'),
(2000005, 'Interstellar', 169, 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity''s survival.
', 2014, null, 32, 'https://www.youtube.com/embed/zSWdZVtXT7E', 'interstellarcover.jpg', 'interstellar.avi'),
(2000006, 'The Matrix', 136, 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.
', 1999, null, 18, 'https://www.youtube.com/embed/m8e-FF8MsqU', 'matrixcover.jpg', 'matrix.avi'),
(2000007, 'Metropolis', 153, 'In a futuristic city sharply divided between the working class and the city planners, the son of the city''s mastermind falls in love with a working class prophet who predicts the coming of a savior to mediate their differences.
', 1927, null, 12, 'https://www.youtube.com/embed/ZSExdX0tds4', 'metropoliscover.jpg', 'metropolis.avi'),
(2000008, 'Eternal Sunshine of the Spotless Mind', 108, 'When their relationship turns sour, a couple undergoes a procedure to have each other erased from their memories. But it is only through the process of loss that they discover what they had to begin with.
', 2004, null, 11, 'https://www.youtube.com/embed/yE-f1alkq9I', 'sunshinecover.jpg', 'sunshine.avi'),
(2000009, 'The Prestige', 130, 'Two stage magicians engage in competitive one-upmanship in an attempt to create the ultimate stage illusion.
', 2006, null, 13, 'https://www.youtube.com/embed/o4gHCmTQDVI', 'theprestigecover.jpg', 'theprestige.avi'),
(2000010, 'The Thing', 109, 'It''s the first week of winter in 1982. An American Research Base is greeted by an alien force, that can assimilate anything it touches. It''s up to the members to stay alive, and be sure of who is human, and who has become one of the Things.
', 1982, null, 11, 'https://www.youtube.com/embed/T5NSqHohZNo', 'thethingcover.jpg' , 'thething.avi'),
(2000011, 'Twelve Monkeys', 129, 'In a future world devastated by disease, a convict is sent back in time to gather information about the man-made virus that wiped out most of the human population on the planet.
', 1995, null, 15, 'https://www.youtube.com/embed/15s4Y9ffW_o', 'twelvemonkeyscover.jpg', 'twelvemonkeys.avi');
go
SET IDENTITY_INSERT WTdb.dbo.Movie OFF
--Genres van de geiinserte movies
INSERT INTO Movie_Genre (movie_id, genre_name)
VALUES
(2000000, 'Adventure'),
(2000000, 'Mystery'),
(2000000, 'Sci-Fi'),
(2000001, 'Horror'),
(2000001, 'Sci-Fi'),
(2000002, 'Action'),
(2000002, 'Adventure'),
(2000002, 'Sci-Fi'),
(2000003, 'Sci-Fi'),
(2000003, 'Thriller'),
(2000004, 'Action'),
(2000004, 'Adventure'),
(2000004, 'Sci-Fi'),
(2000005, 'Adventure'),
(2000005, 'Drama'),
(2000005, 'Sci-Fi'),
(2000006, 'Action'),
(2000006, 'Sci-Fi'),
(2000007, 'Drama'),
(2000007, 'Sci-Fi'),
(2000008, 'Drama'),
(2000008, 'Fantasy'),
(2000008, 'Romance'),
(2000009, 'Drama'),
(2000009, 'Mystery'),
(2000009, 'Sci-Fi'),
(2000010, 'Horror'),
(2000010, 'Mystery'),
(2000010, 'Sci-Fi'),
(2000011, 'Adventure'),
(2000011, 'Drama'),
(2000011, 'Mystery');
go
--Directors
SET IDENTITY_INSERT WTdb.dbo.Person ON
insert into Person (person_id, lastname, firstname, gender)
VALUES
(2000002, 'Cameron', 'James', 'M'),
(2000004, 'Nolan', 'Cristopher', 'M'),
(2000005, 'Wachowski', 'Lana', 'F'),
(2000006, 'Wachowski', 'Lilly', 'F'),
(2000007, 'Lang', 'Fritz', 'M'),
(2000008, 'Carpenter', 'John', 'M'),
(2000009, 'Ford', 'Harrison', 'M'),
(2000010, 'Young', 'Sean', 'M'),
(2000011, 'Hathaway', 'Anna', 'F'),
(2000012, 'Chastain', 'Jessica', 'F'),
(2000013, 'Russell', 'Kurt', 'M');
go
SET IDENTITY_INSERT WTdb.dbo.Person OFF
--Movie_Directors
INSERT INTO Movie_Director(movie_id, person_id)
VALUES
(2000000, 259066),
(2000001, 427753),
(2000002, 2000002),
(2000003, 427753),
(2000004, 2000004),
(2000005, 2000004),
(2000006, 2000005),
(2000006, 2000006),
(2000007, 2000007),
(2000008, 177767),
(2000009, 2000004),
(2000010, 2000008),
(2000011, 173491);
go
--Movie_Actors
insert into movie_cast(movie_id, person_id, role)
values
(2000000, 130919, '(Unknown)'),
(2000000, 282170, '(Unknown)'),
(2000000, 462532, '(Unknown)'),
(2000001, 831289, '(Unknown)'),
(2000001, 441462, '(Unknown)'),
(2000001, 218876, '(Unknown)'),
(2000002, 831289, '(Unknown)'),
(2000002, 42278, '(Unknown)'),
(2000002, 651551, '(Unknown)'),
(2000003, 2000009, '(Unkown)'),
(2000003, 200362, '(Unkown)'),
(2000003, 2000010, '(Unkown)'),
(2000004, 121758, '(Unkown)'),
(2000004, 179605, '(Unkown)'),
(2000004, 745702, '(Unkown)'),
(2000005, 309118, '(Unkown)'),
(2000005, 2000011, '(Unkown)'),
(2000005, 2000012, '(Unkown)'),
(2000006, 393411, '(Unkown)'),
(2000006, 151786, '(Unkown)'),
(2000006, 729933, '(Unkown)'),
(2000008, 74666, '(Unkown)'),
(2000008, 837199, '(Unkown)'),
(2000009, 25103, '(Unkown)'),
(2000009, 224509, '(Unkown)'),
(2000009, 666662, '(Unkown)'),
(2000010, 2000013, '(Unkown)'),
(2000010, 57760, '(Unkown)'),
(2000011, 512541, '(Unkown)'),
(2000011, 802977, '(Unkown)'),
(2000011, 376249, '(Unkown)');
go
/* Shit verneukt de database dus ff uitgecomment
-- Testdata in de customer tabel zetten
INSERT INTO Customer (name, customer_mail_address, paypal_account, username, password, subscription_begin, subscription_end, subscription_type, birthdate, country_name, activation_key, active)
VALUES
('henkjaap', 'henkjaap@jwz.nl', 'henkjaap@jwz.nl', 'henkjaap', 'b03ddf3ca2e714a6548e7495e2a03f5e824eaac9837cd7f159c67b90fb4b7342', '2017-01-10', '2017-02-01', 'Pro', '0003-12-31', 'Nederland', '476ffde9f7b75ffc77abe2048b645efa36b3e780', 1);
go
-- Testdata in de watchhistory zetten
INSERT INTO Watchhistory(movie_id, customer_mail_address, watch_date, price, invoiced)
VALUES
(2000000, 'henkjaap@jwz.nl', '2017-01-13', 22.00, 1),
(2000003, 'henkjaap@jwz.nl', '2017-01-13', 22.00, 1),
(2000007, 'henkjaap@jwz.nl', '2017-01-13', 22.00, 1),
(2000011, 'henkjaap@jwz.nl', '2017-01-13', 22.00, 1),
(2000005, 'henkjaap@jwz.nl', '2017-01-13', 22.00, 1);
go
-- Testdata in de Favorieten tabel zetten
INSERT INTO Favorite(customer_mail_address, movie_id)
VALUES
('henkjaap@jwz.nl', 2000000),
('henkjaap@jwz.nl', 2000003),
('henkjaap@jwz.nl', 2000007),
('henkjaap@jwz.nl', 2000011),
('henkjaap@jwz.nl', 2000005);
go
*/