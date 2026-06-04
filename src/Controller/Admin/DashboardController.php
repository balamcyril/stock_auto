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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AdminUrlGenerator $adminUrlGenerator
    )
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
        $activeCartCount = $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_ACTIVE]);
        $convertedCartCount = $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_CONVERTED]);
        $abandonedCartCount = $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_ABANDONED]);
        $cartItemCount = $this->entityManager->getRepository(CartItem::class)->count([]);
        $orderCount = $this->entityManager->getRepository(Order::class)->count([]);
        $pendingOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_PENDING]);
        $readyToShipOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_READY_TO_SHIP]);
        $shippedOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_SHIPPED]);
        $deliveredOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_DELIVERED]);
        $archivedOrderCount = $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_ARCHIVED]);
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
            'activeCartCount' => $activeCartCount,
            'convertedCartCount' => $convertedCartCount,
            'abandonedCartCount' => $abandonedCartCount,
            'cartItemCount' => $cartItemCount,
            'orderCount' => $orderCount,
            'pendingOrderCount' => $pendingOrderCount,
            'readyToShipOrderCount' => $readyToShipOrderCount,
            'shippedOrderCount' => $shippedOrderCount,
            'deliveredOrderCount' => $deliveredOrderCount,
            'archivedOrderCount' => $archivedOrderCount,
            'orderItemCount' => $orderItemCount,
            'paymentCount' => $paymentCount,
            'pendingPaymentCount' => $pendingPaymentCount,
            'pickupLocationCount' => $pickupLocationCount,
            'stockValue' => $stockValue,
            'paidRevenue' => $paidRevenue,
        ]);
    }

    #[Route('/admin/paniers/{status}', name: 'admin_carts_by_status')]
    public function cartsByStatus(string $status): Response
    {
        $allowedStatuses = [Cart::STATUS_ACTIVE, Cart::STATUS_CONVERTED, Cart::STATUS_ABANDONED];
        if (!in_array($status, $allowedStatuses, true)) {
            throw $this->createNotFoundException();
        }

        $url = $this->adminUrlGenerator
            ->setController(CartCrudController::class)
            ->setAction('index')
            ->set('filters[status][comparison]', '=')
            ->set('filters[status][value]', $status)
            ->generateUrl();

        return $this->redirect($url);
    }

    #[Route('/admin/commandes/{status}', name: 'admin_orders_by_status')]
    public function ordersByStatus(string $status): Response
    {
        $allowedStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_READY_TO_SHIP,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_ARCHIVED,
        ];
        if (!in_array($status, $allowedStatuses, true)) {
            throw $this->createNotFoundException();
        }

        $url = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->set('filters[status][comparison]', '=')
            ->set('filters[status][value]', $status)
            ->generateUrl();

        return $this->redirect($url);
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
        yield MenuItem::submenu('Panier', 'fa fa-shopping-cart')->setSubItems([
            MenuItem::linkToRoute('Panier actif', 'fa fa-circle', 'admin_carts_by_status', ['status' => Cart::STATUS_ACTIVE])
                ->setBadge((string) $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_ACTIVE]), 'primary'),
            MenuItem::linkToRoute('Panier converti', 'fa fa-check-circle', 'admin_carts_by_status', ['status' => Cart::STATUS_CONVERTED])
                ->setBadge((string) $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_CONVERTED]), 'success'),
            MenuItem::linkToRoute('Panier abandonné', 'fa fa-times-circle', 'admin_carts_by_status', ['status' => Cart::STATUS_ABANDONED])
                ->setBadge((string) $this->entityManager->getRepository(Cart::class)->count(['status' => Cart::STATUS_ABANDONED]), 'warning'),
            MenuItem::linkToCrud('Tous les paniers', 'fa fa-list', Cart::class),
        ]);
        yield MenuItem::linkToCrud('Articles panier', 'fa fa-list-ul', CartItem::class);
        yield MenuItem::submenu('Commande', 'fa fa-box-open')->setSubItems([
            MenuItem::linkToRoute('A traiter', 'fa fa-clock', 'admin_orders_by_status', ['status' => Order::STATUS_PENDING])
                ->setBadge((string) $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_PENDING]), 'warning'),
            MenuItem::linkToRoute('Prête à envoyer', 'fa fa-truck', 'admin_orders_by_status', ['status' => Order::STATUS_READY_TO_SHIP])
                ->setBadge((string) $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_READY_TO_SHIP]), 'primary'),
            MenuItem::linkToRoute('Envoyée', 'fa fa-shipping-fast', 'admin_orders_by_status', ['status' => Order::STATUS_SHIPPED])
                ->setBadge((string) $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_SHIPPED]), 'info'),
            MenuItem::linkToRoute('Livrée', 'fa fa-check', 'admin_orders_by_status', ['status' => Order::STATUS_DELIVERED])
                ->setBadge((string) $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_DELIVERED]), 'success'),
            MenuItem::linkToRoute('Archivée', 'fa fa-archive', 'admin_orders_by_status', ['status' => Order::STATUS_ARCHIVED])
                ->setBadge((string) $this->entityManager->getRepository(Order::class)->count(['status' => Order::STATUS_ARCHIVED]), 'secondary'),
            MenuItem::linkToCrud('Toutes les commandes', 'fa fa-list', Order::class),
        ]);
        yield MenuItem::linkToCrud('Articles commande', 'fa fa-receipt', OrderItem::class);
        yield MenuItem::linkToCrud('Paiements', 'fa fa-credit-card', Payment::class);
        yield MenuItem::linkToCrud('Points de retrait', 'fa fa-store', PickupLocation::class);

        yield MenuItem::section('Session');
        yield MenuItem::linkToLogout('Deconnexion', 'fa fa-sign-out');
    }
}
