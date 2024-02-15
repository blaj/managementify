<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240214221528 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create company schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA company;');
    $this->addSql('GRANT USAGE ON SCHEMA company TO managementify_app_user;');

    $this->addSql(
        '
        CREATE TABLE company.company (
          id SERIAL PRIMARY KEY, 
          name VARCHAR(100) NOT NULL,
          city VARCHAR(100) NOT NULL,
          street VARCHAR(100) NOT NULL,
          postcode CHAR(6) NOT NULL,
          deleted BOOLEAN NOT NULL DEFAULT false
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON company.company TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA company TO managementify_app_user;');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE company.company;');
    $this->addSql('DROP SCHEMA company;');
  }
}
