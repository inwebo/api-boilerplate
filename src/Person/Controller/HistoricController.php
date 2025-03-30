<?php

declare(strict_types=1);

namespace App\Person\Controller;

use App\Person\Entity\Person;
use Inwebo\DoctrineEventSourcing\Model\Aggregator;
use Inwebo\DoctrineEventSourcing\Resolver\HistoricResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class HistoricController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Person $person): Response
    {
        $eventSourcing = Aggregator::new($person);
        $resolver = new HistoricResolver($eventSourcing);
        $serialized = $this->serializer->serialize((array) $resolver->resolve()->get(), 'json');

        return JsonResponse::fromJsonString($serialized);
    }
}
