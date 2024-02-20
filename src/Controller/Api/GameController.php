<?php

namespace App\Controller\Api;

use App\Repository\PlayerRepository;
use App\Service\GameManager;
use App\Service\PlayerFormProcessor;
use App\Service\PlayerManager;
use App\Service\TeamManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractFOSRestController
{
    private $logger;
    private $playerRepo;
    private $gameManager;

    public function __construct(
        LoggerInterface $logger,
        PlayerRepository $playerRepo,
        GameManager $gameManager,
    ) {
        $this->logger = $logger;
        $this->playerRepo = $playerRepo;
        $this->gameManager = $gameManager;
    }
    /**
     * 
     * @Rest\Get(path="/game/ajax-random-players")
     */
    public function jsonPlayers()
    {
        $this->logger->info('Load templates action callled');

        $playersArrayResult = [];
        $players = $this->playerRepo->findAll();   
        $playersArray =  $this->gameManager->randomArray($players);
        $playersArrayResult = $this->gameManager->asignArray($playersArray);

        $statusCode = $playersArrayResult ? Response::HTTP_ACCEPTED : Response::HTTP_BAD_REQUEST;
        $data = $playersArrayResult;

        return View::create($data, $statusCode);
    }
}
