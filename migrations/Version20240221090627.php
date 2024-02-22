<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240221090627 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add client preferred hours table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE client.preferred_hour (
          id SERIAL PRIMARY KEY, 
          from_time TIME WITHOUT TIME ZONE NOT NULL,
          to_time TIME WITHOUT TIME ZONE NOT NULL,
          day_of_week VARCHAR(10),
          client_id BIGINT NOT NULL,
          company_id BIGINT NOT NULL,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_client_id
            FOREIGN KEY(client_id)
              REFERENCES client.client(id),
          CONSTRAINT fk_company_id
              FOREIGN KEY(company_id)
                REFERENCES company.company(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON client.preferred_hour TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA client TO managementify_app_user;');

    $this->addSql(
        'CREATE INDEX idx_client_preferred_hour_client_id ON client.preferred_hour(client_id);');
    $this->addSql(
        'CREATE INDEX idx_client_preferred_hour_company_id ON client.preferred_hour(company_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE client.preferred_hour;');
  }
}
