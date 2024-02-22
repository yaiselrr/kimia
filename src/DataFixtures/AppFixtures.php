<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Position;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $positionArray = ['goalkeeper', 'midfield', 'defending', 'forward'];

        $teams = [
            ['id' => 1, 'name' => 'Barca', 'color' => '#000000'],
            ['id' => 2, 'name' => 'Real Madrid', 'color' => '#111111']
        ];

        $teamAct = [];

        $templateBarca = [
            ['id' => 1, 'name' => 'Gerard Piqué', 'position' => 'defending', 'team'],
            ['id' => 2, 'name' => 'Pedri', 'position' => 'midfield', 'team'],
            ['id' => 3, 'name' => 'Antoine Griezmann', 'position' => 'forward', 'team'],
            ['id' => 4, 'name' => 'Ansu Fati', 'position' => 'defending', 'forward'],
            ['id' => 5, 'name' => 'Sergio Busquets', 'position' => 'midfield', 'team'],
            ['id' => 6, 'name' => 'Samuel Umtiti', 'position' => 'defending', 'team'],
            ['id' => 7, 'name' => 'Sergi Roberto', 'position' => 'midfield', 'team'],
            ['id' => 8, 'name' => 'Jordi Alba', 'position' => 'defending', 'team'],
            ['id' => 9, 'name' => 'Ousmane Dembélé', 'position' => 'forward', 'team'],
            ['id' => 10, 'name' => 'Lionel Messi', 'position' => 'forward', 'team']
        ];

        $templateMadrid = [
            ['id' => 1, 'name' => 'Thibaut Courtois', 'position' => 'defending'],
            ['id' => 2, 'name' => 'Sergio Ramos', 'position' => 'midfield'],
            ['id' => 3, 'name' => 'Casemiro', 'position' => 'forward'],
            ['id' => 4, 'name' => 'Marco Asensio', 'position' => 'defending'],
            ['id' => 5, 'name' => 'Isco', 'position' => 'midfield'],
            ['id' => 6, 'name' => 'Eden Hazard', 'position' => 'defending'],
            ['id' => 7, 'name' => 'Toni Kroos', 'position' => 'midfield'],
            ['id' => 8, 'name' => 'Rodrygo', 'position' => 'defending'],
            ['id' => 9, 'name' => 'Karim Benzema', 'position' => 'forward'],
            ['id' => 10, 'name' => 'Eder Militao', 'position' => 'forward']
        ];

        for ($i = 0; $i < count($teams); $i++) {
            $team = new Team();
            $team->setName($teams[$i]['name']);
            $team->setColor($teams[$i]['color']);

            $manager->persist($team);

            $teamAct [$i] = $team;
        }

        for ($i = 0; $i < count($positionArray); $i++) {
            $position = new Position();

            $position->setName($positionArray[$i]);

            $manager->persist($position);
        }

        for ($i = 0; $i < count($templateBarca); $i++) {
            $player1 = new Player();

            $player1->setName($templateBarca[$i]['name']);
            $player1->setPosition($templateBarca[$i]['position']);
            $player1->setTeam($teamAct[0]);

            $manager->persist($player1);
        }

        for ($i = 0; $i < count($templateMadrid); $i++) {
            $player = new Player();

            $player->setName($templateMadrid[$i]['name']);
            $player->setPosition($templateMadrid[$i]['position']);
            $player->setTeam($teamAct[1]);

            $manager->persist($player);
        }

        $manager->flush();
    }
}
