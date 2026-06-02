<?php

namespace App\Command;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class GenerateLiipCacheCommand extends Command
{
    protected static $defaultName = 'app:generate-liip-cache';

    public function __construct(private CacheManager $cacheManager, private KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Génère les caches LiipImagine pour les dossiers d\'uploads (small & medium).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = $this->kernel->getProjectDir();

        $uploadDirs = [
            'products' => 'uploads/products',
            'brands' => 'uploads/brands',
            'categories' => 'uploads/categories',
            'sub_categories' => 'uploads/sub_categories',
        ];

        $filters = ['thumbnail_small', 'thumbnail_medium'];

        foreach ($uploadDirs as $dirName => $relative) {
            $path = $projectDir . '/public/' . $relative;
            if (!is_dir($path)) {
                $io->writeln("Skip: $path not found");
                continue;
            }

            $io->section("Processing $relative");
            $finder = new Finder();
            $finder->files()->in($path);
            $count = 0;
            foreach ($finder as $file) {
                $relPath = $relative . '/' . $file->getFilename();
                foreach ($filters as $filter) {
                    try {
                        $this->cacheManager->resolve($relPath, $filter);
                    } catch (\Throwable $e) {
                        $io->error('Error generating ' . $relPath . ' ' . $filter . ': ' . $e->getMessage());
                    }
                }
                $count++;
            }
            $io->writeln("Generated for $count files in $relative");
        }

        $io->success('Liip cache generation finished.');
        return Command::SUCCESS;
    }
}
