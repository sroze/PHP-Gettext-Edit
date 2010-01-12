DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
	project_id INTEGER NOT NULL AUTO_INCREMENT,
	project_name TEXT,
	project_path TEXT,
	project_languages_path TEXT,
	PRIMARY KEY (project_id)
);