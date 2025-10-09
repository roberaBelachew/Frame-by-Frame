-- user and profile
CREATE TABLE Users
(
id INT PRIMARY KEY AUTO_INCREMENT,
email VARCHAR(100) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL
);
CREATE TABLE ProfileInfo
(
id INT PRIMARY KEY AUTO_INCREMENT,
userID INT UNIQUE,
joinDate DATE NOT NULL,
username VARCHAR(50) UNIQUE NOT NULL,
FOREIGN KEY (userID) REFERENCES Users(id)
);
-- movies
CREATE TABLE Movies
(
id INT PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(200) NOT NULL,
releaseYear INT NOT NULL,
description TEXT
);
-- watchlist
CREATE TABLE Watchlist
(
id INT PRIMARY KEY AUTO_INCREMENT,
userID INT NOT NULL,
movieID INT NOT NULL,
status VARCHAR(50),
FOREIGN KEY (userID) REFERENCES Users(id),
FOREIGN KEY (movieID) REFERENCES Movies(id)
);
-- reviews
CREATE TABLE Reviews
(
id INT PRIMARY KEY AUTO_INCREMENT,
userID INT NOT NULL,
movieID INT NOT NULL,
rating DECIMAL(2,1) CHECK (rating >= 0 AND rating <= 10),
flags INT DEFAULT 0,
FOREIGN KEY (userID) REFERENCES Users(id),
FOREIGN KEY (movieID) REFERENCES Movies(id)
);
-- user interactions (likes, comments)
CREATE TABLE Comments
(
id INT PRIMARY KEY AUTO_INCREMENT,
userID INT NOT NULL,
reviewID INT NOT NULL,
content TEXT NOT NULL,
createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (userID) REFERENCES Users(id),
FOREIGN KEY (reviewID) REFERENCES Reviews(id)
);
CREATE TABLE Likes
(
userID INT,
reviewID INT,
PRIMARY KEY (userID, reviewID),
FOREIGN KEY (userID) REFERENCES Users(id),
FOREIGN KEY (reviewID) REFERENCES Reviews(id)
);
-- age rating
CREATE TABLE AgeRating
(
id INT PRIMARY KEY AUTO_INCREMENT,
label ENUM('G','PG','PG-13','R','NC-17') NOT NULL
);
CREATE TABLE MovieAgeRating
(
movieID INT,
ratingID INT,
PRIMARY KEY (movieID, ratingID),
FOREIGN KEY (movieID) REFERENCES Movies(id),
FOREIGN KEY (ratingID) REFERENCES AgeRating(id)
);
-- tags (genre, cast, director)
CREATE TABLE Tags
(
id INT PRIMARY KEY AUTO_INCREMENT,
type ENUM('Genre','Cast','Director') NOT NULL,
value VARCHAR(100) NOT NULL
);
CREATE TABLE MovieTags
(
movieID INT,
tagID INT,
PRIMARY KEY (movieID, tagID),
FOREIGN KEY (movieID) REFERENCES Movies(id),
FOREIGN KEY (tagID) REFERENCES Tags(id)
);
-- categories (feature, short, blockbuster)
CREATE TABLE Categories
(
id INT PRIMARY KEY AUTO_INCREMENT,
type ENUM('Feature','Short','Blockbuster') NOT NULL
);
CREATE TABLE MovieCategories
(
movieID INT,
categoryID INT,
PRIMARY KEY (movieID, categoryID),
FOREIGN KEY (movieID) REFERENCES Movies(id),
FOREIGN KEY (categoryID) REFERENCES Categories(id)
);
-- Sources (streaming, buy, rent)
CREATE TABLE Sources
(
id INT PRIMARY KEY AUTO_INCREMENT,
type ENUM('Streaming Service','Buy','Rent') NOT NULL
);
CREATE TABLE MovieSources
(
movieID INT,
sourceID INT,
PRIMARY KEY (movieID, sourceID),
FOREIGN KEY (movieID) REFERENCES Movies(id),
FOREIGN KEY (sourceID) REFERENCES Sources(id)
);