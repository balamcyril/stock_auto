<?php

namespace App\Controller\Admin;

use App\Entity\Galerie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class GalerieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galerie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('typeGalerie', 'Type de galerie'),
            AssociationField::new('artiste', 'Artiste')->setRequired(false),
            TextField::new('titre', 'Titre'),
            TextEditorField::new('description', 'Description')->setRequired(false),

            Field::new('file', 'Fichier média')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),

            ImageField::new('image', 'Média')
                ->setBasePath('/uploads/galeries')
                ->hideOnForm(),

            BooleanField::new('publier', 'Publier'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Bons Plans')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Bons Plans')     // Dans les listes
            ->setPageTitle('index', 'Liste des Bons Plans')
            ->setPageTitle('new', 'Créer un <b>Bon Plan</b>')
            ->setPageTitle('edit', 'Modifier un <b>Bon Plan</b>')
            ->setPageTitle('detail', 'Détail d\'un <b>Bon Plan</b>');
    }
}
