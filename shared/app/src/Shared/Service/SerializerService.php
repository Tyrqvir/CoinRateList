<?php

declare(strict_types=1);

namespace App\Shared\Service;

use JMS\Serializer\SerializationContext;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class SerializerService
{

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function normalize($data): array
    {
        $context = SerializationContext::create()
            ->setSerializeNull(true);

        return $this->serializer->serializer();
    }
}