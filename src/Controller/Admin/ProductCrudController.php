<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use App\Service\ImageThumbnailGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ProductCrudController extends AbstractCrudController
{
    private ImageThumbnailGenerator $thumbnailGenerator;

    public function __construct(ImageThumbnailGenerator $thumbnailGenerator)
    {
        $this->thumbnailGenerator = $thumbnailGenerator;
    }
    public static function getEntityFqcn(): string { return Product::class; }

    public function createEntity(string $entityFqcn): Product
    {
        return (new Product())->initializeImageSlots();
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('sku', 'SKU'),
            TextField::new('barcode', 'Code-barres')->setRequired(false),
            TextField::new('oemReference', 'Reference OEM')->setRequired(false),
            TextField::new('name', 'Nom'),
            AssociationField::new('brand', 'Marque'),
            AssociationField::new('category', 'Catégorie'),
            AssociationField::new('subCategory', 'Sous-catégorie')->setRequired(false),
            TextareaField::new('description', 'Description')->setRequired(false),
            MoneyField::new('price', 'Prix')->setCurrency('EUR')->setStoredAsCents(false),
            IntegerField::new('quantity', 'Quantité'),
            NumberField::new('weightKg', 'Poids kg')->setRequired(false),
            ChoiceField::new('volumeSize', 'Volume')->setChoices(Product::VOLUME_SIZE_CHOICES),
            AssociationField::new('warehouse', 'Entrepôt')->setRequired(false),
            TextField::new('shelfCode', 'Emplacement')->setRequired(false),
            ChoiceField::new('status', 'Statut')->setChoices(Product::STATUS_CHOICES),
            CollectionField::new('images', 'Images')
                ->useEntryCrudForm(ProductImageCrudController::class)
                ->allowAdd(false)
                ->allowDelete(false)
                ->setFormTypeOptions(['by_reference' => false]),
            DateTimeField::new('createdAt', 'Création')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modification')->hideOnForm(),
        ];

        // Index: show first image as small thumbnail
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = TextField::new('imagePreview', 'Image plus')
                ->formatValue(function ($value, $product) {
                    $images = $product->getImages();
                    if (count($images) === 0) {
                        return '';
                    }
                    $first = null;
                    foreach ($images as $img) {
                        if ($img && $img->getImage()) { $first = $img; break; }
                    }
                    if (!$first) { return ''; }
                    $file = $first->getImage();
                    $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_small', 'uploads/products');
                    $rawUrl = '/uploads/products/' . ltrim($file, '/');
                    if (!$url) {
                        $url = $rawUrl;
                    }
                    return '<div style="display:inline-flex;align-items:flex-start;max-width:220px;">'
                        . '<img src="' . $url . '" style="max-height:60px;" alt="Miniature produit" />'
                        . '</div>';
                })
                ->renderAsHtml()
                ->onlyOnIndex();
        }

        // Detail: show images carousel with medium thumbnails
        if ($pageName === Crud::PAGE_DETAIL) {
            $fields[] = TextField::new('imagesCarousel', 'Images')
                ->formatValue(function ($value, $product) {
                    $images = $product->getImages();
                    $id = $product->getId() ?? spl_object_id($product);
                    if (count($images) === 0) { return ''; }
                    $html = '<div id="carousel_' . $id . '" class="carousel slide" data-bs-ride="carousel"><div class="carousel-inner">';
                    $i = 0;
                    foreach ($images as $img) {
                        if (!$img || !$img->getImage()) { continue; }
                        $active = $i === 0 ? ' active' : '';
                        $file = $img->getImage();
                        $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/products') ?: ('/media/cache/resolve/thumbnail_medium/uploads/products/' . $file);
                        $html .= '<div class="carousel-item' . $active . '">'
                            . '<img src="' . $url . '" class="d-block w-100" alt="Produit" />'
                            . '<div style="font-size:0.85em;word-break:break-all;margin-top:8px;"><a href="' . $url . '" target="_blank">' . $url . '</a></div>'
                            . '</div>';
                        $i++;
                    }
                    $html .= '</div>';
                    if ($i > 1) {
                        $html .= '<button class="carousel-control-prev" type="button" data-bs-target="#carousel_' . $id . '" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>';
                        $html .= '<button class="carousel-control-next" type="button" data-bs-target="#carousel_' . $id . '" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->renderAsHtml()
                ->onlyOnDetail();
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Pièce')->setEntityLabelInPlural('Pièces');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
