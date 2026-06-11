<?php

namespace App\Controller\Admin;

use App\Entity\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class CartItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return CartItem::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('cart', 'Panier'),
            AssociationField::new('product', 'Produit'),
            IntegerField::new('quantity', 'Quantité'),
            MoneyField::new('unitPrice', 'Prix unitaire')->setCurrency('EUR')->setStoredAsCents(false),
            DateTimeField::new('createdAt', 'Création')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Article panier')->setEntityLabelInPlural('Articles panier')
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
