
CREATE DATABASE
IF NOT EXISTS menhely_db 
DEFAULT CHARACTER
SET utf8
COLLATE utf8_hungarian_ci;
USE menhely_db;

CREATE TABLE
IF NOT EXISTS users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR
(50) NOT NULL UNIQUE,
    password VARCHAR
(255) NOT NULL,
    firstname VARCHAR
(50) NOT NULL,
    lastname VARCHAR
(50) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE
IF NOT EXISTS messages
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
(100) NOT NULL,
    email VARCHAR
(100) NOT NULL,
    subject VARCHAR
(255) NOT NULL,
    message TEXT NOT NULL,
    user_id INT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
(user_id) REFERENCES users
(id) ON
DELETE
SET NULL
);

CREATE TABLE
IF NOT EXISTS animal_types
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
(50) NOT NULL UNIQUE
);

CREATE TABLE
IF NOT EXISTS animals
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
(50) NOT NULL,
    type_id INT NOT NULL,
    age INT,
    gender ENUM
('hím', 'nőstény') NOT NULL,
    description TEXT,
    image_path VARCHAR
(255),
    arrival_date DATE NOT NULL,
    adopted BOOLEAN DEFAULT FALSE,
    adoption_date DATE,
    FOREIGN KEY
(type_id) REFERENCES animal_types
(id)
);

INSERT IGNORE
INTO animal_types
(name) VALUES
('Kutya'),
('Macska'),
('Nyúl'),
('Tengerimalac');

INSERT IGNORE
INTO users
(username, password, firstname, lastname) VALUES
('teszt', 'teszt', 'Teszt', 'Felhasználó'),
('admin', 'teszt', 'Admin', 'Felhasználó'),
('gondozo', 'teszt', 'Menhely', 'Gondozó');

INSERT INTO messages
    (name, email, subject, message, user_id, sent_at)
VALUES
    ('Nagy Péter', 'peter@example.com', 'Érdeklődés', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.?', NULL, DATE_SUB(NOW(), INTERVAL
3 DAY));

INSERT INTO messages
    (name, email, subject, message, user_id, sent_at)
VALUES
    ('Teszt Felhasználó', 'teszt@example.com', 'Örökbefogadás', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 1, DATE_SUB(NOW(), INTERVAL
2 DAY));

INSERT INTO messages
    (name, email, subject, message, user_id, sent_at)
VALUES
    ('Kiss Anna', 'anna@example.com', 'Támogatás', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.?', NULL, DATE_SUB(NOW(), INTERVAL
1 DAY));

INSERT INTO messages
    (name, email, subject, message, user_id, sent_at)
VALUES
    ('Admin Felhasználó', 'admin@example.com', 'Önkéntes munka', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.?', 2, NOW());

INSERT INTO messages
    (name, email, subject, message, user_id, sent_at)
VALUES
    ('Vendég Látogató', 'vendeg@example.com', 'Nyitvatartás', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.?', NULL, NOW());

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Bogyó', id, 3, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/dog1.jpg', '2025-01-15', FALSE
FROM animal_types
WHERE name = 'Kutya'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Cirmi', id, 2, 'nőstény', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/cat1.jpg', '2025-02-20', FALSE
FROM animal_types
WHERE name = 'Macska'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Tappancs', id, 1, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/rabbit1.jpg', '2025-03-05', FALSE
FROM animal_types
WHERE name = 'Nyúl'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Rex', id, 5, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/dog2.jpg', '2024-12-10', FALSE
FROM animal_types
WHERE name = 'Kutya'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Kormi', id, 4, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/cat2.jpg', '2025-01-30', FALSE
FROM animal_types
WHERE name = 'Macska'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Fülesi', id, 2, 'nőstény', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/rabbit2.jpg', '2025-02-15', FALSE
FROM animal_types
WHERE name = 'Nyúl'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Buksi', id, 2, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/dog3.jpg', '2025-03-01', FALSE
FROM animal_types
WHERE name = 'Kutya'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Folti', id, 1, 'nőstény', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/cat3.jpg', '2025-03-10', FALSE
FROM animal_types
WHERE name = 'Macska'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Morzsi', id, 1, 'hím', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/guinea1.jpg', '2025-03-15', FALSE
FROM animal_types
WHERE name = 'Tengerimalac'
LIMIT 1;

INSERT INTO animals
    (name, type_id, age, gender, description, image_path, arrival_date, adopted)
SELECT 'Picur', id, 2, 'nőstény', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at velit luctus, tristique est vel, consequat orci.', 'assets/img/guinea2.jpg', '2025-03-20', FALSE
FROM animal_types
WHERE name = 'Tengerimalac'
LIMIT 1;
