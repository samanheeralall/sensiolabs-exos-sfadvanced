<?php

namespace App\Serializer;

use App\Entity\Conference;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\ExtendsSerializationFor;
use Symfony\Component\Serializer\Attribute\Groups;

#[ExtendsSerializationFor(Conference::class)]
abstract class ConferenceSerialization
{
    #[Groups(['conference:list', 'conference:show'])]
    public ?int $id = null;

    #[Groups(['conference:list', 'conference:show'])]
    public ?string $name = null;

    #[Groups(['conference:list', 'conference:show'])]
    public ?string $description = null;

    #[Groups(['conference:list', 'conference:show'])]
    public ?bool $accessible = null;

    #[Groups(['conference:show'])]
    public ?string $prerequisites = null;

    #[Groups(['conference:list', 'conference:show'])]
    public ?\DateTimeImmutable $startAt = null;

    #[Groups(['conference:list', 'conference:show'])]
    public ?\DateTimeImmutable $endAt = null;

    #[Groups(['conference:show'])]
    public Collection $volunteerings;

    #[Groups(['conference:list', 'conference:show'])]
    public Collection $organizations;

    #[Groups(['conference:list', 'conference:show'])]
    public ?User $createdBy = null;

}
