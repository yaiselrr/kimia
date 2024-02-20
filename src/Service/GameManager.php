<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class GameManager
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

    public function randomArray($array): ?Array
    {
        $resultArray = [];

        foreach ($array as $value) {
            $resultArray[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'position' => $value->getPosition(),
                'team' => $value->getTeam()->getName(),
            ];
        }

        return $resultArray;
    }

    public function asignArray($array): ?Array
    {
        $playersArrayResult = [];
        $claves_aleatorias = array_rand($array, 10);

        for ($i=0; $i < count($claves_aleatorias); $i++) { 
            $playersArrayResult [] = $array[$claves_aleatorias[$i]];
        }

        return $playersArrayResult;
    }
}
