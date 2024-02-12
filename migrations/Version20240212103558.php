<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240212103558 extends AbstractMigration
{
  public function getDescription(): string {
    return 'Create users schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA users;');
    $this->addSql('GRANT USAGE ON SCHEMA users TO managementify_app_user;');

    $this->addSql(
        '
        CREATE TABLE users.users (
          id SERIAL PRIMARY KEY, 
          username VARCHAR(50) NOT NULL, 
          password VARCHAR(200) NOT NULL, 
          deleted BOOLEAN NOT NULL DEFAULT false
        );');

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON users.users TO managementify_app_user;');

    // admin/admin
    $this->addSql(
        'INSERT INTO users.users(username, password) VALUES (\'admin\', \'$2y$13$OeDp.7ou0ncKa/5wpEAzwO/I5Hk7hK83mXu9ySvPyHOfBI73wWEiq\')');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE users.users;');
  }
}
