use party;

-- Drop existing tables
DROP TABLE IF EXISTS pairs;
DROP TABLE IF EXISTS people;

-- Create tables
CREATE TABLE people (
    id int NOT NULL AUTO_INCREMENT,
    first_name varchar(255),
    last_name varchar(255),
    password varchar(255),
    ideas TEXT DEFAULT '',
    rsvp TINYINT DEFAULT 0,
    role TINYINT DEFAULT 0,
    invited TINYINT DEFAULT 0,
    in_secret_santa TINYINT DEFAULT 0,
    PRIMARY KEY (id)
);
CREATE TABLE pairs (
    santa int,
    target int,
    party int,
    PRIMARY KEY (santa, target),
    FOREIGN KEY (santa) REFERENCES people(id),
    FOREIGN KEY (target) REFERENCES people(id)
);

-- Insert data
INSERT INTO people (first_name, last_name, invited, role)
    VALUES ('Kai', 'Mizuno', 1, 1);
INSERT INTO people (first_name, last_name, invited)
    VALUES ('Cameron', 'Hockenhull', 1);
INSERT INTO people (first_name, last_name, invited)
    VALUES ('Emily', 'Koke', 1);
INSERT INTO people (first_name, last_name, invited)
    VALUES ('Sarah', 'Sievers', 1);

INSERT INTO pairs (santa, target, party)
    VALUES (1, 3, 2018);
INSERT INTO pairs (santa, target, party)
    VALUES (3, 1, 2018);
INSERT INTO pairs (santa, target, party)
    VALUES (1, 4, 2020);