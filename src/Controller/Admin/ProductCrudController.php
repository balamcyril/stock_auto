<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Service\ImageThumbnailGenerator;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public function __construct(private ImageThumbnailGenerator $thumbnailGenerator)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function createEntity(string $entityFqcn): Product
    {
        return (new Product())->initializeImageSlots();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $query = trim((string) $searchDto->getQuery());

        if ($query === '') {
            return $qb;
        }

        $terms = preg_split('/\s+/', mb_strtolower($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($terms === []) {
            return $qb;
        }

        $qb
            ->leftJoin('entity.brand', 'b')
            ->leftJoin('entity.category', 'c')
            ->leftJoin('entity.subCategory', 'sc')
            ->leftJoin('entity.warehouse', 'w');

        $scoreParts = [];
        $index = 0;

        foreach ($terms as $term) {
            $param = 'searchTerm' . $index;
            $like = '%' . $term . '%';
            $scoreParts[] = "(CASE WHEN LOWER(entity.sku) LIKE :$param THEN 8 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(entity.barcode, '')) LIKE :$param THEN 7 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(entity.oemReference, '')) LIKE :$param THEN 7 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(entity.name) LIKE :$param THEN 10 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(entity.description, '')) LIKE :$param THEN 5 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(b.name, '')) LIKE :$param THEN 6 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(c.name, '')) LIKE :$param THEN 4 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(sc.name, '')) LIKE :$param THEN 4 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(w.name, '')) LIKE :$param THEN 3 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(w.city, '')) LIKE :$param THEN 3 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(entity.shelfCode, '')) LIKE :$param THEN 2 ELSE 0 END)";

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(entity.sku)', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(entity.barcode, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(entity.oemReference, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(entity.name)', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(entity.description, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(b.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(c.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(sc.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(w.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(w.city, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(entity.shelfCode, \'\'))', ":$param")
                )
            );

            $qb->setParameter($param, $like);
            $index++;
        }

        $qb->addSelect(sprintf('(%s) AS HIDDEN relevance', implode(' + ', $scoreParts)))
            ->addOrderBy('relevance', 'DESC');

        return $qb;
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
            AssociationField::new('category', 'Categorie'),
            AssociationField::new('subCategory', 'Sous-categorie')->setRequired(false),
            TextareaField::new('description', 'Description')->setRequired(false),
            MoneyField::new('price', 'Prix')->setCurrency('EUR')->setStoredAsCents(false),
            IntegerField::new('quantity', 'Quantite'),
            NumberField::new('weightKg', 'Poids kg')->setRequired(false),
            ChoiceField::new('volumeSize', 'Volume')->setChoices(Product::VOLUME_SIZE_CHOICES),
            AssociationField::new('warehouse', 'Entrepot')->setRequired(false),
            TextField::new('shelfCode', 'Emplacement')->setRequired(false),
            ChoiceField::new('status', 'Statut')->setChoices(Product::STATUS_CHOICES),
            CollectionField::new('images', 'Images')
                ->useEntryCrudForm(ProductImageCrudController::class)
                ->allowAdd(false)
                ->allowDelete(false)
                ->setFormTypeOptions(['by_reference' => false]),
            DateTimeField::new('createdAt', 'Creation')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modification')->hideOnForm(),
        ];

        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = TextField::new('imagePreview', 'Image principale')
                ->formatValue(function ($value, $product) {
                    $images = $product->getImages();
                    if (count($images) === 0) {
                        return '';
                    }
                    $first = null;
                    foreach ($images as $img) {
                        if ($img && $img->getImage()) {
                            $first = $img;
                            break;
                        }
                    }
                    if (!$first) {
                        return '';
                    }
                    $file = $first->getImage();
                    $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_small', 'uploads/products');
                    $rawUrl = '/uploads/products/' . ltrim($file, '/');
                    $url = $url ?: $rawUrl;

                    return '<div style="display:inline-flex;align-items:flex-start;max-width:220px;"><img src="' . $url . '" style="max-height:60px;" alt="Miniature produit" /></div>';
                })
                ->renderAsHtml()
                ->onlyOnIndex();
        }

        if ($pageName === Crud::PAGE_DETAIL) {
            $fields[] = TextField::new('imagesCarousel', 'Images')
                ->formatValue(function ($value, $product) {
                    $images = $product->getImages();
                    $id = $product->getId() ?? spl_object_id($product);
                    if (count($images) === 0) {
                        return '';
                    }
                    $html = '<div id="carousel_' . $id . '" class="carousel slide" data-bs-ride="carousel"><div class="carousel-inner">';
                    $i = 0;
                    foreach ($images as $img) {
                        if (!$img || !$img->getImage()) {
                            continue;
                        }
                        $active = $i === 0 ? ' active' : '';
                        $file = $img->getImage();
                        $url = $this->thumbnailGenerator->generateThumbnailUrl($file, 'thumbnail_medium', 'uploads/products') ?: ('/media/cache/resolve/thumbnail_medium/uploads/products/' . $file);
                        $html .= '<div class="carousel-item' . $active . '"><img src="' . $url . '" class="d-block w-100" alt="Produit" /><div style="font-size:0.85em;word-break:break-all;margin-top:8px;"><a href="' . $url . '" target="_blank">' . $url . '</a></div></div>';
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
        return $crud
            ->setEntityLabelInSingular('Pièce')
            ->setEntityLabelInPlural('Pièces')
            ->setPaginatorPageSize(20)
            ->overrideTemplate('crud/index', 'admin/product_index.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
