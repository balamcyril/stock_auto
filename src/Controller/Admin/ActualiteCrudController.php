<?php

namespace App\Controller\Admin;

use App\Entity\Actualite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ActualiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actualite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('typeActualite', 'Thème'),
            TextField::new('titre', 'Titre'),
            TextareaField::new('details', 'Texte court / Résumé')
            ->setRequired(true)
            ->setFormTypeOption('attr', [
                'maxlength' => 255, // Limite à 255 caractères
                'oninput' => "document.getElementById('details-count').textContent = this.value.length + '/255'"
            ])
            ->setHelp('<span id="details-count">0/255</span> caractères saisis'),
            TextEditorField::new('description', 'Description / Texte long')->setRequired(true)->setFormTypeOption('attr', ['style' => 'height: 400px;']),
            DateTimeField::new('dateActualite', 'Date de l\'actualité')->setFormat('dd/MM/yyyy'),

            Field::new('imageFile', 'Fichier image')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(),

            ImageField::new('image', 'Image')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/actualites')
                ->hideOnForm(),
            CollectionField::new('piecesJointes', 'Pièces Jointes')
                ->useEntryCrudForm(PieceJointeCrudController::class)
                ->setFormTypeOptions(['by_reference' => false]),

            BooleanField::new('publier', 'Publier'),
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Blogs et Actualités')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Articles Blogs et Actualités')     // Dans les listes
            ->setPageTitle('index', 'Liste des Articles Blogs et Actualités')
            ->setPageTitle('new', 'Créer un <b>article</b>')
            ->setPageTitle('edit', 'Modifier un <b>article</b>')
            ->setPageTitle('detail', 'Détail de <b> l\article</b>');
    }
}
