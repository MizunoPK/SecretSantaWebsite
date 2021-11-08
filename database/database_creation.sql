use party;

-- Drop existing tables
DROP TABLE IF EXISTS people;
DROP TABLE IF EXISTS targets;

-- Create tables
CREATE TABLE people (
    id int NOT NULL AUTO_INCREMENT,
    first_name varchar(255),
    last_name varchar(255),
    ideas TEXT,
    rsvp TINYINT DEFAULT 0,
    role TINYINT DEFAULT 0,
    PRIMARY KEY (id)
);
CREATE TABLE pairs (
    santa int,
    target int,
    partyYear int,
    PRIMARY KEY (santa, target),
    FOREIGN KEY (santa) REFERENCES people(id),
    FOREIGN KEY (target) REFERENCES people(id)
);

-- Insert data
INSERT INTO people (first_name, last_name, role)
    VALUES ('Kai', 'Mizuno', 1);
INSERT INTO people (first_name, last_name)
    VALUES ('Cameron', 'Hockenhull');
INSERT INTO people (first_name, last_name)
    VALUES ('Emily', 'Koke');
INSERT INTO people (first_name, last_name)
    VALUES ('Sarah', 'Sievers');

INSERT INTO pairs (santa, target, party)
    VALUES (1, 3, 2018);
INSERT INTO pairs (santa, target, party)
    VALUES (3, 1, 2018);
INSERT INTO pairs (santa, target, party)
    VALUES (1, 4, 2020);