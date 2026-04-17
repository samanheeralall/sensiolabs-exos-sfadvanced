<?php

namespace App\Serializer;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\ExtendsSerializationFor;
use Symfony\Component\Serializer\Attribute\Groups;

#[ExtendsSerializationFor(User::class)]
abstract class UserSerialization
{
    #[Groups(['conference:list'])]
    public ?int $id = null;

    #[Groups(['conference:list'])]
    public ?string $email = null;

    public array $roles = [];

    public ?string $password = null;

    public Collection $volunteerings;

    public Collection $organizations;
}
