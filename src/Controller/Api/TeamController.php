<?php

namespace App\Controller\Api;

use App\Repository\TeamRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends AbstractFOSRestController
{
    /**
     * 
     * @Rest\Get(path="/teams")
     * @Rest\View(serializerGroups={"team"},serializerEnableMaxDepthChecks=true)
     */
    public function getActions(TeamRepository $repo)
    {
        return $repo->findAll();
    }
}