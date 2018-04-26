--Aanmaken en gebruiken database
CREATE DATABASE WTdb;
go
USE WTdb;
go

--Aanmaken tables
CREATE TABLE Person (
person_id INT IDENTITY(1,1) NOT NULL,
lastname Varchar (50) NOT NULL,
firstname Varchar (50) NOT NULL,
gender char (1),
CONSTRAINT pk_Person PRIMARY KEY (person_id),
CONSTRAINT ck_gender CHECK (gender = 'M' OR gender = 'F'),
);

CREATE TABLE Movie (
movie_id INT IDENTITY(1,1) NOT NULL,
title Varchar (255) NOT NULL,
duration INT,
description Varchar (1000),
publication_year INT,
cover_image varChar(255) NULL,
previous_part INT,
price numeric (5,2) NOT NULL,
URL Varchar (255),
movie Varchar (255),
CONSTRAINT pk_Movie PRIMARY KEY (movie_id),
CONSTRAINT ck_publication_year CHECK (publication_year BETWEEN 1890 AND YEAR(GETDATE())),
CONSTRAINT ck_duration CHECK (duration > 0),
CONSTRAINT ck_movie_price CHECK (price > 0),
);

CREATE TABLE Movie_Director (
movie_id INT NOT NULL,
person_id INT NOT NULL,
CONSTRAINT pk_Movie_Director PRIMARY KEY (movie_id, person_id),
);

CREATE TABLE Movie_Cast (
movie_id INT NOT NULL,
person_id INT NOT NULL,
role Varchar (255) NOT NULL DEFAULT '(Uknown)',
CONSTRAINT pk_Movie_Cast PRIMARY KEY (role, movie_id, person_id),
);

CREATE TABLE Genre (
genre_name Varchar (255) NOT NULL,
description Varchar (255),
CONSTRAINT pk_Genre PRIMARY KEY (genre_name),
);

CREATE TABLE Movie_Genre (
movie_id INT NOT NULL,
genre_name Varchar (255) NOT NULL,
CONSTRAINT pk_movie_id_movie_genre PRIMARY KEY (movie_id, genre_name),
);

CREATE TABLE Country (
country_name Varchar(50) NOT NULL,
CONSTRAINT pk_country_name PRIMARY KEY (country_name),
);

CREATE TABLE Customer (
name Varchar(255) NOT NULL,
customer_mail_address Varchar(255) NOT NULL,
paypal_account Varchar(255) NOT NULL UNIQUE,
username Varchar (255) NOT NULL UNIQUE,
password Varchar (64) NOT NULL,
subscription_type Varchar (255) NOT NULL,
subscription_begin Date NOT NULL,
subscription_end Date NULL,
birthdate Date NOT NULL,
country_name Varchar(50) NOT NULL,
activation_key varChar(64) NOT NULL UNIQUE,
active bit not null default 0,
CONSTRAINT pk_Customer PRIMARY KEY (customer_mail_address),
CONSTRAINT ck_customer_mail_address CHECK (customer_mail_address like '%@%.%'),
CONSTRAINT ck_customer_paypal_address CHECK (paypal_account like '%@%.%'),
--CONSTRAINT ck_customer_subscription_type CHECK (subscription_type in ('Basis', 'Premium', 'Pro')),
CONSTRAINT ck_subscription_begin CHECK (subscription_begin < subscription_end),
CONSTRAINT ck_password CHECK (LEN(password) >= 8),

);

CREATE TABLE Subscription(
subscription_name varChar(255) NOT NULL,
subscription_price numeric(4,2) NOT NULL,
subscription_resolution varChar(50) NOT NULL,
subscription_screen_count int NOT NULL,
subscription_screen_limit int NOT NULL,
CONSTRAINT pk_Subscriptions PRIMARY KEY (subscription_name),
);

CREATE TABLE Watchhistory (
movie_id INT NOT NULL,
customer_mail_address Varchar (255) NOT NULL,
watch_date Datetime NOT NULL,
price numeric (5,2) NOT NULL,
invoiced bit NOT NULL,
CONSTRAINT pk_Watchhistory PRIMARY KEY (movie_id, customer_mail_address, watch_date),
CONSTRAINT ck_history_price CHECK (price > 0),
);

CREATE TABLE Favorite (
customer_mail_address Varchar(255) NOT NULL,
movie_id INT NOT NULL,
CONSTRAINT pk_favorites PRIMARY KEY (customer_mail_address, movie_id),
);

--Foreign keys toevoegen aan de tables

ALTER TABLE Movie ADD
CONSTRAINT fk_Movie_previous_part FOREIGN KEY (previous_part) REFERENCES Movie (movie_id);

ALTER TABLE Movie_Director ADD
CONSTRAINT fk_Movie_Director_person_id FOREIGN KEY (person_id) REFERENCES Person (person_id) 
	ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_Movie_Director_movie_id FOREIGN KEY (movie_id) REFERENCES Movie (movie_id) 
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Movie_Cast ADD
CONSTRAINT fk_Movie_Cast_movie_id FOREIGN KEY (movie_id) REFERENCES Movie (movie_id)
	ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_Movie_Cast_person_id FOREIGN KEY (person_id) REFERENCES Person (person_id)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Movie_Genre ADD
CONSTRAINT fk_Movie_Genre_movie_id FOREIGN KEY (movie_id) REFERENCES Movie (movie_id)
	ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_Movie_Genre_genre_name FOREIGN KEY (genre_name) REFERENCES Genre (genre_name)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Customer ADD
CONSTRAINT fk_Country_country_name FOREIGN KEY (country_name) REFERENCES Country (country_name),
CONSTRAINT fk_Subscription_subscription_name FOREIGN KEY (subscription_type) REFERENCES Subscription(subscription_name)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Watchhistory ADD
CONSTRAINT fk_Watchhistory_customer_mail_address FOREIGN KEY (customer_mail_address) REFERENCES Customer (customer_mail_address)
	ON DELETE NO ACTION ON UPDATE CASCADE,
CONSTRAINT fk_Watchhistory_movie_id FOREIGN KEY (movie_id) REFERENCES Movie (movie_id)
	ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE Favorite ADD
CONSTRAINT fk_Favorite_customer_mail_address FOREIGN KEY (customer_mail_address) REFERENCES Customer (customer_mail_address)
	ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_Favorite_movie_id FOREIGN KEY (movie_id) REFERENCES Movie (movie_id)
	ON DELETE NO ACTION ON UPDATE CASCADE;
--functie om te checken of de watchdate binnen de subscriptie valt
GO
CREATE FUNCTION dbo.fn_WatchdateBinnenSubscription(@customer varChar(255), @watchdate date)
RETURNS BIT
BEGIN
IF (@Watchdate BETWEEN (SELECT subscription_begin
						FROM Customer c 
						WHERE c.customer_mail_address = @customer)
						AND
						(SELECT subscription_end
						FROM Customer c 
						WHERE c.customer_mail_address = @customer))
						RETURN 1
RETURN 0
END
GO

--functie implementeren
ALTER TABLE Watchhistory WITH CHECK
ADD
CONSTRAINT ck_Watchhistory_WatchdatebinnenSubscription CHECK (dbo.fn_WatchdateBinnenSubscription(customer_mail_address, watch_date) = 1);