<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\PickupLocation;
use App\Entity\Product;
use App\Entity\ProductLocation;
use App\Entity\StockMovement;
use App\Entity\SubCategory;
use App\Entity\User;
use App\Entity\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $productCount = $this->entityManager->getRepository(Product::class)->count([]);
        $activeProductCount = $this->entityManager->getRepository(Product::class)->count(['status' => Product::STATUS_ACTIVE]);
        $outOfStockProductCount = $this->entityManager->getRepository(Product::class)->count(['quantity' => 0]);
        $brandCount = $this->entityManager->getRepository(Brand::class)->count([]);
        $categoryCount = $this->entityManager->getRepository(Category::class)->count([]);
        $subCategoryCount = $this->entityManager->getRepository(SubCategory::class)->count([]);
        $warehouseCount = $this->entityManager->getRepository(Warehouse::class)->count([]);
        $productLocationCount = $this->entityManager->getRepository(ProductLocation::class)->count([]);
        $stockMovementCount = $this->entityManager->getRepository(StockMovement::class)->count([]);
        $userCount = $this->entityManager->getRepository(User::class)->count([]);
        $cartCount = $this->entityManager->getRepository(Cart::class)->count([]);
        $cartItemCount = $this->entityManager->getRepository(CartItem::class)->count([]);
        $orderCount = $this->entityManager->getRepository(Order::class)->count([]);
        $pendingOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_PENDING]);
        $orderItemCount = $this->entityManager->getRepository(OrderItem::class)->count([]);
        $paymentCount = $this->entityManager->getRepository(Payment::class)->count([]);
        $pendingPaymentCount = $this->entityManager->getRepository(Payment::class)->count(['status' => Payment::STATUS_PENDING]);
        $pickupLocationCount = $this->entityManager->getRepository(PickupLocation::class)->count([]);

        $lowStockProductCount = (int) $this->entityManager->createQuery(
            'SELECT COUNT(p.id) FROM App\Entity\Product p WHERE p.quantity > 0 AND p.quantity <= 5'
        )->getSingleScalarResult();

        $stockValue = (float) ($this->entityManager->createQuery(
            'SELECT SUM(p.price * p.quantity) FROM App\Entity\Product p'
        )->getSingleScalarResult() ?? 0);

        $paidRevenue = (float) ($this->entityManager->createQuery(
            'SELECT SUM(o.totalAmount) FROM App\Entity\Order o WHERE o.paymentStatus = :status'
        )->setParameter('status', Order::PAYMENT_PAID)->getSingleScalarResult() ?? 0);

        return $this->render('admin/dashboard.html.twig', [
            'productCount' => $productCount,
            'activeProductCount' => $activeProductCount,
            'outOfStockProductCount' => $outOfStockProductCount,
            'lowStockProductCount' => $lowStockProductCount,
            'brandCount' => $brandCount,
            'categoryCount' => $categoryCount,
            'subCategoryCount' => $subCategoryCount,
            'warehouseCount' => $warehouseCount,
            'productLocationCount' => $productLocationCount,
            'stockMovementCount' => $stockMovementCount,
            'userCount' => $userCount,
            'cartCount' => $cartCount,
            'cartItemCount' => $cartItemCount,
            'orderCount' => $orderCount,
            'pendingOrderCount' => $pendingOrderCount,
            'orderItemCount' => $orderItemCount,
            'paymentCount' => $paymentCount,
            'pendingPaymentCount' => $pendingPaymentCount,
            'pickupLocationCount' => $pickupLocationCount,
            'stockValue' => $stockValue,
            'paidRevenue' => $paidRevenue,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Stock Auto - Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Pièces', 'fa fa-cogs', Product::class);
        yield MenuItem::linkToCrud('Marques', 'fa fa-tags', Brand::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Sous-catégories', 'fa fa-sitemap', SubCategory::class);

        yield MenuItem::section('Stock');
        yield MenuItem::linkToCrud('Entrepôts', 'fa fa-warehouse', Warehouse::class);
        yield MenuItem::linkToCrud('Emplacements produits', 'fa fa-map-marker-alt', ProductLocation::class);
        yield MenuItem::linkToCrud('Mouvements de stock', 'fa fa-exchange-alt', StockMovement::class);

        yield MenuItem::section('Ventes');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Paniers', 'fa fa-shopping-cart', Cart::class);
        yield MenuItem::linkToCrud('Articles panier', 'fa fa-list-ul', CartItem::class);
        yield MenuItem::linkToCrud('Commandes', 'fa fa-box-open', Order::class);
        yield MenuItem::linkToCrud('Articles commande', 'fa fa-receipt', OrderItem::class);
        yield MenuItem::linkToCrud('Paiements', 'fa fa-credit-card', Payment::class);
        yield MenuItem::linkToCrud('Points de retrait', 'fa fa-store', PickupLocation::class);

        yield MenuItem::section('Session');
        yield MenuItem::linkToLogout('Deconnexion', 'fa fa-sign-out');
    }
}
