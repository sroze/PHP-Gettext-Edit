DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
	project_id INTEGER NOT NULL AUTO_INCREMENT,
	project_name TEXT,
	project_path TEXT,
	project_languages_path TEXT,
	PRIMARY KEY (project_id)
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
	id INTEGER NOT NULL AUTO_INCREMENT,
	username TEXT,
	password TEXT,
	email TEXT,
	PRIMARY KEY (id)
);