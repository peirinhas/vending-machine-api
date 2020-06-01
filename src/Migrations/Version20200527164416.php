<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527164416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `machine` table and add row machine';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE machine (
                id CHAR(36) NOT NULL PRIMARY KEY,
                cash DECIMAL(10,2) NOT NULL, 
                wallet DECIMAL(10,2) NOT NULL, 
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );

        $this->addSql('INSERT INTO machine (id, cash, wallet) VALUES ("0f6acbf3-a958-4d2e-9352-bd17f469b002", "10.00", "0.00")');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE machine');
    }
}
