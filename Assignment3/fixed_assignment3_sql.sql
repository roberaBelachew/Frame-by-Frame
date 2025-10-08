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

#SAMPLE DATA

-- Insert Users (10 users)
INSERT INTO Users (email, password) VALUES
('john.doe@email.com', 'hashed_password_1'),
('jane.smith@email.com', 'hashed_password_2'),
('mike.wilson@email.com', 'hashed_password_3'),
('sarah.johnson@email.com', 'hashed_password_4'),
('david.brown@email.com', 'hashed_password_5'),
('emma.davis@email.com', 'hashed_password_6'),
('chris.miller@email.com', 'hashed_password_7'),
('lisa.garcia@email.com', 'hashed_password_8'),
('tom.anderson@email.com', 'hashed_password_9'),
('amy.taylor@email.com', 'hashed_password_10');

-- Insert ProfileInfo (10 profiles)
INSERT INTO ProfileInfo (userID, joinDate, username) VALUES
(1, '2023-01-15', 'MovieBuff_John'),
(2, '2023-02-20', 'CinemaQueen_Jane'),
(3, '2023-03-10', 'FilmFanatic_Mike'),
(4, '2023-04-05', 'ReelTalk_Sarah'),
(5, '2023-05-12', 'ScreenGeek_David'),
(6, '2023-06-18', 'FlickLover_Emma'),
(7, '2023-07-22', 'MovieMaven_Chris'),
(8, '2023-08-30', 'CinephileLisa'),
(9, '2023-09-14', 'FilmCritic_Tom'),
(10, '2023-10-25', 'PopcornAmy');

