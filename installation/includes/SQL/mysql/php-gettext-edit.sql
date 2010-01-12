DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
	project_id INTEGER PRIMARY KEY AUTOINCREMENT,
	project_name TEXT,
	project_path TEXT,
	project_languages_path TEXT
);