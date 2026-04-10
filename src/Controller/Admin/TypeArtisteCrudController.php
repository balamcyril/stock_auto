<?php

namespace App\Controller\Admin;

use App\Entity\TypeArtiste;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TypeArtisteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeArtiste::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégories Jeux et Animations')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Catégories Jeux et Animations')     // Dans les listes
            ->setPageTitle('index', 'Liste des Catégories Jeux et Animations')
            ->setPageTitle('new', 'Créer une <b>Catégorie Jeux et Animations</b>')
            ->setPageTitle('edit', 'Modifier une <b>Catégorie Jeux et Animations</b>')
            ->setPageTitle('detail', 'Détail de la <b>Catégorie Jeux et Animations</b>');
    }
}
