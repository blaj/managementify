<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use App\User\Entity\PermissionType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240226015201 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add user role and permission';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE users.role (
          id SERIAL PRIMARY KEY, 
          name VARCHAR(100) NOT NULL,
          code VARCHAR(50) NOT NULL,
          company_id BIGINT NOT NULL,
          archived BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_company_id
              FOREIGN KEY(company_id)
                REFERENCES company.company(id)
        );');

    $this->addSql(
        'CREATE INDEX idx_users_role_company_id ON users.role(company_id);');

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON users.role TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA users TO managementify_app_user;');

    $this->addSql('ALTER TABLE users.users ADD COLUMN role_id BIGINT;');
    $this->addSql(
        'ALTER TABLE users.users ADD CONSTRAINT users_role_id FOREIGN KEY (role_id) REFERENCES users.role(id)');
    $this->addSql(
        'CREATE INDEX idx_users_role_id ON users.users(role_id);');

    $this->addSql(
        '
        CREATE TABLE users.role_permission (
          id SERIAL PRIMARY KEY, 
          role_id BIGINT NOT NULL, 
          type VARCHAR(100) NOT NULL,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_role_id
              FOREIGN KEY(role_id)
                REFERENCES users.role(id)
        );');

    $this->addSql(
        'CREATE INDEX idx_users_role_permission_company_id ON users.role_permission(role_id);');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON users.role_permission TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA users TO managementify_app_user;');

    $this->addSql(
        'INSERT INTO users.role(code, name, company_id) SELECT \'ADMIN\', \'Admin\', company.id FROM company.company company WHERE company.name LIKE \'Dummy\';');

    foreach (PermissionType::cases() as $permissionType) {
      $this->addSql(
          'INSERT INTO users.role_permission(role_id, type) SELECT role.id, \''
          . $permissionType->value
          . '\' FROM users.role role WHERE role.code LIKE \'ADMIN\';');
    }

    $this->addSql('UPDATE users.users SET role_id = (SELECT role.id FROM users.role role WHERE role.code LIKE \'ADMIN\');');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN role_id;');
    $this->addSql('DROP TABLE users.role_permission;');
    $this->addSql('DROP TABLE users.role;');
  }
}
