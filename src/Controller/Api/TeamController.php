<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Form\Model\TeamDto;
use App\Form\Type\TeamFormType;
use App\Repository\TeamRepository;
use App\Service\TeamFormProcessor;
use App\Service\TeamManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends AbstractFOSRestController
{
    private $logger;
    private $teamRepo;
    private $teamManager;
    private $teamFormProcessor;

    public function __construct(
        LoggerInterface $logger,
        TeamRepository $teamRepo,
        TeamManager $teamManager,
        TeamFormProcessor $teamFormProcessor
    ) {
        $this->logger = $logger;
        $this->teamRepo = $teamRepo;
        $this->teamManager = $teamManager;
        $this->teamFormProcessor = $teamFormProcessor;
    }
    /**
     * 
     * @Rest\Get(path="/teams")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function index()
    {
        $this->logger->info('list action callled');

        return $this->teamRepo->findAll();
    }

    /**
     * 
     * @Rest\Post(path="/teams/new")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function store(Request $request)
    {
        $team = $this->teamManager->findByOne($request->get('name'));

        if ($team) {
            return View::create('team already exists', Response::HTTP_BAD_REQUEST);
        }

        [$team, $error] = ($this->teamFormProcessor)($request);

        $statusCode = $team ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $team ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * 
     * @Rest\Get(path="/teams/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function show(Team $team)
    {
        $team = $this->teamManager->find($team->getId());

        if (!$team) {
            return View::create('team does not exists', Response::HTTP_BAD_REQUEST);
        }

        return $team;
    }

    /**
     * 
     * @Rest\Post(path="/teams/{id}/edit", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function update(Request $request, Team $team)
    {
        $team = $this->teamManager->find($team->getId());

        if (!$team) {
            return View::create('team does not exists', Response::HTTP_BAD_REQUEST);
        }

        [$team, $error] = ($this->teamFormProcessor)($request, $team);

        $statusCode = $team ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $team ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * 
     * @Rest\Post(path="/teams/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function delete(Team $team)
    {
        $team = $this->teamManager->find($team->getId());

        if (!$team) {
            return View::create('team does not exists', Response::HTTP_BAD_REQUEST);
        }

        $this->teamManager->remove($team);

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
