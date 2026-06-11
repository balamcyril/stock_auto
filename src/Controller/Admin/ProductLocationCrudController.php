<?php

namespace App\Controller\Admin;

use App\Entity\ProductLocation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ProductLocationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return ProductLocation::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('product', 'Produit'),
            AssociationField::new('warehouse', 'Entrepôt'),
            TextField::new('shelfCode', 'Emplacement'),
            IntegerField::new('quantity', 'Quantité'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Emplacement produit')->setEntityLabelInPlural('Emplacements produits')
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
