<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240214223151 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add dummy company and user';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        'INSERT INTO company.company(name, city, street, postcode) VALUES (\'Dummy\', \'City\', \'Example Street 1\', \'00-000\')');

    // admin/admin
    $this->addSql(
        'INSERT INTO users.users(username, password, company_id) SELECT \'admin\', \'$2y$13$OeDp.7ou0ncKa/5wpEAzwO/I5Hk7hK83mXu9ySvPyHOfBI73wWEiq\', company.id FROM company.company company WHERE company.name LIKE \'Dummy\';');
  }

  public function down(Schema $schema): void {
    $this->addSql('DELETE FROM users.users WHERE username LIKE \'admin\';');
  }
}
