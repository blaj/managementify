-- 1. Create database and users
CREATE DATABASE managementify;

CREATE USER managementify_app_user WITH PASSWORD 'M@nag3ment!fy' NOINHERIT;
COMMENT ON ROLE managementify_app_user IS 'Backend app user';

CREATE USER managementify_migrations_user WITH PASSWORD 'M@nag3ment!fyMigr@ti0ns' NOINHERIT;
COMMENT ON ROLE managementify_migrations_user IS 'Doctrine migrations user';

GRANT CREATE ON DATABASE managementify TO managementify_migrations_user;
GRANT CREATE ON SCHEMA public TO managementify_migrations_user;

-- 2. Grant delete privilege for all tables for fixtures
DO
$$
DECLARE schemaname text;
BEGIN
    FOR schemaname IN (SELECT nspname FROM pg_namespace) LOOP
        EXECUTE 'GRANT DELETE ON ALL TABLES IN SCHEMA ' || schemaname || ' TO managementify_app_user';
        EXECUTE 'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA ' || schemaname || ' TO managementify_app_user';
    END LOOP;
END
$$;