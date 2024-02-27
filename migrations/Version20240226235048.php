<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240226235048 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add client_id and specialist_id to users.users table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users ADD COLUMN client_id BIGINT;');
    $this->addSql(
        'ALTER TABLE users.users ADD CONSTRAINT users_client_id FOREIGN KEY (client_id) REFERENCES client.client(id)');
    $this->addSql(
        'CREATE INDEX idx_users_client_id ON users.users(client_id);');

    $this->addSql('ALTER TABLE users.users ADD COLUMN specialist_id BIGINT;');
    $this->addSql(
        'ALTER TABLE users.users ADD CONSTRAINT users_specialist_id FOREIGN KEY (specialist_id) REFERENCES specialist.specialist(id)');
    $this->addSql(
        'CREATE INDEX idx_users_specialist_id ON users.users(specialist_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN client_id;');
    $this->addSql('ALTER TABLE users.users DROP COLUMN specialist_id;');
  }
}
