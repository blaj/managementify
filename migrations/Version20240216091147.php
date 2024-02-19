<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240216091147 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add email to users.users table';
  }

  public function up(Schema $schema): void {
    $this->addSql('TRUNCATE TABLE users.users;');

    $this->addSql('ALTER TABLE users.users ADD COLUMN email VARCHAR(100) NOT NULL;');

    // admin/admin
    $this->addSql(
        'INSERT INTO users.users(username, password, company_id, email) SELECT \'admin\', \'$2y$13$OeDp.7ou0ncKa/5wpEAzwO/I5Hk7hK83mXu9ySvPyHOfBI73wWEiq\', company.id, \'kontakt@managementify.pl\' FROM company.company company WHERE company.name LIKE \'Dummy\';');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN email;');
  }
}
