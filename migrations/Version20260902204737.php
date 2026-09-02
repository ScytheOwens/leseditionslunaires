<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260902204737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add missing product variant column on media';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_category (product_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY (product_id, category_id))');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medium ADD product_variant_id UUID NOT NULL');
        $this->addSql('ALTER TABLE medium ADD CONSTRAINT FK_C67345B7A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_C67345B7A80EF684 ON medium (product_variant_id)');
        $this->addSql('ALTER TABLE product_variant ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC735612469DE2');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('ALTER TABLE medium DROP CONSTRAINT FK_C67345B7A80EF684');
        $this->addSql('DROP INDEX IDX_C67345B7A80EF684');
        $this->addSql('ALTER TABLE medium DROP product_variant_id');
        $this->addSql('ALTER TABLE product_variant DROP name');
    }
}
