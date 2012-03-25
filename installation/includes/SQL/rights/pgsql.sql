DROP TABLE IF EXISTS rights_context_list;
CREATE TABLE rights_context_list (
	id SERIAL,
    "name" varchar(255),
	UNIQUE ("id")
);

DROP TABLE IF EXISTS rights_rights_list;
CREATE TABLE rights_rights_list (
	id SERIAL,
	"from" integer NOT NULL,
	"to" integer NOT NULL,
	"name" varchar(255),
	UNIQUE ("id")
);

DROP TABLE IF EXISTS rights_groups_list;
CREATE TABLE rights_groups_list (
	id SERIAL,
	"from" integer NOT NULL,
	"to" integer NOT NULL,
	"name" varchar(255),
	UNIQUE ("id")
);

DROP TABLE IF EXISTS rights_groups_rights;
CREATE TABLE rights_groups_rights (
	"group" int NOT NULL,
	"right" int NOT NULL,
	UNIQUE ("group", "right")
);

DROP TABLE IF EXISTS rights_users_rights;
CREATE TABLE rights_users_rights (
	"user" int NOT NULL,
	"right" int NOT NULL,
	"context" int NULL,
	"grant" boolean NULL,
	UNIQUE ("user", "right", "context")
);

DROP TABLE IF EXISTS rights_users_groups;
CREATE TABLE rights_users_groups (
	"user" int NOT NULL,
	"group" int NOT NULL,
	"context" int NULL,
	"grant" boolean NULL,
	UNIQUE ("user", "group", "context")
);

INSERT INTO rights_rights_list ("from", "to", "name") VALUES (0, 1, 'ALL');
INSERT INTO rights_groups_list ("from", "to", "name") VALUES (0, 1, 'ALL');