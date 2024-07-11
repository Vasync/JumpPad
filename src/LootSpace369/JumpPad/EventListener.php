<?php

declare(strict_types = 1);

namespace LootSpace369\JumpPad;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;

class EventListener implements Listener {
  
  public function onMove(PlayerMoveEvent $ev) {
    $player = $ev->getPlayer();
    $p = $player->getPosition();
    $pos = (int)$p->getX().";".(int)$p->getY().";".(int)$p->getZ().";".$player->getWorld()->getFolderName();
    foreach (Loader::getInstance()->getConfig()->get("JumpPad") as $pad) {
      $ex = explode("|", $pad);
      $to = explode(";", $ex[1]);
      $toPos = new Vector3((int)$to[0],(int)$to[1],(int)$to[2]);
      $distance = $p->distance($toPos)/2;
      if ($pos === $ex[0]) {
        $motFlat = $player->getDirectionPlane()->normalize()->multiply($distance * 3.75 / 20);
        $mot = new Vector3($motFlat->x, 0.7, $motFlat->y);
        $player->setMotion($mot);
      }
    }
  }
}