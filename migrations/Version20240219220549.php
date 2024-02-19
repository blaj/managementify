<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240219220549 extends AbstractMigration {

  public function getDescription(): string {
    return 'Grant permission to users schema';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA users TO managementify_app_user;');
  }

  public function down(Schema $schema): void {}
}
