<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Service\ImageThumbnailGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProductImageCrudController extends AbstractCrudController
{
    private ImageThumbnailGenerator $thumbnailGenerator;

    public function __construct(ImageThumbnailGenerator $thumbnailGenerator)
    {
        $this->thumbnailGenerator = $thumbnailGenerator;
    }
    public static function getEntityFqcn(): string { return ProductImage::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            Field::new('imageFile', 'Image')->setFormType(VichFileType::class)->onlyOnForms(),
            TextField::new('imagePreview', 'Image')->formatValue(function ($value, $img) {
                $file = $img->getImage();
                if (empty($file)) { return ''; }
                $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_small', 'uploads/products') ?: ('/media/cache/resolve/thumbnail_small/uploads/products/' . $file);
                return '<a href="' . ($this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/products') ?: ('/media/cache/resolve/thumbnail_medium/uploads/products/' . $file)) . '" target="_blank"><img src="' . $url . '" style="max-height:60px;" /></a>';
            })->renderAsHtml()->onlyOnIndex(),
            TextField::new('imageDetail', 'Image')->formatValue(function ($value, $img) {
                $file = $img->getImage();
                if (empty($file)) { return ''; }
                $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/products') ?: ('/media/cache/resolve/thumbnail_medium/uploads/products/' . $file);
                return '<img src="' . $url . '" class="img-fluid" />';
            })->renderAsHtml()->onlyOnDetail(),
            BooleanField::new('isPrimary', 'Image principale'),
            IntegerField::new('sortOrder', 'Ordre'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Image produit')->setEntityLabelInPlural('Images produits');
    }
}
