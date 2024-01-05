<?php

namespace App\Serializer\Normalizer;

use App\Repository\ComposerRepository;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SymfonyNormalizer extends ObjectNormalizer
{
    public function __construct(
        private ComposerRepository $repo,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    )
    {
        $this->repo = $repo;
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = parent::normalize($object, $format, $context);

        // TODO: add, edit, or delete some data
        $data['composerId'] = $object->getComposer() ? $object->getComposer()->getId() : null;

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \App\Entity\Symfony;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $symfony = parent::denormalize($data, $type, $format, $context);
        if (empty($data['composerId'])) {
            return $symfony;
        }

        $composer = $this->repo->find($data['composerId']);
        if ($composer) {
            $symfony->setComposer($composer);
        }

        return $symfony;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type == \App\Entity\Symfony::class;
    }
}
