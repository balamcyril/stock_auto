<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductSearchController
{
    #[Route('/api/products/search', name: 'api_products_search', methods: ['GET'], priority: 10)]
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse {
        $rawQuery = trim((string) $request->query->get('q', ''));
        $limit = max(1, min(50, (int) $request->query->get('limit', 20)));

        if ($rawQuery === '') {
            throw new BadRequestHttpException('Le parametre "q" est obligatoire.');
        }

        $terms = preg_split('/\s+/', mb_strtolower($rawQuery), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($terms === []) {
            return new JsonResponse([
                'message' => 'Aucun mot-clé exploitable fourni.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $qb = $entityManager->createQueryBuilder()
            ->select('p', 'b', 'c', 'sc', 'w', 'pi')
            ->from(Product::class, 'p')
            ->leftJoin('p.brand', 'b')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.subCategory', 'sc')
            ->leftJoin('p.warehouse', 'w')
            ->leftJoin('p.images', 'pi')
            ->where('p.status = :active')
            ->setParameter('active', Product::STATUS_ACTIVE);

        $scoreParts = [];
        $termIndex = 0;
        foreach ($terms as $term) {
            $param = 'term' . $termIndex;
            $like = '%' . $term . '%';
            $scoreParts[] = "(CASE WHEN LOWER(p.sku) LIKE :$param THEN 8 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(p.barcode, '')) LIKE :$param THEN 7 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(p.oemReference, '')) LIKE :$param THEN 7 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(p.name) LIKE :$param THEN 10 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(p.description, '')) LIKE :$param THEN 5 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(b.name, '')) LIKE :$param THEN 6 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(c.name, '')) LIKE :$param THEN 4 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(sc.name, '')) LIKE :$param THEN 4 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(w.name, '')) LIKE :$param THEN 3 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(w.city, '')) LIKE :$param THEN 3 ELSE 0 END)";
            $scoreParts[] = "(CASE WHEN LOWER(COALESCE(p.shelfCode, '')) LIKE :$param THEN 2 ELSE 0 END)";

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(p.sku)', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(p.barcode, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(p.oemReference, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(p.name)', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(p.description, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(b.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(c.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(sc.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(w.name, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(w.city, \'\'))', ":$param"),
                    $qb->expr()->like('LOWER(COALESCE(p.shelfCode, \'\'))', ":$param")
                )
            );

            $qb->setParameter($param, $like);
            $termIndex++;
        }

        $qb->addSelect(sprintf('(%s) AS HIDDEN relevance', implode(' + ', $scoreParts)))
            ->orderBy('relevance', 'DESC')
            ->addOrderBy('p.updatedAt', 'DESC')
            ->setMaxResults($limit);

        $products = $qb->getQuery()->getResult();

        $json = $serializer->serialize($products, 'json', [
            'groups' => ['product:read'],
            'enable_max_depth' => true,
        ]);

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}
