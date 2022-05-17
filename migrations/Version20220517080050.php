<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517080050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA146C783232');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA149D86650F');
        $this->addSql('DROP INDEX IDX_CFBDFA146C783232 ON note');
        $this->addSql('DROP INDEX IDX_CFBDFA149D86650F ON note');
        $this->addSql('ALTER TABLE note ADD user_id INT NOT NULL, ADD partner_id INT NOT NULL, DROP user_id_id, DROP partner_id_id');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA149393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA149393F8FE ON note (partner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA149393F8FE');
        $this->addSql('DROP INDEX IDX_CFBDFA14A76ED395 ON note');
        $this->addSql('DROP INDEX IDX_CFBDFA149393F8FE ON note');
        $this->addSql('ALTER TABLE note ADD user_id_id INT NOT NULL, ADD partner_id_id INT NOT NULL, DROP user_id, DROP partner_id');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA146C783232 FOREIGN KEY (partner_id_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA149D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA146C783232 ON note (partner_id_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA149D86650F ON note (user_id_id)');
    }
}
