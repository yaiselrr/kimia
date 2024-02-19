<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Form\Model\TeamDto;
use App\Form\Type\TeamFormType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends AbstractFOSRestController
{
    private $logger;
    private $em;
    private $repo;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, TeamRepository $repo) {
        $this->logger = $logger;
        $this->em = $em;
        $this->repo = $repo;
    }
    /**
     * 
     * @Rest\Get(path="/teams")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function index()
    {
        $this->logger->info('list action callled');

        return $this->repo->findAll();
    }

    /**
     * 
     * @Rest\Post(path="/teams/new")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function store(Request $request,)
    {
        $this->logger->info('create team action callled');

        $teamDto = new TeamDto();
        $form = $this->createForm(TeamFormType::class, $teamDto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = new Team();

            $team->setName($teamDto->name);
            $team->setColor($teamDto->color);

            $this->em->persist($team);
            $this->em->flush();

            return $team;
        }

        return $form;
    }

    /**
     * 
     * @Rest\Get(path="/teams/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function show(int $id)
    {
        $this->logger->info('get team action callled');

        $team = $this->repo->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team not found');
        }

        return $team;
    }

    /**
     * 
     * @Rest\Post(path="/teams/{id}/edit", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function update(Request $request, int $id)
    {
        $this->logger->info('update team action callled');

        $team = $this->repo->find($id);

        if (!$team) {
            throw $this->createNotFoundException('Team not found');
        }

        $teamDto = new TeamDto();

        $form = $this->createForm(TeamFormType::class, $teamDto);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) 
        {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        
        if ($form->isValid()) {
            $team->setName($teamDto->name);

            $this->em->persist($team);
            $this->em->flush();
            $this->em->refresh($team);

            return $team;
        }

        return $form;
    }
}
