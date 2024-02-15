<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240214222348 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add company_id column to tables';
  }

  public function up(Schema $schema): void {
    $this->addSql('TRUNCATE TABLE users.users, visit.visit, specialist.specialist, client.client;');

    $this->addSql(
        'ALTER TABLE users.users ADD company_id BIGINT NOT NULL CONSTRAINT fk_company_id REFERENCES company.company(id);');
    $this->addSql(
        'ALTER TABLE specialist.specialist ADD company_id BIGINT NOT NULL CONSTRAINT fk_company_id REFERENCES company.company(id);');
    $this->addSql(
        'ALTER TABLE client.client ADD company_id BIGINT NOT NULL CONSTRAINT fk_company_id REFERENCES company.company(id);');
    $this->addSql(
        'ALTER TABLE visit.visit ADD company_id BIGINT NOT NULL CONSTRAINT fk_company_id REFERENCES company.company(id);');

    $this->addSql('CREATE INDEX idx_company_id ON users.users(company_id);');
    $this->addSql('CREATE INDEX idx_company_id ON specialist.specialist(company_id);');
    $this->addSql('CREATE INDEX idx_company_id ON client.client(company_id);');
    $this->addSql('CREATE INDEX idx_company_id ON visit.visit(company_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN company_id;');
    $this->addSql('ALTER TABLE specialist.specialist DROP COLUMN company_id;');
    $this->addSql('ALTER TABLE client.client DROP COLUMN company_id;');
    $this->addSql('ALTER TABLE visit.visit DROP COLUMN company_id;');
  }
}
