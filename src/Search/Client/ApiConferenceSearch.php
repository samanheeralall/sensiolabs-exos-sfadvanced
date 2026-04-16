<?php

namespace App\Search\Client;

use App\Search\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias]
#[AutoconfigureTag('app.conference_search')]
readonly class ApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        #[Target('conf.client')] private HttpClientInterface $client,
    ) {}

    public function searchByName(?string $name = null, ?int $page = null): array
    {
        $query = [];

        if (\is_string($name)) {
            $query['name'] = $name;
        }

        if (\is_int($page)) {
            $query['page'] = $page;
        }

        return $this->client->request('GET', '/events', [
            'query' => $query,
        ])->toArray();
    }
}
