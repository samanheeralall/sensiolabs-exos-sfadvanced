<?php

namespace App\Serializer;

use App\Entity\Organization;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\ExtendsSerializationFor;
use Symfony\Component\Serializer\Attribute\Groups;

#[ExtendsSerializationFor(Organization::class)]
abstract class OrganizationSerialization
{
    #[Groups(['conference:list', 'conference:show', 'organization:list'])]
    public ?int $id = null;

    #[Groups(['conference:list', 'conference:show', 'organization:list'])]
    public ?string $name = null;

    #[Groups(['conference:show', 'organization:list'])]
    public ?string $presentation = null;

    #[Groups(['conference:list', 'conference:show', 'organization:list'])]
    public ?\DateTimeImmutable $createdAt = null;

    #[Groups(['organization:list'])]
    public Collection $conferences;

    #[Groups(['organization:list'])]
    public Collection $users;

}
