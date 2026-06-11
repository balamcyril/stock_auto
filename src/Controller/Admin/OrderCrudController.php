<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Order::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('orderNumber', 'Numéro commande'),
            MoneyField::new('totalAmount', 'Total')->setCurrency('EUR')->setStoredAsCents(false),
            ChoiceField::new('status', 'Statut')->setChoices(Order::STATUS_CHOICES),
            ChoiceField::new('paymentStatus', 'Paiement')->setChoices(Order::PAYMENT_STATUS_CHOICES),
            ChoiceField::new('fulfillmentType', 'Mode')->setChoices(Order::FULFILLMENT_TYPE_CHOICES),
            TextareaField::new('shippingAddress', 'Adresse de livraison')->setRequired(false),
            AssociationField::new('pickupLocation', 'Point de retrait')->setRequired(false),
            CollectionField::new('items', 'Articles')
                ->useEntryCrudForm(OrderItemCrudController::class)
                ->setFormTypeOptions(['by_reference' => false]),
            DateTimeField::new('createdAt', 'Création')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modification')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setPaginatorPageSize(20)
            ->overrideTemplate('crud/detail', 'admin/order_detail.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        if ($responseParameters->get('pageName') === Crud::PAGE_DETAIL) {
            $order = $responseParameters->get('entity')->getInstance();
            $items = $order->getItems();

            $lines = [];
            $totalWeight = 0.0;
            foreach ($items as $item) {
                $product = $item->getProduct();
                $quantity = (int) $item->getQuantity();
                $weight = (float) ($product?->getWeightKg() ?? 0);
                $lineWeight = $weight * $quantity;
                $totalWeight += $lineWeight;

                $lines[] = [
                    'sku' => $product?->getSku(),
                    'name' => $product?->getName(),
                    'brand' => $product?->getBrand()?->getName(),
                    'category' => $product?->getCategory()?->getName(),
                    'subCategory' => $product?->getSubCategory()?->getName(),
                    'quantity' => $quantity,
                    'unitPrice' => $item->getUnitPrice(),
                    'weightKg' => $weight ?: null,
                    'lineWeightKg' => $lineWeight ?: null,
                    'volumeSize' => $product?->getVolumeSize(),
                    'shelfCode' => $product?->getShelfCode(),
                ];
            }

            $responseParameters->set('packingLines', $lines);
            $responseParameters->set('totalWeightKg', $totalWeight);
        }

        return $responseParameters;
    }
}
