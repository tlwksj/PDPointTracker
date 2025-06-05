# Project 1
# Trishelle Leal Uribe
# CMSCI 359: Database Management Spring 2025

CREATE TABLE PDstudent(
	student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    year ENUM('Freshman', 'Sophomore', 'Junior', 'Senior') NOT NULL,
    PDworth INT
    );
CREATE TABLE PDclasses(
	class_code VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    semester VARCHAR(100),
    PDreq INT
    );
CREATE TABLE student_class(
	student_id INT,
    class_code VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (student_id, class_code),
    );

CREATE TABLE PDevents(
	event_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    type enum("Talk", "Club"),
    date date,
    PDworth INT
    );

CREATE TABLE eventattendance(
	student_id INT,
    event_id INT,
    PRIMARY KEY (student_id, event_id)
    );

ALTER TABLE event_attendance ADD pointgained INT;

DELIMITER //

CREATE TRIGGER PDpunto
AFTER INSERT ON eventattendance
FOR EACH ROW
BEGIN
	UPDATE PDstudent
    SET pdworth = pdworth + 1
    WHERE student_id = NEW.student_id ;
END //

DELIMITER ;

DROP TRIGGER PDpoints;

ALTER TABLE PDevents ADD semester VARCHAR(100);

INSERT INTO PDclasses(class_code, name, semester, PDreq) VALUES ("CMSCI 359", "Database Management", "Spring 2025" , 8
),("CMSCI 485", "Theory of Computation", "Spring 2025" , 8), ("DATA 320", "Data Architecture", "Spring 2025" , 6), 
("DATA 201 ", "Special Topics in Data", "Spring 2025" , 0);