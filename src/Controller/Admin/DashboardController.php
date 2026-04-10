<?php

namespace App\Controller\Admin;

use App\Entity\TypeContenu;
use App\Entity\Contenu;
use App\Entity\UnitePedagogique;
use App\Entity\Formation;
use App\Entity\UniteRecherche;
use App\Entity\Actualite;
use App\Entity\Agenda;
use App\Entity\TypeArtiste;
use App\Entity\Artiste;
use App\Entity\TypeGalerie;
use App\Entity\Galerie;
use App\Entity\Offre;
use App\Entity\Lien;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Récupérer les comptages pour chaque entité
        $typeContenuCount = $this->entityManager->getRepository(TypeContenu::class)->count([]);
        $contenuCount = $this->entityManager->getRepository(Contenu::class)->count([]);
        $unitePedagogiqueCount = $this->entityManager->getRepository(UnitePedagogique::class)->count([]);
        $formationCount = $this->entityManager->getRepository(Formation::class)->count([]);
        $uniteRechercheCount = $this->entityManager->getRepository(UniteRecherche::class)->count([]);
        $actualiteCount = $this->entityManager->getRepository(Actualite::class)->count([]);
        $agendaCount = $this->entityManager->getRepository(Agenda::class)->count([]);
        $typeArtisteCount = $this->entityManager->getRepository(TypeArtiste::class)->count([]);
        $artisteCount = $this->entityManager->getRepository(Artiste::class)->count([]);
        $typeGalerieCount = $this->entityManager->getRepository(TypeGalerie::class)->count([]);
        $galerieCount = $this->entityManager->getRepository(Galerie::class)->count([]);
        $offreCount = $this->entityManager->getRepository(Offre::class)->count([]);
        $lienCount = $this->entityManager->getRepository(Lien::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'typeContenuCount' => $typeContenuCount,
            'contenuCount' => $contenuCount,
            'unitePedagogiqueCount' => $unitePedagogiqueCount,
            'formationCount' => $formationCount,
            'uniteRechercheCount' => $uniteRechercheCount,
            'actualiteCount' => $actualiteCount,
            'agendaCount' => $agendaCount,
            'typeArtisteCount' => $typeArtisteCount,
            'artisteCount' => $artisteCount,
            'typeGalerieCount' => $typeGalerieCount,
            'galerieCount' => $galerieCount,
            'offreCount' => $offreCount,
            'lienCount' => $lienCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion du site - Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home');



        yield MenuItem::section('Blog & Actualités');
        yield MenuItem::linkToCrud('Actualités/Blog', 'fa fa-newspaper', Actualite::class);

        yield MenuItem::section('Jeux & Animations');
        yield MenuItem::linkToCrud('Categorie Jeux et Animations', 'fa fa-paint-brush', TypeArtiste::class);
        yield MenuItem::linkToCrud('Jeux et Animations', 'fa fa-gamepad', Artiste::class);

        yield MenuItem::section('Packs et bons plans');
        yield MenuItem::linkToCrud('Types / Catégories', 'fa fa-list', TypeGalerie::class);
        yield MenuItem::linkToCrud('Packs', 'fa fa-box', Agenda::class);
        yield MenuItem::linkToCrud('Bons Plans', 'fa fa-percent', Galerie::class);

        yield MenuItem::section('Gestion des Contenus');
        yield MenuItem::linkToCrud('Types de Contenu', 'fa fa-list', TypeContenu::class);
        yield MenuItem::linkToCrud('Contenus', 'fa fa-file-alt', Contenu::class);
        yield MenuItem::linkToCrud('Texte annexe', 'fa fa-link', Lien::class);
        

        yield MenuItem::section('Paramètres');
        if (!$this->getUser()) {
            yield MenuItem::linkToRoute('Connexion', 'fa fa-sign-in', 'admin_login');
        } else {
            yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
        }
    }
}
