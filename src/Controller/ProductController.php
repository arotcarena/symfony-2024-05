<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use App\Serializer\Normalizer\ProductNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
      private ProductRepository $productRepository,
      private ProductNormalizer $productNormalizer
    )
    {
      
    }

    #[Route('/products', name: 'product_index')]
    public function index(): JsonResponse
    {
        $products = $this->productRepository->findAll();

        return $this->json(
          $this->productNormalizer->normalizeAll($products)
        );
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        return $this->json(
          $this->productNormalizer->normalize($product)
        );
    }
}