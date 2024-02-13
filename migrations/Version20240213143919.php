<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240213143919 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create client schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA client;');
    $this->addSql('GRANT USAGE ON SCHEMA client TO managementify_app_user;');

    $this->addSql(
        '
        CREATE TABLE client.client (
          id SERIAL PRIMARY KEY, 
          firstname VARCHAR(100) NOT NULL, 
          surname VARCHAR(100) NOT NULL,
          foreign_id VARCHAR(100), 
          deleted BOOLEAN NOT NULL DEFAULT false
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON client.client TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA client TO managementify_app_user;');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE client.client;');
    $this->addSql('DROP SCHEMA client;');
  }
}
