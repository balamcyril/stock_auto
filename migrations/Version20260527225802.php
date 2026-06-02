<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527225802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, status VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id BIGINT AUTO_INCREMENT NOT NULL, cart_id BIGINT NOT NULL, product_id BIGINT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), INDEX IDX_F0FE25274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, pickup_location_id BIGINT DEFAULT NULL, order_number VARCHAR(80) NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, shipping_address LONGTEXT DEFAULT NULL, status VARCHAR(30) NOT NULL, payment_status VARCHAR(30) NOT NULL, fulfillment_type VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F5299398551F0F81 (order_number), INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F5299398C77EA60D (pickup_location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order_item` (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, product_id BIGINT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), INDEX IDX_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, method VARCHAR(30) NOT NULL, provider VARCHAR(80) DEFAULT NULL, status VARCHAR(30) NOT NULL, amount NUMERIC(10, 2) NOT NULL, transaction_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6D28840D8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pickup_location (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, city VARCHAR(255) NOT NULL, opening_hours LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id BIGINT AUTO_INCREMENT NOT NULL, brand_id BIGINT NOT NULL, category_id BIGINT NOT NULL, sub_category_id BIGINT DEFAULT NULL, warehouse_id BIGINT DEFAULT NULL, sku VARCHAR(80) NOT NULL, barcode VARCHAR(80) DEFAULT NULL, oem_reference VARCHAR(120) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, weight_kg NUMERIC(8, 2) DEFAULT NULL, volume_size VARCHAR(30) NOT NULL, shelf_code VARCHAR(80) DEFAULT NULL, status VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04ADF9038C4 (sku), INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04ADF7BFE87C (sub_category_id), INDEX IDX_D34A04AD5080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id BIGINT AUTO_INCREMENT NOT NULL, product_id BIGINT NOT NULL, image VARCHAR(255) DEFAULT NULL, is_primary TINYINT(1) NOT NULL, sort_order INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_location (id BIGINT AUTO_INCREMENT NOT NULL, product_id BIGINT NOT NULL, warehouse_id BIGINT NOT NULL, shelf_code VARCHAR(80) NOT NULL, quantity INT NOT NULL, INDEX IDX_952EE35C4584665A (product_id), INDEX IDX_952EE35C5080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_movement (id BIGINT AUTO_INCREMENT NOT NULL, product_id BIGINT NOT NULL, type VARCHAR(20) NOT NULL, quantity INT NOT NULL, reason VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_BB1BC1B54584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id BIGINT AUTO_INCREMENT NOT NULL, category_id BIGINT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BCE3F79812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BIGINT AUTO_INCREMENT NOT NULL, first_name VARCHAR(120) NOT NULL, last_name VARCHAR(120) NOT NULL, email VARCHAR(180) NOT NULL, password_hash VARCHAR(255) NOT NULL, phone VARCHAR(40) DEFAULT NULL, role VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C77EA60D FOREIGN KEY (pickup_location_id) REFERENCES pickup_location (id)');
        $this->addSql('ALTER TABLE `order_item` ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order_item` ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_location ADD CONSTRAINT FK_952EE35C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_location ADD CONSTRAINT FK_952EE35C5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C77EA60D');
        $this->addSql('ALTER TABLE `order_item` DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE `order_item` DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D8D9F6D38');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF7BFE87C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5080ECDE');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_location DROP FOREIGN KEY FK_952EE35C4584665A');
        $this->addSql('ALTER TABLE product_location DROP FOREIGN KEY FK_952EE35C5080ECDE');
        $this->addSql('ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B54584665A');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE `order_item`');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE pickup_location');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_location');
        $this->addSql('DROP TABLE stock_movement');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE warehouse');
    }
}
