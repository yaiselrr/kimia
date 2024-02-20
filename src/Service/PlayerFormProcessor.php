<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\Model\PlayerDto;
use App\Form\Type\PlayerFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class PlayerFormProcessor
{
    private $playerManager;
    private $teamManager;
    private $formFactoryCreate;

    public function __construct(PlayerManager $playerManager, TeamManager $teamManager, FormFactoryInterface $formFactoryCreate)
    {
        $this->playerManager = $playerManager;
        $this->teamManager = $teamManager;
        $this->formFactoryCreate = $formFactoryCreate;
    }

    public function __invoke($playerPar, $teamId): array
    // ['id' => 1, 'name' => 'Thibaut Courtois', 'position' => 'defending']
    {
        $team = $this->teamManager->find($teamId);

        if (!$team) {
            return [null, 'Team not found'];
        }

        $playerDto = $this->playerManager->createDto();

        $form = $this->formFactoryCreate->create(PlayerFormType::class, $playerDto);

        $form->handleRequest($playerPar);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submited'];
        }

        if ($form->isValid()) {
            $player = $playerPar == null ? $this->playerManager->create() : $playerPar;

            $player->setName($playerDto->name);
            $player->setPosition($playerDto->position);
            $player->setTeam($team);

            $player->getId() < 1 ? $this->playerManager->save($player) : $this->playerManager->saveAndUpdate($player);

            return [$player, null];
        }

        return [null, $form];
    }
}
