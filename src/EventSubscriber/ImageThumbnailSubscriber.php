<?php
namespace App\EventSubscriber;

use App\Entity\ProductImage;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Service\ImageThumbnailGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ImageThumbnailSubscriber implements EventSubscriber
{
    public function __construct(private ImageThumbnailGenerator $thumbnailGenerator)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            'postLoad',
        ];
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProductImage) {
            $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_small', 'uploads/products');
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_medium', 'uploads/products')
            );
        }

        if ($entity instanceof Brand) {
            $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_small', 'uploads/brands');
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_medium', 'uploads/brands')
            );
        }

        if ($entity instanceof Category) {
            $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_small', 'uploads/categories');
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_medium', 'uploads/categories')
            );
        }

        if ($entity instanceof SubCategory) {
            $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_small', 'uploads/sub_categories');
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(), 'thumbnail_medium', 'uploads/sub_categories')
            );
        }
    }
}
