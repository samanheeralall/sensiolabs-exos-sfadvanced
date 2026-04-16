<?php

namespace App\Search\Log;

use App\Search\ConferenceSearchInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('dev')]
#[AsDecorator(ConferenceSearchInterface::class, priority: 5)]
readonly class TraceableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private ConferenceSearchInterface $inner,
        private LoggerInterface $logger,
    ) {}

    public function searchByName(?string $name = null, ?int $page = null): array
    {
        if (\is_string($name)) {
            $this->logger->info("Search for conferences with specific name", [
                'name' => $name,
                'page' => $page,
            ]);
        }

        return $this->inner->searchByName($name, $page);
    }
}
