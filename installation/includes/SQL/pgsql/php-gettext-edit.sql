DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
	project_id SERIAL,
	project_name TEXT,
	project_path TEXT,
	project_languages_path TEXT
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
	id SERIAL,
	username TEXT,
	password TEXT,
	email TEXT
);