DROP TABLE IF EXISTS rights_context_list;
CREATE TABLE rights_context_list (
	id SERIAL,
    "name" varchar(255)
);

DROP TABLE IF EXISTS rights_rights_list;
CREATE TABLE rights_rights_list (
	id SERIAL,
	"from" integer NOT NULL,
	"to" integer NOT NULL,
	"name" varchar(255)
);

DROP TABLE IF EXISTS rights_groups_list;
CREATE TABLE rights_groups_list (
	id SERIAL,
	"from" integer NOT NULL,
	"to" integer NOT NULL,
	"name" varchar(255)
);

DROP TABLE IF EXISTS rights_groups_rights;
CREATE TABLE rights_groups_rights (
	"group" int NOT NULL,
	"right" int NOT NULL,
	"context" int NULL,
	"grant" boolean NULL,
	UNIQUE ("group", "right")
);

DROP TABLE IF EXISTS rights_users_rights;
CREATE TABLE rights_users_rights (
	"user" int NOT NULL,
	"right" int NOT NULL,
	"context" int NULL,
	"grant" boolean NULL,
	UNIQUE ("user", "right")
);

DROP TABLE IF EXISTS rights_users_groups;
CREATE TABLE rights_users_groups (
	"user" int NOT NULL,
	"group" int NOT NULL,
	UNIQUE ("user", "group")
);