<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260126213902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, type_actualite_id INT NOT NULL, titre VARCHAR(2048) NOT NULL, description LONGTEXT DEFAULT NULL, date_actualite DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, details VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5492819725C78A76 (type_actualite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, type_galerie_id INT NOT NULL, titre VARCHAR(2048) NOT NULL, date_evenement DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, details VARCHAR(1000) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2CEDC8779F5040EE (type_galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, type_artiste_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix VARCHAR(255) NOT NULL, forfait VARCHAR(255) DEFAULT NULL, ideale VARCHAR(255) DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, options LONGTEXT DEFAULT NULL, photo1 VARCHAR(255) DEFAULT NULL, photo2 VARCHAR(255) DEFAULT NULL, photo3 VARCHAR(255) DEFAULT NULL, photo4 VARCHAR(255) DEFAULT NULL, photo5 VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9C07354F3CD18E61 (type_artiste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, title VARCHAR(2048) NOT NULL, contenu1 LONGTEXT DEFAULT NULL, image1 VARCHAR(255) DEFAULT NULL, contenu2 LONGTEXT DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_89C2003FC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, unite_pedagogique_id INT NOT NULL, nom VARCHAR(2048) NOT NULL, sigle VARCHAR(255) DEFAULT NULL, contenu1 LONGTEXT DEFAULT NULL, image1 VARCHAR(255) DEFAULT NULL, contenu2 LONGTEXT DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, type VARCHAR(20) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_404021BFD6F94D98 (unite_pedagogique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galerie (id INT AUTO_INCREMENT NOT NULL, type_galerie_id INT NOT NULL, titre VARCHAR(2048) NOT NULL, description LONGTEXT DEFAULT NULL, media VARCHAR(255) NOT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9E7D15909F5040EE (type_galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lien (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(2048) NOT NULL, url LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, details LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece_jointe (id INT AUTO_INCREMENT NOT NULL, actualite_id INT DEFAULT NULL, contenu_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AB5111D4A2843073 (actualite_id), INDEX IDX_AB5111D43C1CC488 (contenu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_actualite (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_artiste (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(2048) NOT NULL, actif TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_contenu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(2048) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_galerie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(2048) NOT NULL, actif TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_pedagogique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(2048) NOT NULL, sigle VARCHAR(255) DEFAULT NULL, contenu1 LONGTEXT DEFAULT NULL, image1 VARCHAR(255) DEFAULT NULL, contenu2 LONGTEXT DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_recherche (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(2048) NOT NULL, sigle VARCHAR(255) DEFAULT NULL, contenu1 LONGTEXT DEFAULT NULL, image1 VARCHAR(255) DEFAULT NULL, contenu2 LONGTEXT DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, publier TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_5492819725C78A76 FOREIGN KEY (type_actualite_id) REFERENCES type_actualite (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8779F5040EE FOREIGN KEY (type_galerie_id) REFERENCES type_galerie (id)');
        $this->addSql('ALTER TABLE artiste ADD CONSTRAINT FK_9C07354F3CD18E61 FOREIGN KEY (type_artiste_id) REFERENCES type_artiste (id)');
        $this->addSql('ALTER TABLE contenu ADD CONSTRAINT FK_89C2003FC54C8C93 FOREIGN KEY (type_id) REFERENCES type_contenu (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFD6F94D98 FOREIGN KEY (unite_pedagogique_id) REFERENCES unite_pedagogique (id)');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT FK_9E7D15909F5040EE FOREIGN KEY (type_galerie_id) REFERENCES type_galerie (id)');
        $this->addSql('ALTER TABLE piece_jointe ADD CONSTRAINT FK_AB5111D4A2843073 FOREIGN KEY (actualite_id) REFERENCES actualite (id)');
        $this->addSql('ALTER TABLE piece_jointe ADD CONSTRAINT FK_AB5111D43C1CC488 FOREIGN KEY (contenu_id) REFERENCES contenu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualite DROP FOREIGN KEY FK_5492819725C78A76');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8779F5040EE');
        $this->addSql('ALTER TABLE artiste DROP FOREIGN KEY FK_9C07354F3CD18E61');
        $this->addSql('ALTER TABLE contenu DROP FOREIGN KEY FK_89C2003FC54C8C93');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFD6F94D98');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D15909F5040EE');
        $this->addSql('ALTER TABLE piece_jointe DROP FOREIGN KEY FK_AB5111D4A2843073');
        $this->addSql('ALTER TABLE piece_jointe DROP FOREIGN KEY FK_AB5111D43C1CC488');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE contenu');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('DROP TABLE lien');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE piece_jointe');
        $this->addSql('DROP TABLE type_actualite');
        $this->addSql('DROP TABLE type_artiste');
        $this->addSql('DROP TABLE type_contenu');
        $this->addSql('DROP TABLE type_galerie');
        $this->addSql('DROP TABLE unite_pedagogique');
        $this->addSql('DROP TABLE unite_recherche');
    }
}
