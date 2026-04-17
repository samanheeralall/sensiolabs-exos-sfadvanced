<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class ConferenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in(__DIR__.'/data/')
            ->name('conferences.json')
        ;

        foreach ($finder as $file) {
            $data = json_decode($file->getContents(), true, flags: JSON_THROW_ON_ERROR);

            foreach ($data as $row) {
                /** @var Organization $organization */
                try {
                    $organization = $this->getReference(
                        OrganizationFixtures::getReferenceKey($row['organization']['name']),
                        Organization::class
                    );
                } catch (\OutOfBoundsException $e) {
                    $organization = null;
                }

                $manager->persist($this->createConference($row, $organization));
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrganizationFixtures::class,
            UserFixtures::class,
        ];
    }

    private function createConference(array $row, ?Organization $organization): Conference
    {
        $conference = (new Conference())
            ->setName($row['name'])
            ->setDescription($row['description'])
            ->setAccessible($row['accessible'])
            ->setStartAt((new \DateTimeImmutable($row['startAt']))->setTime(0, 0, 0))
            ->setEndAt((new \DateTimeImmutable($row['endAt']))->setTime(23, 59, 59))
            ->setCreatedBy($this->getReference('user_organizer', User::class))
        ;

        if ($organization instanceof Organization) {
            $conference->addOrganization($organization);
        }

        if (isset($row['prerequisites'])) {
            $conference->setPrerequisites($row['prerequisites']);
        }

        return $conference;
    }
}
