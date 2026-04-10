<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
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

class ArtisteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artiste::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('typeArtiste', 'Categorie'),
            TextField::new('titre', 'Titre du jeux ou de l\'animation'),
            TextField::new('description', 'Description'),
            TextField::new('prix', 'Prix location'),
            TextField::new('forfait', 'Forfait jour/weekend/semaine'),
            TextEditorField::new('description', 'Description')->setRequired(false),
            TextEditorField::new('contenu', 'Contenu de la location')->setRequired(false),
            TextEditorField::new('options', 'Options de la location')->setRequired(false),
            TextEditorField::new('ideale', 'Idéale pour')->setRequired(false),

            Field::new('photoFile1', 'Photo1')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),
            ImageField::new('photo1', 'Photo1')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/artistes')
                ->hideOnForm(),

            Field::new('photoFile2', 'Photo2')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),
            ImageField::new('photo2', 'Photo2')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/artistes')
                ->hideOnForm(),

            Field::new('photoFile3', 'Photo3')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),
            ImageField::new('photo3', 'Photo3')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/artistes')
                ->hideOnForm(),

            Field::new('photoFile4', 'Photo4')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),
            ImageField::new('photo4', 'Photo4')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/artistes')
                ->hideOnForm(),

            Field::new('photoFile5', 'Photo5')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),
            ImageField::new('photo5', 'Photo5')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/artistes')
                ->hideOnForm(),

            BooleanField::new('publier', 'Publier'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Jeux et Animations')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Jeux et Animations')     // Dans les listes
            ->setPageTitle('index', 'Liste des Jeux et Animations')
            ->setPageTitle('new', 'Créer un <b>Jeux ou Animations</b>')
            ->setPageTitle('edit', 'Modifier un <b>Jeux ou Animations</b>')
            ->setPageTitle('detail', 'Détail du <b>Catégorie Jeux ou Animations</b>');
    }
}
