<?php

namespace App\Service;

use App\Entity\Team;
use App\Form\Model\TeamDto;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeamManager
{
    private $logger;
    private $em;
    private $teamRepo;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, TeamRepository $teamRepo)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->teamRepo = $teamRepo;
    }

    public function find(Team $team): ?Team
    {
        $this->logger->info('Get team action callled');

        return $this->teamRepo->find($team->getId());
    }

    public function findByOne(string $name): ?Team
    {
        $this->logger->info('Get team name action callled');

        return $this->teamRepo->findOneBy(['name' => $name]);
    }

    public function createDto(): TeamDto
    {
        $this->logger->info('Create team objectDto action callled');

        $teamDto = new TeamDto();

        return $teamDto;
    }

    public function create(): Team
    {
        $this->logger->info('Create team object action callled');

        $team = new Team();

        return $team;
    }

    public function save(Team $team): Team
    {
        $this->logger->info('Save team data action callled');

        $this->em->persist($team);
        $this->em->flush();

        return $team;
    }

    public function saveAndUpdate(Team $team): Team
    {
        $this->logger->info('Update team action callled');

        $this->em->persist($team);
        $this->em->flush();
        $this->em->refresh($team);

        return $team;
    }

    public function json($data): JsonResponse
    {
        $response = new JsonResponse();

        $response->setData([
            'success' => false,
            'error' => 'team already exists',
            'data' => [
                'id' => $data->getId(),
                'name' => $data->getName(),
                'position' => $data->getPosition(),
                'team' => $data->getTeam(),
            ]
        ]);

        return $response;
    }

    public function remove(Team $team): Team
    {
        $teamOut = $this->find($team);

        $this->em->remove($team);
        $this->em->flush();

        return $teamOut;
    }
}
