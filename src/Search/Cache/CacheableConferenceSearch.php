<?php

namespace App\Search\Cache;

use App\Search\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[When('prod')]
#[When('dev')]
#[AsDecorator(ConferenceSearchInterface::class, priority: 10)]
readonly class CacheableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private ConferenceSearchInterface $inner,
        private CacheInterface $cache,
        private SluggerInterface $slugger,
    ) {}

    public function searchByName(?string $name = null, ?int $page = null): array
    {
        $key = $this->slugger->slug(sprintf("%s-page-%d", $name, $page));

        return $this->cache->get($key, function(ItemInterface $item) use ($name, $page) {
            $item->expiresAfter(3600);

            return $item->set($this->inner->searchByName($name, $page))->get();
        });
    }
}
