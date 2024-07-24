<?php

declare(strict_types = 1);

namespace LootSpace369\JumpPad;

use pocketmine\world\Position;
use pocketmine\world\particle\FlameParticle;
use pocketmine\scheduler\Task;
use pocketmine\math\Vector3;
use pocketmine\world\particle\DustParticle;
use pocketmine\world\World;

class ParticleEffect extends Task {
  
  public function __construct(private Loader $pl) {}
  
  public function createStarCircle(Position $pos, float $radius): void
  {
      $center = $pos->ceil()->add(0.5, 0, 0.5);
      $world = $pos->getWorld();
  
      $this->addStarParticles($world, $center, $radius, 0);
      
      $this->addStarParticles($world, $center, $radius, 0.1);
  }
  
  private function addStarParticles(World $world, Vector3 $center, float $radius, float $angleOffset): void
  {
      for ($i = 0; $i < 10; $i++) {
          $angle = ($i / 10) * 2 * M_PI + $angleOffset;
          $x = $center->x + ($radius * cos($angle));
          $z = $center->z + ($radius * sin($angle));
          $y = $center->y;
          
          for ($j = 0; $j < 5; $j++) {
              $particleAngle = ($j / 5) * 2 * M_PI;
              $particleX = $x + (0.5 * cos($particleAngle + ($i / 10) * M_PI));
              $particleZ = $z + (0.5 * sin($particleAngle + ($i / 10) * M_PI));
              $particle = new DustParticle(new \pocketmine\color\Color(255, 178, 255));
              $world->addParticle(new Vector3($particleX, $y + 0.3, $particleZ), $particle);
          }
      }
  }
  
  public function onRun(): void {
    foreach ($this->pl->getConfig()->get("JumpPad") as $total) {
      $divide = explode("|",$total)[0];
      $ex = explode(";",$divide);
      
      $pos = new Position($ex[0], $ex[1], $ex[2], \pocketmine\Server::getInstance()->getWorldManager()->getWorldByName($ex[3]));
      $this->createStarCircle($pos, 1);
      
      /*for($i = 0; $i < 0.5;$i+=0.1) {
        $position->addParticle($pos->add(1,0.2,$i),$p);
        $position->addParticle($pos->add(1,0.2,0 - $i),$p);
      }
      for($i = 0; $i < 0.5;$i+=0.1) {
        $position->addParticle($pos->add(-1,0.2,$i),$p);
        $position->addParticle($pos->add(-1,0.2,0 - $i),$p);
      }
      for($i = 0; $i < 0.5;$i+=0.1) {
        $position->addParticle($pos->add($i,0.2,-1),$p);
        $position->addParticle($pos->add(0 - $i,0.2,-1),$p);
      }
      
      for($i = 0; $i < 0.5;$i+=0.1) {
        $position->addParticle($pos->add($i,0.2,1),$p);
        $position->addParticle($pos->add(0 - $i,0.2,1),$p);
      }
      
      for($i = 0; $i < 1;$i+=0.1) {
        $position->addParticle($pos->add(0.5 + $i, 0.2, 1 - $i),$p);
        $position->addParticle($pos->add(),$p);
      }*/
    }
  }
}
