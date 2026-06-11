<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Payment::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('order', 'Commande'),
            ChoiceField::new('method', 'Méthode')->setChoices(Payment::METHOD_CHOICES),
            TextField::new('provider', 'Fournisseur')->setRequired(false),
            ChoiceField::new('status', 'Statut')->setChoices(Payment::STATUS_CHOICES),
            MoneyField::new('amount', 'Montant')->setCurrency('EUR')->setStoredAsCents(false),
            TextField::new('transactionId', 'Transaction')->setRequired(false),
            DateTimeField::new('createdAt', 'Création')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modification')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Paiement')->setEntityLabelInPlural('Paiements')
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
