<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416161547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, prestataire_id INT NOT NULL, texte VARCHAR(255) NOT NULL, note INT NOT NULL, INDEX IDX_8F91ABF019EB6921 (client_id), INDEX IDX_8F91ABF0BE3DB2B7 (prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, methode VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B1DC7A1EB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, service_id INT NOT NULL, date DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C84955ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, prestataire_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_E19D9AD2BE3DB2B7 (prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF019EB6921 FOREIGN KEY (client_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF019EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0BE3DB2B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EB83297E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE3DB2B7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE paiement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