-- Insert Movies (15 movies)
INSERT INTO Movies (title, releaseYear, description) VALUES
('The Shawshank Redemption', 1994, 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.'),
('The Dark Knight', 2008, 'When the menace known as the Joker wreaks havoc on Gotham, Batman must accept one of the greatest psychological tests.'),
('Inception', 2010, 'A thief who steals corporate secrets through dream-sharing technology is given the inverse task of planting an idea.'),
('Pulp Fiction', 1994, 'The lives of two mob hitmen, a boxer, and a pair of diner bandits intertwine in four tales of violence and redemption.'),
('The Matrix', 1999, 'A computer hacker learns about the true nature of his reality and his role in the war against its controllers.'),
('Forrest Gump', 1994, 'The presidencies of Kennedy and Johnson unfold through the perspective of an Alabama man with an IQ of 75.'),
('Interstellar', 2014, 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity survival.'),
('The Godfather', 1972, 'The aging patriarch of an organized crime dynasty transfers control to his reluctant son.'),
('Parasite', 2019, 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.'),
('The Avengers', 2012, 'Earth mightiest heroes must come together to stop Loki and his alien army from enslaving humanity.'),
('Mean Girls', 2004, 'Cady Heron is a hit with The Plastics at her new school, until she makes the mistake of falling for Aaron Samuels.'),
('La La Land', 2016, 'While navigating their careers in Los Angeles, a pianist and an actress fall in love while attempting to reconcile their aspirations.'),
('Get Out', 2017, 'A young African-American visits his white girlfriend family estate where he becomes ensnared in a disturbing reality.'),
('Toy Story', 1995, 'A cowboy doll is profoundly threatened when a new spaceman figure supplants him as top toy in a boy room.'),
('Knives Out', 2019, 'A detective investigates the death of a patriarch of an eccentric, combative family.');

-- Insert Age Ratings
INSERT INTO AgeRating (label) VALUES
('G'),
('PG'),
('PG-13'),
('R'),
('NC-17');

-- Insert Movie Age Ratings
INSERT INTO MovieAgeRating (movieID, ratingID) VALUES
(1, 4), -- Shawshank - R
(2, 3), -- Dark Knight - PG-13
(3, 3), -- Inception - PG-13
(4, 4), -- Pulp Fiction - R
(5, 4), -- Matrix - R
(6, 3), -- Forrest Gump - PG-13
(7, 3), -- Interstellar - PG-13
(8, 4), -- Godfather - R
(9, 4), -- Parasite - R
(10, 3), -- Avengers - PG-13
(11, 3), -- Mean Girls - PG-13
(12, 3), -- La La Land - PG-13
(13, 4), -- Get Out - R
(14, 1), -- Toy Story - G
(15, 3); -- Knives Out - PG-13

-- Insert Tags (Genres, Cast, Directors)
INSERT INTO Tags (type, value) VALUES
-- Genres
('Genre', 'Drama'),
('Genre', 'Action'),
('Genre', 'Sci-Fi'),
('Genre', 'Crime'),
('Genre', 'Thriller'),
('Genre', 'Comedy'),
('Genre', 'Romance'),
('Genre', 'Horror'),
('Genre', 'Animation'),
('Genre', 'Mystery'),
-- Directors
('Director', 'Frank Darabont'),
('Director', 'Christopher Nolan'),
('Director', 'Quentin Tarantino'),
('Director', 'Lana Wachowski'),
('Director', 'Robert Zemeckis'),
('Director', 'Francis Ford Coppola'),
('Director', 'Bong Joon-ho'),
('Director', 'Joss Whedon'),
('Director', 'Mark Waters'),
('Director', 'Damien Chazelle'),
('Director', 'Jordan Peele'),
('Director', 'John Lasseter'),
('Director', 'Rian Johnson'),
-- Cast
('Cast', 'Tim Robbins'),
('Cast', 'Morgan Freeman'),
('Cast', 'Christian Bale'),
('Cast', 'Heath Ledger'),
('Cast', 'Leonardo DiCaprio'),
('Cast', 'John Travolta'),
('Cast', 'Keanu Reeves'),
('Cast', 'Tom Hanks'),
('Cast', 'Matthew McConaughey'),
('Cast', 'Marlon Brando'),
('Cast', 'Al Pacino'),
('Cast', 'Song Kang-ho'),
('Cast', 'Robert Downey Jr.'),
('Cast', 'Chris Evans'),
('Cast', 'Lindsay Lohan'),
('Cast', 'Rachel McAdams'),
('Cast', 'Ryan Gosling'),
('Cast', 'Emma Stone'),
('Cast', 'Daniel Kaluuya'),
('Cast', 'Tom Hanks'),
('Cast', 'Daniel Craig'),
('Cast', 'Ana de Armas');

-- Insert Movie Tags
INSERT INTO MovieTags (movieID, tagID) VALUES
-- Shawshank Redemption
(1, 1), (1, 11), (1, 24), (1, 25),
-- Dark Knight
(2, 2), (2, 4), (2, 12), (2, 26), (2, 27),
-- Inception
(3, 2), (3, 3), (3, 5), (3, 12), (3, 28),
-- Pulp Fiction
(4, 4), (4, 13), (4, 29),
-- Matrix
(5, 2), (5, 3), (5, 14), (5, 30),
-- Forrest Gump
(6, 1), (6, 7), (6, 15), (6, 31),
-- Interstellar
(7, 1), (7, 3), (7, 12), (7, 32),
-- Godfather
(8, 1), (8, 4), (8, 16), (8, 33), (8, 34),
-- Parasite
(9, 1), (9, 5), (9, 17), (9, 35),
-- Avengers
(10, 2), (10, 3), (10, 18), (10, 36), (10, 37),
-- Mean Girls
(11, 6), (11, 19), (11, 38), (11, 39),
-- La La Land
(12, 1), (12, 7), (12, 20), (12, 40), (12, 41),
-- Get Out
(13, 5), (13, 8), (13, 21), (13, 42),
-- Toy Story
(14, 6), (14, 9), (14, 22), (14, 43),
-- Knives Out
(15, 4), (15, 10), (15, 23), (15, 44), (15, 45);

-- Insert Categories
INSERT INTO Categories (type) VALUES
('Feature'),
('Short'),
('Blockbuster');

-- Insert Movie Categories
INSERT INTO MovieCategories (movieID, categoryID) VALUES
(1, 1), -- Shawshank - Feature
(2, 1), (2, 3), -- Dark Knight - Feature, Blockbuster
(3, 1), (3, 3), -- Inception - Feature, Blockbuster
(4, 1), -- Pulp Fiction - Feature
(5, 1), (5, 3), -- Matrix - Feature, Blockbuster
(6, 1), (6, 3), -- Forrest Gump - Feature, Blockbuster
(7, 1), (7, 3), -- Interstellar - Feature, Blockbuster
(8, 1), -- Godfather - Feature
(9, 1), -- Parasite - Feature
(10, 1), (10, 3), -- Avengers - Feature, Blockbuster
(11, 1), -- Mean Girls - Feature
(12, 1), -- La La Land - Feature
(13, 1), -- Get Out - Feature
(14, 1), (14, 3), -- Toy Story - Feature, Blockbuster
(15, 1); -- Knives Out - Feature

-- Insert Sources
INSERT INTO Sources (type) VALUES
('Streaming Service'),
('Buy'),
('Rent');

-- Insert Movie Sources
INSERT INTO MovieSources (movieID, sourceID) VALUES
-- Most movies available on all platforms
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 2), (2, 3),
(3, 1), (3, 2), (3, 3),
(4, 1), (4, 2), (4, 3),
(5, 1), (5, 2), (5, 3),
(6, 1), (6, 2), (6, 3),
(7, 1), (7, 2), (7, 3),
(8, 1), (8, 2), (8, 3),
(9, 1), (9, 2), (9, 3),
(10, 1), (10, 2), (10, 3),
(11, 1), (11, 2), (11, 3),
(12, 1), (12, 2), (12, 3),
(13, 1), (13, 2), (13, 3),
(14, 1), (14, 2), (14, 3),
(15, 1), (15, 2), (15, 3);

-- Insert Watchlist entries (various users, various movies, different statuses)
INSERT INTO Watchlist (userID, movieID, status) VALUES
(1, 1, 'Completed'),
(1, 2, 'Watching'),
(1, 3, 'Plan to Watch'),
(2, 1, 'Completed'),
(2, 4, 'Completed'),
(2, 11, 'Completed'),
(3, 2, 'Completed'),
(3, 5, 'Watching'),
(3, 10, 'Completed'),
(4, 3, 'Completed'),
(4, 7, 'Plan to Watch'),
(5, 6, 'Completed'),
(5, 8, 'Completed'),
(6, 9, 'Completed'),
(6, 12, 'Completed'),
(7, 13, 'Completed'),
(7, 14, 'Completed'),
(8, 15, 'Watching'),
(9, 1, 'Completed'),
(9, 2, 'Completed'),
(10, 11, 'Plan to Watch');

-- Insert Reviews (50+ reviews with varied ratings)
INSERT INTO Reviews (userID, movieID, rating, flags) VALUES
-- Shawshank Redemption (highly rated)
(1, 1, 9.5, 0),
(2, 1, 10.0, 0),
(9, 1, 9.8, 0),
(4, 1, 9.0, 0),
-- Dark Knight (highly rated)
(1, 2, 9.0, 0),
(3, 2, 9.5, 0),
(9, 2, 9.2, 0),
(5, 2, 8.8, 0),
-- Inception (highly rated)
(4, 3, 9.0, 0),
(6, 3, 8.5, 0),
(7, 3, 9.3, 0),
-- Pulp Fiction (highly rated)
(2, 4, 9.0, 0),
(8, 4, 8.7, 0),
(5, 4, 9.5, 0),
-- Matrix (highly rated)
(3, 5, 8.8, 0),
(7, 5, 9.0, 0),
(9, 5, 8.5, 0),
-- Forrest Gump (highly rated)
(5, 6, 9.0, 0),
(10, 6, 8.8, 0),
(1, 6, 9.2, 0),
-- Interstellar (highly rated)
(4, 7, 8.5, 0),
(8, 7, 9.0, 0),
-- Godfather (highly rated)
(5, 8, 10.0, 0),
(6, 8, 9.5, 0),
(2, 8, 9.8, 0),
-- Parasite (highly rated)
(6, 9, 9.0, 0),
(3, 9, 8.8, 0),
-- Avengers (good ratings)
(3, 10, 8.0, 0),
(7, 10, 7.5, 0),
(9, 10, 8.2, 0),
-- Mean Girls (good ratings)
(2, 11, 8.5, 0),
(10, 11, 8.0, 0),
(4, 11, 7.8, 0),
-- La La Land (mixed ratings)
(6, 12, 8.5, 0),
(9, 12, 7.0, 0),
(1, 12, 8.0, 0),
-- Get Out (good ratings)
(7, 13, 8.8, 0),
(2, 13, 9.0, 0),
-- Toy Story (highly rated)
(7, 14, 9.5, 0),
(8, 14, 9.0, 0),
(10, 14, 9.3, 0),
-- Knives Out (good ratings)
(8, 15, 8.5, 0),
(5, 15, 8.0, 0),
-- Additional reviews from active users
(1, 5, 8.7, 0),
(1, 8, 9.5, 0),
(2, 3, 9.0, 0),
(2, 10, 7.8, 0),
(3, 6, 8.9, 0),
(9, 13, 8.5, 1); -- One flagged review

-- Insert Comments (30+ comments)
INSERT INTO Comments (userID, reviewID, content) VALUES
(2, 1, 'Totally agree! One of the best movies ever made.'),
(3, 1, 'The ending still gives me goosebumps.'),
(4, 2, 'Perfect score well deserved!'),
(5, 5, 'Heath Ledger performance was legendary.'),
(6, 6, 'Christopher Nolan at his finest.'),
(7, 9, 'The dream sequences were mind-blowing.'),
(8, 10, 'Such clever visual effects!'),
(1, 12, 'Tarantino is a genius storyteller.'),
(9, 14, 'The dialogue in this film is iconic.'),
(10, 18, 'Tom Hanks delivers an Oscar-worthy performance.'),
(1, 20, 'Cried at the ending, so emotional.'),
(2, 23, 'The ultimate crime drama.'),
(3, 24, 'Marlon Brando at his absolute best.'),
(4, 26, 'The social commentary is brilliant.'),
(5, 27, 'This movie deserved all its awards.'),
(6, 30, 'Marvel really nailed the team dynamics.'),
(7, 31, 'Fun popcorn entertainment!'),
(8, 33, 'So quotable! Love this movie.'),
(9, 34, '"That so fetch!" - Classic line!'),
(10, 36, 'The music and dancing were spectacular.'),
(1, 39, 'Jordan Peele is a master of horror.'),
(2, 40, 'Kept me on the edge of my seat.'),
(3, 41, 'Childhood favorite! Never gets old.'),
(4, 42, 'Pixar set the standard with this one.'),
(5, 45, 'The twist was unexpected!'),
(6, 46, 'Brilliant whodunit mystery.'),
(7, 5, 'The Joker redefined comic book villains.'),
(8, 23, 'A masterclass in filmmaking.'),
(9, 18, 'One of my all-time favorites.'),
(10, 9, 'Need to watch this again to catch all the details.');

-- Insert Likes (50+ likes across various reviews)
INSERT INTO Likes (userID, reviewID) VALUES
(2, 1), (3, 1), (4, 1), (5, 1), (6, 1),
(1, 2), (3, 2), (7, 2), (8, 2),
(1, 5), (2, 5), (4, 5), (6, 5), (7, 5),
(2, 6), (5, 6), (8, 6),
(1, 9), (2, 9), (5, 9), (8, 9),
(3, 12), (4, 12), (6, 12), (9, 12),
(1, 14), (7, 14), (10, 14),
(2, 18), (3, 18), (8, 18), (9, 18),
(4, 23), (5, 23), (7, 23), (10, 23),
(1, 24), (6, 24), (8, 24),
(2, 26), (4, 26), (7, 26), (9, 26),
(3, 30), (5, 30), (6, 30),
(4, 33), (8, 33), (10, 33),
(1, 36), (5, 36), (9, 36),
(2, 39), (6, 39), (7, 39),
(3, 41), (4, 41), (9, 41), (10, 41),
(5, 45), (7, 45), (8, 45);



#QUERIES


-- 1. Average movie review
-- I joined Movies with Reviews using movieID to calculate the average rating for each movie. 
-- I only included reviews with no flags (flags = 0) to ensure invalid reviews 
-- don't affect the score. I used ROUND to make the ratings look neat and sorted them so the 
-- highest-rated movies show first.
SELECT 
    m.title,
    ROUND(AVG(r.rating), 2) AS average_rating,
    COUNT(r.id) AS review_count
FROM Movies m
LEFT JOIN Reviews r 
    ON m.id = r.movieID AND r.flags = 0
GROUP BY m.id, m.title
ORDER BY average_rating DESC;


-- 2. Top rated genre overall
-- This finds the genre with the highest average movie rating. 
-- I joined Tags → MovieTags → Movies → Reviews to get ratings for each genre. 
-- Only valid reviews were counted. 
-- Then I grouped by genre and averaged the ratings. LIMIT 1 gives the top genre.
WITH GenreRatings AS (
    SELECT 
        t.value AS genre,
        AVG(r.rating) AS avg_rating
    FROM Tags t
    JOIN MovieTags mt ON t.id = mt.tagID
    JOIN Movies m ON mt.movieID = m.id
    JOIN Reviews r ON m.id = r.movieID AND r.flags = 0
    WHERE t.type = 'Genre'
    GROUP BY t.value
)
SELECT genre, ROUND(avg_rating, 2) AS avg_genre_rating
FROM GenreRatings
ORDER BY avg_rating DESC
LIMIT 1;


-- 3. Most liked reviews 
-- I counted likes for each review using the Likes table. I joined Reviews → Users → Movies 
-- to show the reviewer and movie title. 
-- LEFT JOIN ensures reviews with zero likes are included. Only valid reviews (flags = 0) are considered. 
-- Finally, I sorted by like_count to get the most liked reviews and limited the results to 5.
WITH ReviewLikes AS (
    SELECT 
        r.id AS review_id,
        p.username,
        m.title AS movie_title,
        r.rating,
        COUNT(l.userID) AS like_count
    FROM Reviews r
    JOIN ProfileInfo p ON r.userID = p.userID
    JOIN Movies m ON r.movieID = m.id
    LEFT JOIN Likes l ON r.id = l.reviewID
    WHERE r.flags = 0
    GROUP BY r.id, p.username, m.title, r.rating
)
SELECT review_id, username, movie_title, rating, like_count
FROM ReviewLikes
ORDER BY like_count DESC, review_id
LIMIT 5;

-- 4. top 5 highest rated movies by average rating
/*natural language: Find the 5 movies with the highest average rating across all reviews.
                    For each movie display the title, the average rating and the nb of reviews.
*/
SELECT
    m.title AS movieTitle,
    ROUND(AVG(r.rating), 2) AS averageRating,
    COUNT(r.id) AS Total_Reviews
FROM
    Movies m
JOIN
    Reviews r ON m.id = r.movieID
GROUP BY
    m.id, m.title
HAVING
    COUNT(r.id) > 0
ORDER BY
    averageRating DESC
LIMIT 5; 

-- 5. display cast or director, and genre tags
/*natural language: For a given movie title, show all people involved 
                    (Cast, Director), along with its Genre
*/
SELECT
    m.title AS movieTitle,
    t.type AS tagType,
    t.value AS tagValue
FROM
    Movies m
JOIN
    MovieTags mt ON m.id = mt.movieID
JOIN
    Tags t ON mt.tagID = t.id
WHERE
    m.title = 'Mean Girls';

/*
-- Find all movies with a certain actor
SELECT 
    m.title AS movieTitle,
    t.type AS tagType,
    t.value AS tagValue
FROM 
    Movies m
JOIN 
    MovieTags mt ON m.id = mt.movieID
JOIN 
    Tags t ON mt.tagID = t.id
WHERE 
    t.value = 'Rachel McAdams';
*/

-- 6. number of reviews each user has written
/*natural language: Show each user and how many reviews they have written 
                    (users with no reviews are included)
*/
SELECT
    p.username AS Username,
    COUNT(r.id) AS reviewsWritten
FROM
    ProfileInfo p
LEFT JOIN
    Reviews r ON p.userID = r.userID
GROUP BY
    p.username
ORDER BY
    reviewsWritten DESC;

/* 7. Query designed to look for the users that have written the most reviews.
Print out the username and the total number of reviews they have submitted in 
descending order (from most to least) */

SELECT
    p.username,
    COUNT(r.id) AS total_reviews
FROM Reviews r
JOIN Users u ON r.userID = u.id
JOIN ProfileInfo p ON u.id = p.userID
GROUP BY p.username
ORDER BY total_reviews DESC;

/* 8. Display how many movies belong to each category type
(from Feature, Short, Blockbuster) */
SELECT
    c.type AS category,
    COUNT(mc.movieID) AS total_movies
FROM Categories c
JOIN MovieCategories mc ON c.id = mc.categoryID
GROUP BY c.type;


/* 9. List all of the movies that are tagged under the "Action" genre */

SELECT
    m.title,
    m.releaseYear,
    t.value AS genre
FROM Movies m
JOIN MovieTags mt ON m.id = mt.movieID
JOIN Tags t ON mt.tagID = t.id
WHERE t.type = 'Genre' AND t.value = 'Action'; 

-- 10. Find the top 3 users who wrote the most reviews for each genre.
WITH GenreUserReviews AS (
    SELECT
        t.value AS genre,
        p.username,
        COUNT(r.id) AS total_reviews
    FROM Reviews r
    JOIN Movies m ON r.movieID = m.id
    JOIN MovieTags mt ON m.id = mt.movieID
    JOIN Tags t ON mt.tagID = t.id
    JOIN ProfileInfo p ON r.userID = p.userID
    WHERE t.type = 'Genre'
    GROUP BY t.value, p.username
)
SELECT genre, username, total_reviews
FROM GenreUserReviews
ORDER BY genre, total_reviews DESC
LIMIT 30; -- adjust as needed



-- 11. Find movies whose average rating is above their genre's overall average rating.
WITH MovieAverages AS (
    SELECT 
        m.id AS movieID,
        m.title,
        t.value AS genre,
        AVG(r.rating) AS movie_avg
    FROM Movies m
    JOIN Reviews r ON m.id = r.movieID
    JOIN MovieTags mt ON m.id = mt.movieID
    JOIN Tags t ON mt.tagID = t.id
    WHERE t.type = 'Genre' AND r.flags = 0
    GROUP BY m.id, m.title, t.value
),
GenreAverages AS (
    SELECT 
        genre,
        AVG(movie_avg) AS genre_avg
    FROM MovieAverages
    GROUP BY genre
)
SELECT 
    ma.title AS movieTitle,
    ma.genre,
    ROUND(ma.movie_avg, 2) AS movieAverage,
    ROUND(ga.genre_avg, 2) AS genreAverage
FROM MovieAverages ma
JOIN GenreAverages ga ON ma.genre = ga.genre
WHERE ma.movie_avg > ga.genre_avg
ORDER BY ma.genre, ma.movie_avg DESC;





-- 12. Find directors whose movies have the highest average rating (minimum 2 movies reviewed).
SELECT
    t.value AS director,
    ROUND(AVG(r.rating), 2) AS avg_rating,
    COUNT(DISTINCT m.id) AS total_movies
FROM Tags t
JOIN MovieTags mt ON t.id = mt.tagID
JOIN Movies m ON mt.movieID = m.id
JOIN Reviews r ON m.id = r.movieID
WHERE t.type = 'Director' AND r.flags = 0
GROUP BY t.value
HAVING COUNT(DISTINCT m.id) >= 2
ORDER BY avg_rating DESC
LIMIT 10;