<?php

namespace App\Controller\Api;

use App\Repository\PlayerRepository;
use App\Service\GameFormProcessor;
use App\Service\GameManager;
use App\Service\PlayerFormProcessor;
use App\Service\PlayerManager;
use App\Service\TeamManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractFOSRestController
{
    private $logger;
    private $playerRepo;
    private $gameManager;
    private $playerManager;
    private $gameFormProcessor;

    public function __construct(
        LoggerInterface $logger,
        PlayerRepository $playerRepo,
        GameManager $gameManager,
        PlayerManager $playerManager,
        GameFormProcessor $gameFormProcessor
    ) {
        $this->logger = $logger;
        $this->playerRepo = $playerRepo;
        $this->gameManager = $gameManager;
        $this->playerManager = $playerManager;
        $this->gameFormProcessor = $gameFormProcessor;
    }

    /**
     * 
     * @Rest\Post(path="/game")
     */
    public function game(Request $request)
    {
        $player = $this->playerManager->findByOne($request->get('currentPlayer'));

        if (!$player) {
            return View::create('player does not exists', Response::HTTP_BAD_REQUEST);
        }        

        [$response, $error] = ($this->gameFormProcessor)($request, $player);

        $statusCode = $response ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $response ?? $error;

        return View::create($data, $statusCode);
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

        $statusCode = $playersArrayResult ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $playersArrayResult;

        return View::create($data, $statusCode);
    }
}
