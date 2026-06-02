<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\PickupLocation;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductLocation;
use App\Entity\StockMovement;
use App\Entity\SubCategory;
use App\Entity\User;
use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $brands = [];
        $brandNames = [
            'AutoTech',
            'EuroParts',
            'ProMécanique',
            'MasterDrive',
            'TurboLine',
            'Piston & Co',
            'SynchroCar',
            'OptiMotor',
        ];

        foreach ($brandNames as $index => $name) {
            $brand = new Brand();
            $brand->setName($name);
            $brand->setImage(sprintf('brand-%02d.png', $index + 1));
            $manager->persist($brand);
            $brands[] = $brand;
        }

        $categories = [];
        $subCategoriesByCategory = [];
        $categoriesData = [
            'Moteur' => ['Filtres', 'Bougies', 'Injecteurs'],
            'Freinage' => ['Plaquettes', 'Disques', 'Liquides'],
            'Suspension' => ['Amortisseurs', 'Ressorts', 'Rotules'],
            'Éclairage' => ['Phares', 'Ampoules', 'Feux arrière'],
            'Électronique' => ['Capteurs', 'Units de commande', 'Batteries'],
        ];

        $categoryIndex = 0;
        $subCategoryIndex = 0;
        foreach ($categoriesData as $categoryName => $subCategoryNames) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setImage(sprintf('category-%02d.png', ++$categoryIndex));
            $manager->persist($category);
            $categories[] = $category;

            $subCategoriesByCategory[$categoryName] = [];
            foreach ($subCategoryNames as $subCategoryName) {
                $subCategory = new SubCategory();
                $subCategory->setName($subCategoryName);
                $subCategory->setCategory($category);
                $subCategory->setImage(sprintf('subcat-%02d.png', ++$subCategoryIndex));
                $manager->persist($subCategory);
                $subCategoriesByCategory[$categoryName][] = $subCategory;
            }
        }

        $warehouses = [];
        $warehouseData = [
            ['name' => 'Entrepôt Nord', 'city' => 'Lille', 'address' => '12 rue des Frênes'],
            ['name' => 'Centre Logistique Sud', 'city' => 'Lyon', 'address' => '18 avenue du Rhône'],
            ['name' => 'Stock Paris', 'city' => 'Paris', 'address' => '7 boulevard Victor'],
            ['name' => 'Hub Ouest', 'city' => 'Bordeaux', 'address' => '23 rue des Vignes'],
        ];

        foreach ($warehouseData as $warehouseItem) {
            $warehouse = new Warehouse();
            $warehouse->setName($warehouseItem['name']);
            $warehouse->setAddress($warehouseItem['address']);
            $warehouse->setCity($warehouseItem['city']);
            $manager->persist($warehouse);
            $warehouses[] = $warehouse;
        }

        $pickupLocations = [];
        $pickupData = [
            ['name' => 'Retrait Gare du Nord', 'city' => 'Paris', 'address' => '10 place de la Gare', 'hours' => 'Lun-Ven 9h-18h'],
            ['name' => 'Retrait Quai Sud', 'city' => 'Lyon', 'address' => '5 quai Victor', 'hours' => 'Lun-Sam 10h-19h'],
            ['name' => 'Retrait Grand Ouest', 'city' => 'Bordeaux', 'address' => '12 rue des Lilas', 'hours' => 'Mar-Dim 9h-17h'],
            ['name' => 'Retrait Lille Centre', 'city' => 'Lille', 'address' => '8 rue Saint-Sauveur', 'hours' => 'Lun-Ven 8h-16h'],
        ];

        foreach ($pickupData as $item) {
            $pickup = new PickupLocation();
            $pickup->setName($item['name']);
            $pickup->setCity($item['city']);
            $pickup->setAddress($item['address']);
            $pickup->setOpeningHours($item['hours']);
            $manager->persist($pickup);
            $pickupLocations[] = $pickup;
        }

        $defaultPassword = password_hash('P@ssw0rd123', PASSWORD_BCRYPT);
        $users = [];
        $usersData = [
            ['firstName' => 'Admin', 'lastName' => 'System', 'email' => 'admin@example.com', 'phone' => '0600000001', 'role' => User::ROLE_ADMIN],
            ['firstName' => 'Stock', 'lastName' => 'Manager', 'email' => 'warehouse@example.com', 'phone' => '0600000002', 'role' => User::ROLE_WAREHOUSE],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setRole($userData['role']);
            $user->setPasswordHash($defaultPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail(sprintf('client%d@example.com', $i + 1));
            $user->setPhone($faker->phoneNumber());
            $user->setRole(User::ROLE_CUSTOMER);
            $user->setPasswordHash($defaultPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        $customerUsers = array_filter($users, static fn (User $user) => $user->getRole() === User::ROLE_CUSTOMER);

        $productNames = [
            'Bougie d’allumage',
            'Filtre à huile',
            'Plaquette de frein avant',
            'Disque de frein ventilé',
            'Amortisseur arrière',
            'Ressort de suspension',
            'Phares LED',
            'Batterie 12V 60Ah',
            'Capteur de pression',
            'Unité de commande moteur',
            'Courroie de distribution',
            'Pompe à eau',
            'Radiateur moteur',
            'Alternateur',
            'Injecteur common rail',
            'Moteur d’essuie-glace',
            'Turbocompresseur',
            'Système d’échappement',
            'Liquide de frein DOT 4',
            'Projecteur antibrouillard',
        ];

        $volumeChoices = array_values(Product::VOLUME_SIZE_CHOICES);
        $statusChoices = [Product::STATUS_ACTIVE, Product::STATUS_ARCHIVED];
        $products = [];

        $productCount = count($productNames);
        for ($i = 0; $i < $productCount; $i++) {
            $category = $faker->randomElement($categories);
            $subCategory = $faker->randomElement($subCategoriesByCategory[$category->getName()]);
            $brand = $faker->randomElement($brands);
            $warehouse = $faker->randomElement($warehouses);

            $product = new Product();
            $product->setSku(sprintf('SKU-%04d', $i + 1));
            $product->setName($productNames[$i]);
            $product->setBrand($brand);
            $product->setCategory($category);
            $product->setSubCategory($subCategory);
            $product->setBarcode($faker->ean13());
            $product->setOemReference(sprintf('OEM-%s', strtoupper($faker->bothify('???##'))));
            $product->setDescription($faker->paragraph(3));
            $product->setPrice(number_format($faker->randomFloat(2, 12, 450), 2, '.', ''));
            $product->setQuantity($faker->numberBetween(0, 120));
            $product->setWeightKg(number_format($faker->randomFloat(2, 0.5, 20), 2, '.', ''));
            $product->setVolumeSize($faker->randomElement($volumeChoices));
            $product->setWarehouse($warehouse);
            $product->setShelfCode(sprintf('%s%d', chr($faker->numberBetween(65, 90)), $faker->numberBetween(1, 99)));
            $product->setStatus($faker->boolean(88) ? Product::STATUS_ACTIVE : Product::STATUS_ARCHIVED);
            $createdAt = $faker->dateTimeBetween('-18 months', 'now');
            $product->setCreatedAt($createdAt);
            $product->setUpdatedAt($faker->dateTimeBetween($createdAt, 'now'));

            $image = new ProductImage();
            $image->setImage(sprintf('product-%02d-1.png', $i + 1));
            $image->setIsPrimary(true);
            $image->setSortOrder(1);
            $product->addImage($image);
            $manager->persist($image);

            if ($faker->boolean(50)) {
                $extraImage = new ProductImage();
                $extraImage->setImage(sprintf('product-%02d-2.png', $i + 1));
                $extraImage->setIsPrimary(false);
                $extraImage->setSortOrder(2);
                $product->addImage($extraImage);
                $manager->persist($extraImage);
            }

            $manager->persist($product);
            $products[] = $product;
        }

        $productLocations = [];
        foreach ($products as $product) {
            $location = new ProductLocation();
            $location->setProduct($product);
            $location->setWarehouse($faker->randomElement($warehouses));
            $location->setShelfCode(sprintf('%s%d', chr($faker->numberBetween(65, 90)), $faker->numberBetween(1, 99)));
            $location->setQuantity($faker->numberBetween(0, (int)$product->getQuantity()));
            $manager->persist($location);
            $productLocations[] = $location;

            if ($faker->boolean(40)) {
                $extraLocation = new ProductLocation();
                $extraLocation->setProduct($product);
                $extraLocation->setWarehouse($faker->randomElement($warehouses));
                $extraLocation->setShelfCode(sprintf('%s%d', chr($faker->numberBetween(65, 90)), $faker->numberBetween(1, 99)));
                $extraLocation->setQuantity($faker->numberBetween(0, max(1, (int)$product->getQuantity())));
                $manager->persist($extraLocation);
                $productLocations[] = $extraLocation;
            }
        }

        $orders = [];
        $orderStatusChoices = [Order::STATUS_PENDING, Order::STATUS_READY_TO_SHIP, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED, Order::STATUS_ARCHIVED];
        $paymentStatusChoices = [Payment::STATUS_PENDING, Payment::STATUS_PAID, Payment::STATUS_FAILED, Payment::STATUS_REFUNDED];

        for ($i = 0; $i < 12; $i++) {
            $customer = $faker->randomElement($customerUsers);
            $order = new Order();
            $order->setUser($customer);
            $order->setOrderNumber(sprintf('CMD-%05d', $i + 1));
            $isPickup = $faker->boolean(25);
            if ($isPickup) {
                $order->setFulfillmentType(Order::FULFILLMENT_PICKUP);
                $order->setPickupLocation($faker->randomElement($pickupLocations));
            } else {
                $order->setFulfillmentType(Order::FULFILLMENT_DELIVERY);
                $order->setShippingAddress($faker->address());
            }

            $order->setStatus($faker->randomElement($orderStatusChoices));
            $paymentStatus = match ($order->getStatus()) {
                Order::STATUS_DELIVERED, Order::STATUS_SHIPPED => Payment::STATUS_PAID,
                default => $faker->randomElement($paymentStatusChoices),
            };
            $order->setPaymentStatus($paymentStatus);
            $order->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $order->setUpdatedAt($faker->dateTimeBetween($order->getCreatedAt(), 'now'));

            $itemsCount = $faker->numberBetween(1, 5);
            $orderTotal = 0.0;
            $chosenProducts = $faker->randomElements($products, $itemsCount);
            foreach ($chosenProducts as $product) {
                $itemQuantity = $faker->numberBetween(1, 4);
                $item = new OrderItem();
                $item->setOrder($order);
                $item->setProduct($product);
                $item->setQuantity($itemQuantity);
                $item->setUnitPrice($product->getPrice());
                $manager->persist($item);
                $order->addItem($item);
                $orderTotal += ((float)$product->getPrice()) * $itemQuantity;
            }

            $order->setTotalAmount(number_format($orderTotal, 2, '.', ''));
            $manager->persist($order);
            $orders[] = $order;

            $payment = new Payment();
            $payment->setOrder($order);
            $payment->setMethod($faker->randomElement([Payment::METHOD_CARD, Payment::METHOD_PAYPAL]));
            $payment->setProvider($payment->getMethod() === Payment::METHOD_PAYPAL ? 'PayPal' : 'Stripe');
            $payment->setStatus($paymentStatus);
            $payment->setAmount($order->getTotalAmount());
            $payment->setTransactionId(sprintf('TX-%s', strtoupper($faker->bothify('??##??'))));
            $payment->setCreatedAt($faker->dateTimeBetween($order->getCreatedAt(), 'now'));
            $payment->setUpdatedAt($faker->dateTimeBetween($payment->getCreatedAt(), 'now'));
            $manager->persist($payment);
        }

        $carts = [];
        $cartStatusChoices = [Cart::STATUS_ACTIVE, Cart::STATUS_CONVERTED, Cart::STATUS_ABANDONED];
        foreach ($customerUsers as $index => $customer) {
            $cart = new Cart();
            $cart->setUser($customer);
            $cart->setStatus($faker->randomElement($cartStatusChoices));
            $cart->setCreatedAt($faker->dateTimeBetween('-4 months', 'now'));
            $cart->setUpdatedAt($faker->dateTimeBetween($cart->getCreatedAt(), 'now'));

            $itemsCount = $faker->numberBetween(1, 4);
            $cartProducts = $faker->randomElements($products, $itemsCount);
            foreach ($cartProducts as $product) {
                $item = new CartItem();
                $item->setCart($cart);
                $item->setProduct($product);
                $item->setQuantity($faker->numberBetween(1, 3));
                $item->setUnitPrice($product->getPrice());
                $manager->persist($item);
                $cart->addItem($item);
            }

            $manager->persist($cart);
            $carts[] = $cart;
        }

        for ($i = 0; $i < 30; $i++) {
            $product = $faker->randomElement($products);
            $movement = new StockMovement();
            $movement->setProduct($product);
            $movement->setType($faker->randomElement([StockMovement::TYPE_IN, StockMovement::TYPE_OUT, StockMovement::TYPE_ADJUST]));
            $movement->setQuantity($faker->numberBetween(1, 20));
            $movement->setReason($faker->sentence(5));
            $movement->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $manager->persist($movement);
        }

        $manager->flush();
    }
}
