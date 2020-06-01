<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527165829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `products` table and its relationships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE product(
                id CHAR(36) NOT NULL PRIMARY KEY,
                machine_id CHAR(36) NOT NULL,
                name VARCHAR (100) NOT NULL,
                cost DECIMAL(10,2) NOT NULL,
                stock int NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX IDX_product_machine_id (machine_id), 
                CONSTRAINT FK_product_machine_id FOREIGN KEY (machine_id) REFERENCES machine (id) ON UPDATE CASCADE ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
