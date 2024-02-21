<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240219225140 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create visit_type table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE visit.visit_type (
          id SERIAL PRIMARY KEY, 
          name VARCHAR(100) NOT NULL,
          code VARCHAR(50) NOT NULL,
          preferred_price BIGINT,
          company_id BIGINT NOT NULL,
          archived BOOLEAN NOT NULL DEFAULT false
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON visit.visit_type TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA visit TO managementify_app_user;');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE visit.visit_type;');
  }
}
