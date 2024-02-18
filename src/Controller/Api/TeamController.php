<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Form\Type\TeamFormType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends AbstractFOSRestController
{
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
    /**
     * 
     * @Rest\Get(path="/teams")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function index(TeamRepository $repo)
    {
        $this->logger->info('list action callled');

        return $repo->findAll();
    }

    /**
     * 
     * @Rest\Post(path="/teams/new")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function store(Request $request, EntityManagerInterface $em)
    {
        $this->logger->info('create action callled');

        $team = new Team();
        $form = $this->createForm(TeamFormType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($team);
            $em->flush();

            return $team;
        }

        return $form;
    }
}
