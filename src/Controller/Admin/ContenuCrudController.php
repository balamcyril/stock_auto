<?php

namespace App\Controller\Admin;

use App\Entity\Contenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ContenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contenu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Masquer l'ID dans le formulaire
            AssociationField::new('type', 'Type de contenu'),
            TextField::new('title', 'Titre'),
            TextEditorField::new('contenu1', 'Contenu 1')->setRequired(true)->setFormTypeOption('attr', ['style' => 'height: 400px;']),
            
            Field::new('imageFile1', 'Fichier image contenu 1')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),

            ImageField::new('image1', 'Image 1')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/contenus')
                ->hideOnForm(),
            
                TextEditorField::new('contenu2', 'Contenu 2')->setRequired(false)->setFormTypeOption('attr', ['style' => 'height: 400px;']),
            Field::new('imageFile2', 'Fichier image contenu 2')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(), // Afficher uniquement dans le formulaire
            ImageField::new('image2', 'Image 2')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/contenus')
                ->hideOnForm(), // Masquer dans le formulaire
            
            BooleanField::new('publier', 'Publier'),
            DateTimeField::new('updatedAt', 'Mis à jour le')
            ->renderAsText() // Afficher la valeur sous forme de texte
            ->setFormat('dd/MM/yyyy') // Format d'affichage
            ->hideOnForm(), // Masquer dans le formulaire si nécessaire

            CollectionField::new('piecesJointes', 'Pièces Jointes')
                ->useEntryCrudForm(PieceJointeCrudController::class)
                ->setFormTypeOptions(['by_reference' => false]),
        ];
    }
}
