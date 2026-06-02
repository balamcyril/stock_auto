<?php

namespace App\Controller\Admin;

use App\Entity\OrderItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class OrderItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return OrderItem::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('order', 'Commande'),
            AssociationField::new('product', 'Produit'),
            IntegerField::new('quantity', 'Quantité'),
            MoneyField::new('unitPrice', 'Prix unitaire')->setCurrency('EUR')->setStoredAsCents(false),
            DateTimeField::new('createdAt', 'Création')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Article commande')->setEntityLabelInPlural('Articles commande');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
