<?php
namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Product;
use App\Repository\AdminRepository;
use App\Repository\ProductRepository;
use App\Serializer\Normalizer\ProductNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
          'products' => $products
        ]);
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
          'product' => $product
        ]);
    }
}