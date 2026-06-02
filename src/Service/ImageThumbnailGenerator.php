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
        string $filterName = 'thumbnail_medium',
        string $uploadsDir = 'uploads/products'
    ): ?string {
        if (empty($imagePath)) {
            return null;
        }

        $fullImagePath = ltrim($uploadsDir.'/'.$imagePath, '/');

        try {
            return $this->cacheManager->getBrowserPath($fullImagePath, $filterName);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getOriginalImageUrl(
        ?string $imagePath,
        string $uploadsDir = 'uploads/products'
    ): ?string {
        if (empty($imagePath)) {
            return null;
        }

        return '/' . trim($uploadsDir, '/') . '/' . ltrim($imagePath, '/');
    }

    /**
     * Génère le chemin absolu du thumbnail (pour usage dans des emails par exemple)
     */
    public function generateAbsoluteThumbnailUrl(
        ?string $imagePath,
        string $filterName = 'thumbnail_medium',
        string $uploadsDir = 'uploads/products'
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
