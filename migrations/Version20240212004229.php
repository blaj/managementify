<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240212004229 extends AbstractMigration {

  public function getDescription(): string {
    return 'Check migrations is working';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE TABLE doctrine_migrations_test (id SERIAL PRIMARY KEY);');
    $this->addSql('DROP TABLE doctrine_migrations_test;');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE doctrine_migrations_test;');
  }
}
