<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251103173229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fazendas (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, tamanho DOUBLE PRECISION NOT NULL, responsavel VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_84C17E3254BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fazenda_veterinarios (fazenda_id INT NOT NULL, veterinario_id INT NOT NULL, INDEX IDX_629F4EEED4A3545F (fazenda_id), INDEX IDX_629F4EEE1454BD8B (veterinario_id), PRIMARY KEY(fazenda_id, veterinario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gados (id INT AUTO_INCREMENT NOT NULL, fazenda_id INT NOT NULL, codigo VARCHAR(100) NOT NULL, leite DOUBLE PRECISION NOT NULL, racao DOUBLE PRECISION NOT NULL, peso DOUBLE PRECISION NOT NULL, data_nascimento DATE NOT NULL, vivo TINYINT(1) NOT NULL, data_abate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A1DF83420332D99 (codigo), INDEX IDX_A1DF834D4A3545F (fazenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinarios (id INT AUTO_INCREMENT NOT NULL, crmv VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B3D959523697FA2C (crmv), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fazenda_veterinarios ADD CONSTRAINT FK_629F4EEED4A3545F FOREIGN KEY (fazenda_id) REFERENCES fazendas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fazenda_veterinarios ADD CONSTRAINT FK_629F4EEE1454BD8B FOREIGN KEY (veterinario_id) REFERENCES veterinarios (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gados ADD CONSTRAINT FK_A1DF834D4A3545F FOREIGN KEY (fazenda_id) REFERENCES fazendas (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fazenda_veterinarios DROP FOREIGN KEY FK_629F4EEED4A3545F');
        $this->addSql('ALTER TABLE fazenda_veterinarios DROP FOREIGN KEY FK_629F4EEE1454BD8B');
        $this->addSql('ALTER TABLE gados DROP FOREIGN KEY FK_A1DF834D4A3545F');
        $this->addSql('DROP TABLE fazendas');
        $this->addSql('DROP TABLE fazenda_veterinarios');
        $this->addSql('DROP TABLE gados');
        $this->addSql('DROP TABLE veterinarios');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
