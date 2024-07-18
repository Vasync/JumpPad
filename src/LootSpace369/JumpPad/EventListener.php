<?php

declare(strict_types = 1);

namespace LootSpace369\JumpPad;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;


class EventListener implements Listener {

  public function lookAtLocation(Entity $entity, Location $location): array{
    $angle = atan2($location->z - $entity->getLocation()->z, $location->x - $entity->getLocation()->x);
    $yaw = (($angle * 180) / M_PI) - 90;
    $angle = atan2((new Vector2($entity->getLocation()->x, $entity->getLocation()->z))->distance(new Vector2($location->x, $location->z)), $location->y - $entity->getLocation()->y);
    $pitch = (($angle * 180) / M_PI) - 90;

    $entity->setRotation($yaw, $pitch);

    return [$yaw, $pitch];
  }
  
  public function onMove(PlayerMoveEvent $ev) {
    $player = $ev->getPlayer();
    $p = $player->getPosition();
    $pos = (int)$p->getX().";".(int)$p->getY().";".(int)$p->getZ().";".$player->getWorld()->getFolderName();
    foreach (Loader::getInstance()->getConfig()->get("JumpPad") as $pad) {
      $ex = explode("|", $pad);
      $exCheck = explode(";", $ex[0]);
      $posCheck = $exCheck[0].";".$exCheck[1].";".$exCheck[2].";".$exCheck[3];
      $to = explode(";", $ex[1]);
      $toPos = new Vector3((int)$to[0],(int)$to[1],(int)$to[2]);
      $distance = $p->distance($toPos)/2;
      if ($pos === $posCheck) {
        $motFlat = $player->getDirectionPlane()->normalize()->multiply($distance * 3.75 / 20);
        $mot = new Vector3($motFlat->x, 0.7, $motFlat->y);
        $this->lookAtLocation($player, (new Location((float)$to[0],(float)$to[1],(float)$to[2],\pocketmine\Server::getInstance()->getWorldManager()->getWorldByName($to[3]),$to[4],$to[5]))->add(0,0.5,0));
        $player->setMotion($mot);
      }
    }
  }
}
