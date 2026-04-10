<?php

namespace App\Controller\Admin;

use App\Entity\TypeGalerie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TypeGalerieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeGalerie::class;
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
            ->setEntityLabelInSingular('Types / Catégories')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Types / Catégories')     // Dans les listes
            ->setPageTitle('index', 'Liste des Types / Catégories')
            ->setPageTitle('new', 'Créer un <b>Types / Catégories</b>')
            ->setPageTitle('edit', 'Modifier un <b>Types / Catégories</b>')
            ->setPageTitle('detail', 'Détail de <b>Types / Catégories</b>');
    }
}
