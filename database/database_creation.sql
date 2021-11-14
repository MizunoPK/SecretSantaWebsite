use party;

-- Drop existing tables
DROP TABLE IF EXISTS pairs;
DROP TABLE IF EXISTS people;
DROP TABLE IF EXISTS party;

-- Create tables
CREATE TABLE people (
    id int NOT NULL AUTO_INCREMENT,
    first_name varchar(255),
    last_name varchar(255),
    password varchar(255) DEFAULT '',
    ideas varchar(255) DEFAULT '',
    rsvp TINYINT DEFAULT 0,
    role TINYINT DEFAULT 0,
    invited TINYINT DEFAULT 0,
    attending TINYINT DEFAULT 0,
    in_secret_santa TINYINT DEFAULT 0,
    PRIMARY KEY (id)
);
CREATE TABLE party (
    year int NOT NULL,
    rsvp_deadline varchar(255),
    party_date varchar(255),
    party_location varchar(255) DEFAULT "Cameron's House",
    targets_assigned TINYINT DEFAULT 0,
    PRIMARY KEY (year)
);
CREATE TABLE pairs (
    santa int,
    target int,
    party int,
    PRIMARY KEY (santa, party)
);

-- Insert data
INSERT INTO people (first_name, last_name, role)
    VALUES ('Kai', 'Mizuno', 1);
INSERT INTO people (first_name, last_name, role)
    VALUES ('Cameron', 'Hockenhull', 1);
INSERT INTO people (first_name, last_name)
    VALUES ('Emily', 'Koke');
INSERT INTO people (first_name, last_name)
    VALUES ('Sarah', 'Sievers');
INSERT INTO people (first_name, last_name)
    VALUES ('Renato', 'Marranzino');
INSERT INTO people (first_name, last_name)
    VALUES ('Cameron', 'Johnson');
INSERT INTO people (first_name, last_name)
    VALUES ('Cody', 'Murrow');
INSERT INTO people (first_name, last_name)
    VALUES ('Chloe', 'King');
INSERT INTO people (first_name, last_name)
    VALUES ('Chris', 'Glance');
INSERT INTO people (first_name, last_name)
    VALUES ('Hunter', 'Dreier');
INSERT INTO people (first_name, last_name)
    VALUES ('Logan', 'Perkins');
INSERT INTO people (first_name, last_name)
    VALUES ('Rachel', 'Kelly');
INSERT INTO people (first_name, last_name)
    VALUES ('Tony', 'Mai');
INSERT INTO people (first_name, last_name)
    VALUES ('Josh', 'Andersen');
INSERT INTO people (first_name, last_name)
    VALUES ('Decker', 'Graham');

INSERT INTO party (year)
    VALUES (2017);
INSERT INTO party (year)
    VALUES (2019);
INSERT INTO party (year, rsvp_deadline, party_date)
    VALUES (2021, 'December 1st', 'December 16th at 8:00pm');