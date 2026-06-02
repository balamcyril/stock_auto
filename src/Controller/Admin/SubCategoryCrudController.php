<?php

namespace App\Controller\Admin;

use App\Entity\SubCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Service\ImageThumbnailGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SubCategoryCrudController extends AbstractCrudController
{
    private ImageThumbnailGenerator $thumbnailGenerator;

    public function __construct(ImageThumbnailGenerator $thumbnailGenerator)
    {
        $this->thumbnailGenerator = $thumbnailGenerator;
    }
    public static function getEntityFqcn(): string { return SubCategory::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            AssociationField::new('category', 'Catégorie'),
            Field::new('imageFile', 'Image')->setFormType(VichFileType::class)->onlyOnForms(),
            TextField::new('imagePreview', 'Image')->formatValue(function ($value, $subCategory) {
                $file = $subCategory->getImage();
                if (empty($file)) { return ''; }
                $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_small', 'uploads/sub_categories') ?: ('/media/cache/resolve/thumbnail_small/uploads/sub_categories/' . $file);
                return '<a href="' . ($this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/sub_categories') ?: ('/media/cache/resolve/thumbnail_medium/uploads/sub_categories/' . $file)) . '" target="_blank"><img src="' . $url . '" style="max-height:60px;" /></a>';
            })->renderAsHtml()->onlyOnIndex(),
            TextField::new('imageDetail', 'Image')->formatValue(function ($value, $subCategory) {
                $file = $subCategory->getImage();
                if (empty($file)) { return ''; }
                $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/sub_categories') ?: ('/media/cache/resolve/thumbnail_medium/uploads/sub_categories/' . $file);
                return '<img src="' . $url . '" class="img-fluid" />';
            })->renderAsHtml()->onlyOnDetail(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Sous-catégorie')->setEntityLabelInPlural('Sous-catégories');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
