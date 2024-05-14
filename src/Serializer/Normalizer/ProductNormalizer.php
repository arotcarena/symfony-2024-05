<?php

namespace App\Serializer\Normalizer;

use App\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @param Product[]
     */
    public function normalizeAll(array $products): array
    {
        $array = [];
        foreach($products as $product)
        {
            $array[] = $this->normalize($product);
        }

        return $array;
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'category' => $object->getCategory()->getName()
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Product;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Product::class => true];
    }
}
