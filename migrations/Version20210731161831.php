<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731161831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, disposition INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_sub_category (id INT AUTO_INCREMENT NOT NULL, shop_category_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, disposition INT DEFAULT NULL, INDEX IDX_A186900AC0316BF2 (shop_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_sub_category ADD CONSTRAINT FK_A186900AC0316BF2 FOREIGN KEY (shop_category_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE product ADD shop_sub_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD1E4CEC48 FOREIGN KEY (shop_sub_category_id) REFERENCES shop_sub_category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD1E4CEC48 ON product (shop_sub_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_sub_category DROP FOREIGN KEY FK_A186900AC0316BF2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD1E4CEC48');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE shop_sub_category');
        $this->addSql('DROP INDEX IDX_D34A04AD1E4CEC48 ON product');
        $this->addSql('ALTER TABLE product DROP shop_sub_category_id');
    }
}
