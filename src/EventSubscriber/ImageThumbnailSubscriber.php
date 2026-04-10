<?php
namespace App\EventSubscriber;

use App\Service\ImageThumbnailGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Actualite;
use App\Entity\Agenda;
use App\Entity\Artiste;
use App\Entity\Contenu;
use App\Entity\Formation;
use App\Entity\Galerie;
use App\Entity\UnitePedagogique;
use App\Entity\UniteRecherche;

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

        if ($entity instanceof Actualite) {
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage())
            );
        }
        if ($entity instanceof Agenda) {
            $entity->setImageThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage(),'thumbnail_800x570','uploads/agendas')
            );
        }

        if ($entity instanceof UnitePedagogique) {
            $entity->setImage1Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage1(),'thumbnail_800x570','uploads/unite_pedagogiques')
            );

            $entity->setImage2Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage2(),'thumbnail_800x570','uploads/unite_pedagogiques')
            );
        }

        if ($entity instanceof UniteRecherche) {
            $entity->setImage1Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage1(),'thumbnail_800x570','uploads/unite_recherche')
            );

            $entity->setImage2Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage2(),'thumbnail_800x570','uploads/unite_recherche')
            );
        }

        if ($entity instanceof Formation) {
            $entity->setImage1Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage1(),'thumbnail_800x570','uploads/formations')
            );

            $entity->setImage2Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage2(),'thumbnail_800x570','uploads/formations')
            );
        }

        if ($entity instanceof Contenu) {
            $entity->setImage1Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage1(),'thumbnail_800x570','uploads/contenus')
            );

            $entity->setImage2Thumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getImage2(),'thumbnail_800x570','uploads/contenus')
            );
        }

        if ($entity instanceof Artiste) {
            $entity->setPhotoThumbnail1(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getPhoto1(),'thumbnail_800x570','uploads/artistes')
            );
            $entity->setPhotoThumbnail2(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getPhoto2(),'thumbnail_800x570','uploads/artistes')
            );
            $entity->setPhotoThumbnail3(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getPhoto3(),'thumbnail_800x570','uploads/artistes')
            );
            $entity->setPhotoThumbnail4(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getPhoto4(),'thumbnail_800x570','uploads/artistes')
            );
            $entity->setPhotoThumbnail5(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getPhoto5(),'thumbnail_800x570','uploads/artistes')
            );
        }

        if ($entity instanceof Galerie) {
            $entity->setMediaThumbnail(
                $this->thumbnailGenerator->generateThumbnailUrl($entity->getMedia(),'thumbnail_800x570','uploads/galeries')
            );
        }
        
        
    }
}