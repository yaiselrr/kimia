<?php

namespace App\Form\Model;

use App\Entity\Player;

class PlayerDto
{
    public $name;
    public $position;
    public $team;

    // public function __construct() {
    //     $this->team = [];
    // }

    // public static function createFromPlayer(Player $player): self
    // {
    //     $dto = new self();
    //     $dto->name = $player->getName();

    //     return $dto;
    // }
}