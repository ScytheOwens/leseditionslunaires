<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260820210619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fields and constraint on category';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category ADD reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category ADD parent_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1AEA34913 ON category (reference)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD989D9B62 ON product (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_209AA41DAEA34913 ON product_variant (reference)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1727ACA70');
        $this->addSql('DROP INDEX UNIQ_64C19C1AEA34913');
        $this->addSql('DROP INDEX UNIQ_64C19C1989D9B62');
        $this->addSql('DROP INDEX IDX_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category DROP reference');
        $this->addSql('ALTER TABLE category DROP parent_id');
        $this->addSql('DROP INDEX UNIQ_D34A04AD989D9B62');
        $this->addSql('DROP INDEX UNIQ_209AA41DAEA34913');
    }
}
