<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\Model\PlayerDto;
use App\Form\Type\GameFormType;
use App\Form\Type\PlayerFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class GameFormProcessor
{
    private $gameManager;
    private $teamManager;
    private $playerManager;
    private $formFactoryCreate;

    public function __construct(GameManager $gameManager, TeamManager $teamManager, PlayerManager $playerManager, FormFactoryInterface $formFactoryCreate)
    {
        $this->gameManager = $gameManager;
        $this->teamManager = $teamManager;
        $this->playerManager = $playerManager;
        $this->formFactoryCreate = $formFactoryCreate;
    }

    public function __invoke(Request $request, Player $player): array
    {
        $team = $this->teamManager->findById($request->get('team'));

        if (!$team) {
            return [null, 'Team not found'];
        }        

        $gameDto = $this->gameManager->createDto();
        $form = $this->formFactoryCreate->create(GameFormType::class, $gameDto);        

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submited'];
        }        
        
        if ($form->isValid()) {
            if ($player->getTeam()->getName() == $team->getName()) {
                return [true, null];
            }

            return [false, null];
        }

        return [null, $form];
    }
}
