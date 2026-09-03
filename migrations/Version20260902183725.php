<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260902183725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix released_on type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP released_on');
        $this->addSql('ALTER TABLE product ADD released_on TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE product_variant ALTER length DROP NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER width DROP NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER height DROP NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER weight DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP released_on');
        $this->addSql('ALTER TABLE product ADD released_on VARCHAR(255)');
        $this->addSql('ALTER TABLE product_variant ALTER length SET NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER width SET NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER height SET NOT NULL');
        $this->addSql('ALTER TABLE product_variant ALTER weight SET NOT NULL');
    }
}
