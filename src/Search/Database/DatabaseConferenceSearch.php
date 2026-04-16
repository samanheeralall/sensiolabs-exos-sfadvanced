<?php

namespace App\Search\Database;

use App\Repository\ConferenceRepository;
use App\Search\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AutoconfigureTag('app.conference_search')]
readonly class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private ConferenceRepository $repository,
        #[Autowire(param: 'app.conference_limit')]
        private int $limit,
    ) {}

    public function searchByName(?string $name = null, ?int $page = null): array
    {
        $limit = null;
        $offset = null;

        if (\is_int($page)) {
            $limit = $this->limit;
            $page = ($page - 1) * $limit;
        }

        if (null === $name) {
            return $this->repository->findBy([], limit: $limit, offset: $offset);
        }

        return $this->repository->findLikeName($name, limit: $limit, offset: $offset);
    }
}
