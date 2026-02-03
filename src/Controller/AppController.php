<?php

namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/')]
class AppController
{
    public function __invoke()
    {
        $metadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $serializer = new Serializer(
            [
                new ArrayDenormalizer(),
                new DateTimeNormalizer(
                    [
                        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s',
                        DateTimeNormalizer::TIMEZONE_KEY => 'Europe/Zurich', // date_default_timezone_get(),
                    ]
                ),
                new ObjectNormalizer(
                    $metadataFactory,
                    new MetadataAwareNameConverter($metadataFactory),
                    PropertyAccess::createPropertyAccessor(), //PropertyAccess::createPropertyAccessorBuilder()->enableMagicSet()->getPropertyAccessor(),
                    new PropertyInfoExtractor(
                        [
                            new ReflectionExtractor(),
                        ],
                        [
                            new PhpDocExtractor(),
                            new ReflectionExtractor(),
                        ],
                    ),
                    new ClassDiscriminatorFromClassMetadata($metadataFactory),
                    defaultContext: [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true]
                ),
            ],
        );

        $payload = ['param1' => 'test'];
        $dto = $serializer->denormalize($payload, GenericDto::class, context: [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true]);
        assert($dto instanceof TopicDto);

        // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        $dto->setTopicInfo('name', 'key', 5);
        dd($dto);
    }
}
