<?php
namespace App\Service;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ImageThumbnailGenerator
{
    public function __construct(
        private CacheManager $cacheManager,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * Génère l'URL d'un thumbnail
     */
    public function generateThumbnailUrl(
        ?string $imagePath, 
        string $filterName = 'thumbnail_800x570',
        string $uploadsDir = 'uploads/actualites'
    ): ?string {
        if (empty($imagePath)) {
            return null;
        }

        // Chemin relatif vers l'image originale
        $fullImagePath = $uploadsDir.'/'.$imagePath;

        // Génère le thumbnail via LiipImagine
        return $this->cacheManager->generateUrl($fullImagePath, $filterName);
    }

    /**
     * Génère le chemin absolu du thumbnail (pour usage dans des emails par exemple)
     */
    public function generateAbsoluteThumbnailUrl(
        ?string $imagePath,
        string $filterName = 'thumbnail_800x570',
        string $uploadsDir = 'uploads/actualites'
    ): ?string {
        if (empty($imagePath)) {
            return null;
        }

        $relativePath = $uploadsDir.'/'.$imagePath;
        $filteredPath = $this->cacheManager->getBrowserPath($relativePath, $filterName);

        return $this->urlGenerator->generate(
            'app_home', 
            [], 
            UrlGeneratorInterface::ABSOLUTE_URL
        ).ltrim($filteredPath, '/');
    }
}