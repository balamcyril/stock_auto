<?php

namespace App\Controller\Admin;

use App\Entity\Agenda;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AgendaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Agenda::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('typeGalerie', 'Type de pack'),
            TextField::new('titre', 'Titre'),
            TextareaField::new('details', 'Texte court / Résumé')
            ->setRequired(true)
            ->setFormTypeOption('attr', [
                'maxlength' => 1000, // Limite à 255 caractères
                'oninput' => "document.getElementById('details-count').textContent = this.value.length + '/100'"
            ])
            ->setHelp('<span id="details-count">0/100</span> caractères saisis'),

            ImageField::new('image', 'Image')
                ->setBasePath('/media/cache/resolve/thumbnail_800x570/uploads/agendas')
                ->hideOnForm(),

            BooleanField::new('publier', 'Publier'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Packs')    // Dans les formulaires
            ->setEntityLabelInPlural('Mes Packs')     // Dans les listes
            ->setPageTitle('index', 'Liste des Packs')
            ->setPageTitle('new', 'Créer un <b>Pack</b>')
            ->setPageTitle('edit', 'Modifier un <b>Pack</b>')
            ->setPageTitle('detail', 'Détail d\'un <b>Pack</b>');
    }
}
