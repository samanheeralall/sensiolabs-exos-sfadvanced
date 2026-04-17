<?php

namespace App\Search\Client\Persistence;

use App\Dto\ApiConference;
use App\Entity\Conference;
use App\Entity\User;
use App\Repository\ConferenceRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class ApiConferencePersister
{
    private bool $isOrganizerOrWebsite;

    private ?User $user;

    public function __construct(
        private readonly ConferenceRepository $conferenceRepository,
        private readonly OrganizationRepository $organizationRepository,
        private readonly EntityManagerInterface $manager,
    ) {
    }

    #[Required]
    public function setIsOrganizerOrWebsite(Security $security): void
    {
        $this->isOrganizerOrWebsite =
            $security->isGranted('ROLE_ORGANIZER')
            || $security->isGranted('ROLE_WEBSITE');
    }

    #[Required]
    public function setUser(Security $security): void
    {
        $user = $security->getUser();

        if ($user instanceof User) {
            $this->user = $user;
        }
    }

    public function findOrPersist(ApiConference $dto): Conference
    {
        $dto->setOrganizations($this->findOrCreateOrganizations($dto));

        $conference = $this->conferenceRepository->findOneBy([
            'name' => $dto->getName(),
            'startAt' => $dto->getStartAt(),
            'endAt' => $dto->getEndAt(),
        ]);

        if (null === $conference) {
            $conference = $dto->toEntity();
            $this->persistForAdmins($conference, true);
        }

        return $conference;
    }

    private function findOrCreateOrganizations(ApiConference $dto): array
    {
        $persistedOrgs = false;

        $organizations = $dto->getOrganizations();
        foreach ($organizations as $key => $dtoOrganization) {
            $organization = $this->organizationRepository->findOneBy([
                'name' => $dtoOrganization->getName(),
                'presentation' => $dtoOrganization->getPresentation(),
                'createdAt' => $dtoOrganization->getCreatedAt(),
            ]);

            if (null === $organization) {
                $organization = $dtoOrganization->toEntity();
                $this->persistForAdmins($organization);
                $persistedOrgs = true;
            }

            $organizations[$key] = $organization;
        }

        if ($persistedOrgs && $this->isOrganizerOrWebsite) {
            $this->manager->flush();
        }

        return $organizations;
    }

    private function persistForAdmins(object $entity, bool $flush = false): void
    {
        if ($this->isOrganizerOrWebsite) {
            if ($entity instanceof Conference) {
                $entity->setCreatedBy($this->user);
            }

            $this->manager->persist($entity);

            if ($flush) {
                $this->manager->flush();
            }
        }
    }
}
