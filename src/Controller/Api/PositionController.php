<?php

namespace App\Controller\Api;

use App\Repository\PositionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;

class PositionController extends AbstractFOSRestController
{
    private $logger;
    private $positionRepo;

    public function __construct(
        LoggerInterface $logger,
        PositionRepository $positionRepo
    ) {
        $this->logger = $logger;
        $this->positionRepo = $positionRepo;
    }
    /**
     * 
     * @Rest\Get(path="/positions")
     * @Rest\View(serializerGroups={"position"},serializerEnableMaxDepthChecks=true)
     */
    public function index()
    {
        $this->logger->info('list action callled');

        return $this->positionRepo->findAll();
    }
}
