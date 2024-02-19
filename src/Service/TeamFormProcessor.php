<?php

namespace App\Service;

use App\Entity\Team;
use App\Form\Model\TeamDto;
use App\Form\Type\TeamFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class TeamFormProcessor
{
    private $teamManager;
    private $formFactoryCreate;

    public function __construct(TeamManager $teamManager, FormFactoryInterface $formFactoryCreate)
    {
        $this->teamManager = $teamManager;
        $this->formFactoryCreate = $formFactoryCreate;
    }

    public function __invoke(Request $request, Team $team = null): array
    {
        $teamDto = $this->teamManager->createDto();

        $form = $this->formFactoryCreate->create(TeamFormType::class, $teamDto);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submited'];
        }

        if ($form->isValid()) {
            $team = $team == null ? $this->teamManager->create() : $team;

            $team->setName($teamDto->name);
            $team->setColor($teamDto->color);

            $team->getId() < 1 ? $this->teamManager->save($team) : $this->teamManager->saveAndUpdate($team);

            return [$team, null];
        }

        return [null, $form];
    }
}
