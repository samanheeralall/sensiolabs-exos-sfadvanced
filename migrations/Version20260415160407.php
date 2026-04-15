<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260415160407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_organization (
              user_id INTEGER NOT NULL,
              organization_id INTEGER NOT NULL,
              PRIMARY KEY (user_id, organization_id),
              CONSTRAINT FK_41221F7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_41221F7E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__volunteering AS
            SELECT
              id,
              start_at,
              end_at,
              conference_id
            FROM
              volunteering
        SQL);
        $this->addSql('DROP TABLE volunteering');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteering (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL,
              conference_id INTEGER NOT NULL,
              for_user_id INTEGER NOT NULL,
              CONSTRAINT FK_7854E8EE604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON
              UPDATE
                NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE,
                CONSTRAINT FK_7854E8EE9B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO volunteering (
              id, start_at, end_at, conference_id
            )
            SELECT
              id,
              start_at,
              end_at,
              conference_id
            FROM
              __temp__volunteering
        SQL);
        $this->addSql('DROP TABLE __temp__volunteering');
        $this->addSql('CREATE INDEX IDX_7854E8EE604B8382 ON volunteering (conference_id)');
        $this->addSql('CREATE INDEX IDX_7854E8EE9B5BB4B8 ON volunteering (for_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_organization');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__volunteering AS
            SELECT
              id,
              start_at,
              end_at,
              conference_id
            FROM
              volunteering
        SQL);
        $this->addSql('DROP TABLE volunteering');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteering (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL,
              conference_id INTEGER NOT NULL,
              CONSTRAINT FK_7854E8EE604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO volunteering (
              id, start_at, end_at, conference_id
            )
            SELECT
              id,
              start_at,
              end_at,
              conference_id
            FROM
              __temp__volunteering
        SQL);
        $this->addSql('DROP TABLE __temp__volunteering');
        $this->addSql('CREATE INDEX IDX_7854E8EE604B8382 ON volunteering (conference_id)');
    }
}
