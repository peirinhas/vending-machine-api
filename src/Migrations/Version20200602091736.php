<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602091736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `history_sale` table and its relationships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE history_sale(
                id CHAR(36) NOT NULL PRIMARY KEY,
                product_id CHAR(36) NOT NULL,
                cost DECIMAL(10,2) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX IDX_history_sale_product_id (product_id), 
                CONSTRAINT FK_history_sale_product_id FOREIGN KEY (product_id) REFERENCES product (id) 
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE history_sale');
    }
}
