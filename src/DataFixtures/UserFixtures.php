<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('admin@sensio-events.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
        ;
        // WARNING: DO NOT EVER USE THIS WITH A PLAIN UNENCODED PASSWORD IN PRODUCTION THIS WAY
        $password = $this->passwordHasherFactory->getPasswordHasher($user)->hash('admin');
        $user->setPassword($password);
        $manager->persist($user);
        $this->setReference('user_admin', $user);

        $user = (new User())
            ->setEmail('organizer@sensio-events.com')
            ->setRoles(['ROLE_USER', 'ROLE_ORGANIZER'])
        ;
        // WARNING: DO NOT EVER USE THIS WITH A PLAIN UNENCODED PASSWORD IN PRODUCTION THIS WAY
        $password = $this->passwordHasherFactory->getPasswordHasher($user)->hash('organizer');
        $user->setPassword($password);
        $manager->persist($user);
        $this->setReference('user_organizer', $user);

        $user = (new User())
            ->setEmail('user@sensio-events.com')
            ->setRoles(['ROLE_USER'])
        ;
        // WARNING: DO NOT EVER USE THIS WITH A PLAIN UNENCODED PASSWORD IN PRODUCTION THIS WAY
        $password = $this->passwordHasherFactory->getPasswordHasher($user)->hash('user');
        $user->setPassword($password);
        $manager->persist($user);
        $this->setReference('user_user', $user);

        $manager->flush();
    }
}
