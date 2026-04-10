<?php

namespace App\Controller\Admin;

use App\Entity\Lien;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class LienCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lien::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('titre'),
            TextEditorField::new('url'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Textes annexes')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Textes annexes')     // Dans les listes
            ->setPageTitle('index', 'Liste des Textes annexes')
            ->setPageTitle('new', 'Créer un <b>Texte annexe</b>')
            ->setPageTitle('edit', 'Modifier un <b>Texte annexe</b>')
            ->setPageTitle('detail', 'Détail d\un <b>Texte annexe</b>');
    }    
}
