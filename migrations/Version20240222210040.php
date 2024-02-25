<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240222210040 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create client_specialist schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA client_specialist;');
    $this->addSql('GRANT USAGE ON SCHEMA client_specialist TO managementify_app_user;');

    $this->addSql(
        '
        CREATE TABLE client_specialist.client_specialist (
          id SERIAL PRIMARY KEY, 
          from_date DATE,
          to_date DATE,
          assign_type VARCHAR(10) NOT NULL,
          client_id BIGINT NOT NULL,
          specialist_id BIGINT NOT NULL,
          company_id BIGINT NOT NULL,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_client_id
            FOREIGN KEY(client_id)
              REFERENCES client.client(id),
          CONSTRAINT fk_specialist_id
            FOREIGN KEY(specialist_id)
              REFERENCES specialist.specialist(id),
          CONSTRAINT fk_company_id
              FOREIGN KEY(company_id)
                REFERENCES company.company(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON client_specialist.client_specialist TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA client_specialist TO managementify_app_user;');

    $this->addSql(
        'CREATE INDEX idx_client_specialist_client_specialist_client_id ON client_specialist.client_specialist(client_id);');
    $this->addSql(
        'CREATE INDEX idx_client_specialist_client_specialist_specialist_id ON client_specialist.client_specialist(specialist_id);');
    $this->addSql(
        'CREATE INDEX idx_client_specialist_client_specialist_company_id ON client_specialist.client_specialist(company_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE client_specialist.client_specialist;');
    $this->addSql('DROP SCHEMA client_specialist;');
  }
}
