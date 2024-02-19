<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Form\Model\TeamDto;
use App\Form\Type\PlayerFormType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends AbstractFOSRestController
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
        $this->logger->info('create action callled');

        $team = $this->teamRepo->find((int)$request->get('team'));

        if (!$team) {
            throw $this->createNotFoundException('Team not found');
        }

        $playerDto = new PlayerDto();

        $form = $this->createForm(PlayerFormType::class, $playerDto);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            $player = new Player();

            $player->setName($playerDto->name);
            $player->setPosition($playerDto->position);
            $player->setTeam($team);

            $this->em->persist($player);
            $this->em->flush();

            return $player;
        }

        return $form;
    }

    /**
     * 
     * @Rest\Get(path="/players/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function show(int $id)
    {
        $this->logger->info('get player action callled');

        $player = $this->playerRepo->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }

        return $player;
    }

    /**
     * 
     * @Rest\Post(path="/players/{id}/edit", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"},serializerEnableMaxDepthChecks=true)
     */
    public function update(Request $request, int $id)
    {
        $this->logger->info('update action callled');

        $player = $this->playerRepo->find($id);

        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }

        $team = $this->teamRepo->find((int)$request->get('team'));

        if (!$team) {
            throw $this->createNotFoundException('Team not found');
        }

        $playerDto = new PlayerDto();

        $form = $this->createForm(PlayerFormType::class, $playerDto);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        
        if ($form->isValid()) {
            $player->setName($playerDto->name);
            $player->setPosition($playerDto->position);
            $player->setTeam($team);

            $this->em->persist($player);
            $this->em->flush();
            $this->em->refresh($player);

            return $player;
        }

        return $form;
    }
}
