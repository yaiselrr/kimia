<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Form\Model\TeamDto;
use App\Form\Type\PlayerFormType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Service\PlayerFormProcessor;
use App\Service\PlayerManager;
use App\Service\TeamManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends AbstractFOSRestController
{
    private $logger;
    private $playerRepo;
    private $playerManager;
    private $playerFormProcessor;

    public function __construct(
        LoggerInterface $logger,  
        PlayerRepository $playerRepo, 
        PlayerManager $playerManager, 
        PlayerFormProcessor $playerFormProcessor
        )
    {
        $this->logger = $logger;
        $this->playerRepo = $playerRepo;
        $this->playerManager = $playerManager;
        $this->playerFormProcessor = $playerFormProcessor;
    }
    /**
     * 
     * @Rest\Get(path="/players")
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function index()
    {
        $this->logger->info('list action callled');

        return $this->playerRepo->findAll();
    }

    /**
     * 
     * @Rest\Post(path="/players/new")
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function store(Request $request)
    {
        $player = $this->playerManager->findByOne($request->get('name'));

        if ($player) {
            return View::create('player already exists', Response::HTTP_BAD_REQUEST);
        }

        [$player, $error] = ($this->playerFormProcessor)($request);

        $statusCode = $player ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $player ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * 
     * @Rest\Get(path="/players/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function show(Player $player)
    {
        $player = $this->playerManager->find($player);
        
        if (!$player) {
            return View::create('player does not exists', Response::HTTP_BAD_REQUEST);
        }

        return $player;
    }

    /**
     * 
     * @Rest\Post(path="/players/{id}/edit", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function update(Request $request, Player $player)
    {
        $player = $this->playerManager->find($player);

        if (!$player) {
            return View::create('player does not exists', Response::HTTP_BAD_REQUEST);
        }

        [$player, $error] = ($this->playerFormProcessor)($request, $player);

        $statusCode = $player ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $player ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * 
     * @Rest\Post(path="/players/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function delete(Player $player)
    {
        $player = $this->playerManager->find($player);

        if (!$player) {
            return View::create('player does not exists', Response::HTTP_BAD_REQUEST);
        }

        $this->playerManager->remove($player);

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
