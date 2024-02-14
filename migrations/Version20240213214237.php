<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240213214237 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create visit schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA visit;');
    $this->addSql('GRANT USAGE ON SCHEMA visit TO managementify_app_user;');

    $this->addSql(
        '
        CREATE TABLE visit.visit (
          id SERIAL PRIMARY KEY, 
          from_time TIMESTAMP WITHOUT TIME ZONE NOT NULL, 
          to_time TIMESTAMP WITHOUT TIME ZONE NOT NULL, 
          note TEXT, 
          client_id BIGINT NOT NULL, 
          specialist_id BIGINT NOT NULL, 
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_client_id
            FOREIGN KEY(client_id)
              REFERENCES client.client(id),
          CONSTRAINT fk_specialist_id
            FOREIGN KEY(specialist_id)
              REFERENCES specialist.specialist(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON visit.visit TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA visit TO managementify_app_user;');
    
    $this->addSql('CREATE INDEX idx_client_id ON visit.visit(client_id);');
    $this->addSql('CREATE INDEX idx_specialist_id ON visit.visit(specialist_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE visit.visit;');
    $this->addSql('DROP SCHEMA visit;');
  }
}
