<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240225212704 extends AbstractMigration
{
  public function getDescription(): string {
    return 'Add client contact table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE client.contact (
          id SERIAL PRIMARY KEY, 
          content VARCHAR(100),
          type VARCHAR(20),
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
        'GRANT INSERT, SELECT, UPDATE ON client.contact TO managementify_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA client TO managementify_app_user;');

    $this->addSql(
        'CREATE INDEX idx_client_contact_client_id ON client.contact(client_id);');
    $this->addSql(
        'CREATE INDEX idx_client_contact_company_id ON client.contact(company_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE client.contact;');
  }
}
