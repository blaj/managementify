<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240228234718 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add visit_type_id to visit.visit table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE visit.visit ADD COLUMN visit_type_id BIGINT;');
    $this->addSql(
        'ALTER TABLE visit.visit ADD CONSTRAINT visit_visit_type_id FOREIGN KEY (visit_type_id) REFERENCES visit.visit_type(id)');
    $this->addSql(
        'CREATE INDEX idx_visit_visit_type_id ON visit.visit(visit_type_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE visit.visit DROP COLUMN visit_type_id;');
  }
}
