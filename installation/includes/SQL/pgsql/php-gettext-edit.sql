DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
	project_id SERIAL,
	project_name TEXT,
	project_path TEXT,
	project_languages_path TEXT
);