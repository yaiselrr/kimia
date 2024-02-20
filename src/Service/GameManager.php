<?php

namespace App\Service;

use App\Form\Model\GameDto;
use Psr\Log\LoggerInterface;

class GameManager
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function randomArray($array): ?Array
    {
        $this->logger->info('randomArray action callled');

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
        $this->logger->info('asignArray action callled');

        $playersArrayResult = [];
        $claves_aleatorias = array_rand($array, 10);

        for ($i=0; $i < count($claves_aleatorias); $i++) { 
            $playersArrayResult [] = $array[$claves_aleatorias[$i]];
        }

        return $playersArrayResult;
    }

    public function createDto(): GameDto
    {
        $this->logger->info('Create game objectDto action callled');

        $gameDto = new GameDto();

        return $gameDto;
    }
}
