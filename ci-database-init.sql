CREATE DATABASE managementify;

CREATE USER managementify_app_user WITH PASSWORD 'M@nag3ment!fy' NOINHERIT;
COMMENT ON ROLE managementify_app_user IS 'Backend app user';

CREATE USER managementify_migrations_user WITH PASSWORD 'M@nag3ment!fyMigr@ti0ns' NOINHERIT;
COMMENT ON ROLE managementify_migrations_user IS 'Doctrine migrations user';

GRANT CREATE ON DATABASE managementify TO managementify_migrations_user;
GRANT CREATE ON SCHEMA public TO managementify_migrations_user;