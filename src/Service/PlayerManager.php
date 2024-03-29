<?php

namespace App\Service;

use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Form\Model\PlayerFindDto;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerManager
{
    private $logger;
    private $em;
    private $teamRepo;
    private $playerRepo;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, TeamRepository $teamRepo, PlayerRepository $playerRepo)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->teamRepo = $teamRepo;
        $this->playerRepo = $playerRepo;
    }

    public function findAll(): ?Array
    {
        $this->logger->info('Get all players action callled');

        $players = $this->playerRepo->findAll();
        $playesArray = [];

        foreach ($players as $value) {
            $playesArray[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'position' => $value->getPosition(),
                'team' => $value->getTeam()->getName(),
            ];
        }

        return $playesArray;
    }

    public function findShow(Player $player)
    {
        $this->logger->info('Get player action callled');

        $playerFind = $this->playerRepo->find($player);

        $playerDto = new PlayerFindDto;
        $playerDto->name = $playerFind->getName();
        $playerDto->position = $playerFind->getPosition();
        $playerDto->team = $playerFind->getTeam()->getName();
        $playerDto->idTeam = $playerFind->getTeam()->getId();
        
        return $playerDto;

    }

    public function find(Player $player): Player
    {
        $this->logger->info('Get player action callled');

        $playerFind = $this->playerRepo->find($player);
        
        return $playerFind;

    }

    public function findByOne(string $name): ?Player
    {
        $this->logger->info('Get player name action callled');

        return $this->playerRepo->findOneBy(['name' => $name]);
    }

    public function createDto(): PlayerDto
    {
        $this->logger->info('Create player objectDto action callled');

        $playerDto = new PlayerDto();

        return $playerDto;
    }

    public function create(): Player
    {
        $this->logger->info('Create player object action callled');

        $player = new Player();

        return $player;
    }

    public function save(Player $player): Player
    {
        $this->logger->info('Save player data action callled');

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    public function saveAndUpdate(Player $player): Player
    {
        $this->logger->info('Update player action callled');

        $this->em->persist($player);
        $this->em->flush();
        $this->em->refresh($player);

        return $player;
    }

    public function json($data): JsonResponse
    {
        $response = new JsonResponse();

        $response->setData([
            'success' => false,
            'error' => 'player already exists',
            'data' => [
                'id' => $data->getId(),
                'name' => $data->getName(),
                'position' => $data->getPosition(),
                'team' => $data->getTeam(),
            ]
        ]);

        return $response;
    }

    public function remove(Player $player): Player
    {
        $playerOut = $this->find($player);

        $this->em->remove($player);
        $this->em->flush();

        return $playerOut;
    }
}
