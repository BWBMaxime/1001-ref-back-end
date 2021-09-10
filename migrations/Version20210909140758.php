<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909140758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_tags');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad7e3c61f9');
        $this->addSql('DROP INDEX idx_d34a04ad7e3c61f9');
        $this->addSql('ALTER TABLE product DROP owner_id');
        $this->addSql('ALTER TABLE "user" ALTER address_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER biography DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_logo DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER facebook DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER linkedin DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER website DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_picture DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_type DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER role DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE product_tags (product_id INT NOT NULL, tags_id INT NOT NULL, PRIMARY KEY(product_id, tags_id))');
        $this->addSql('CREATE INDEX idx_e254b6874584665a ON product_tags (product_id)');
        $this->addSql('CREATE INDEX idx_e254b6878d7b4fb4 ON product_tags (tags_id)');
        $this->addSql('ALTER TABLE product_tags ADD CONSTRAINT fk_e254b6874584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_tags ADD CONSTRAINT fk_e254b6878d7b4fb4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER address_id SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER biography SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_logo SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER facebook SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER linkedin SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER website SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_picture SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER company_type SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER role SET NOT NULL');
        $this->addSql('ALTER TABLE product ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad7e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d34a04ad7e3c61f9 ON product (owner_id)');
    }
}
