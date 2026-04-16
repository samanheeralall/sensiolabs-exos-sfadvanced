<?php

namespace App\Search\Database;

use App\Repository\ConferenceRepository;

class DatabaseConferenceSearch
{
    public function __construct(
        private readonly ConferenceRepository $repository,
        private readonly int $limit,
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
