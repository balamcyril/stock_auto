<?php

namespace App\Controller\Admin;

use App\Entity\PieceJointe;
use App\Entity\Actualite;
use App\Entity\Contenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class PieceJointeCrudController extends AbstractCrudController
{
    private $doctrine;
    private $requestStack;

    public function __construct(ManagerRegistry $doctrine, RequestStack $requestStack)
    {
        $this->doctrine = $doctrine;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return PieceJointe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityId = $request->query->get('entityId');
        $entityType = $request->query->get('entityType');

        $fields = [
            FormField::addPanel('Informations de la pièce jointe'),
            TextField::new('fileName', 'Nom du fichier')->onlyOnIndex(),
            TextField::new('file')
                ->setFormType(VichFileType::class)
                ->setLabel('Télécharger le fichier')
                ->onlyOnForms(),
        ];

        // Ajouter le champ d'association en fonction du type d'entité
        if ($entityType === 'actualite') {
            $fields[] = AssociationField::new('actualite', 'Actualité liée')
                ->setRequired(true)
                ->setFormTypeOption('disabled', true)
                ->hideOnIndex();
        } elseif ($entityType === 'contenu') {
            $fields[] = AssociationField::new('contenu', 'Contenu lié')
                ->setRequired(true)
                ->setFormTypeOption('disabled', true)
                ->hideOnIndex();
        }

        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $pieceJointe = new PieceJointe();
        $request = $this->requestStack->getCurrentRequest();
        $entityId = $request->query->get('entityId');
        $entityType = $request->query->get('entityType');

        if ($entityId && $entityType) {
            if ($entityType === 'actualite') {
                $actualite = $this->doctrine->getRepository(Actualite::class)->find($entityId);
                if ($actualite) {
                    $pieceJointe->setActualite($actualite);
                }
            } elseif ($entityType === 'contenu') {
                $contenu = $this->doctrine->getRepository(Contenu::class)->find($entityId);
                if ($contenu) {
                    $pieceJointe->setContenu($contenu);
                }
            }
        }

        return $pieceJointe;
    }
}