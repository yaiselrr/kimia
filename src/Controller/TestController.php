<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    // #[Route('/test', name: 'app_test')]
    // public function index(): JsonResponse
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/TestController.php',
    //     ]);
    // }

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * 
     * @Route("/teamsfdsdf", name="get_teamsfdsfds")
     */
    public function index(Request $request, TeamRepository $repo)
    {
        $this->logger->info('List action callled');

        $response = new JsonResponse();
        $teams = $repo->findAll();
        $teamsArray = [];

        foreach ($teams as $key => $value) {
            $teamsArray[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'color' => $value->getColor(),
            ];
        }

        $response->setData([
            'success' => true,
            'data' => $teamsArray
        ]);

        return $response;
    }

    /**
     * 
     * @Route("/team/new", name="create_team")
     */
    public function store(Request $request, EntityManagerInterface $em)
    {
        $this->logger->info('create action callled');

        $team = new Team();
        $response = new JsonResponse();
        $name = $request->get('name', null);
        $color = $request->get('color', null);

        if (empty($name)) {
            $this->logger->error('error Name cannot be empty');
            
            $response->setData([
                'success' => false,
                'error' => 'Name cannot be empty',
                'data' => null
            ]);

            return $response;
        }
        if (empty($color)) {
            $this->logger->error('error Color cannot be empty');
            
            $response->setData([
                'success' => false,
                'error' => 'Color cannot be empty',
                'data' => null
            ]);

            return $response;
        }

        $team->setName($name);
        $team->setColor('#'.$color);
        $em->persist($team);
        $em->flush();

        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $team->getId(),
                    'name' => $team->getName(),
                    'color' => $team->getColor()
                ]
            ]
        ]);

        return $response;
    }
}
