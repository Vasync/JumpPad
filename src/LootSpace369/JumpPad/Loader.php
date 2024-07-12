<?php

declare(strict_types = 1);

namespace LootSpace369\JumpPad;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
  
  use SingletonTrait;
  
  public array $pos1;
  public array $pos2;
  
  public function onLoad(): void {
    self::setInstance($this);
  }
  
  public function onEnable(): void {
    $this->saveDefaultConfig();
    $this->getServer()->getPluginManager()->registerEvents(new EventListener(),$this);
    $this->getScheduler()->scheduleRepeatingTask(new ParticleEffect($this), 15);
  }
  
  public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $command, string $label, array $args) : bool {
      
      switch ($command->getName()) {
        case "pospad":
          if (!$sender instanceof \pocketmine\player\Player) {
            $sender->sendMessage("use this in game!");
            return false;
          }else{
            if (isset($args[0])) {
              if ($args[0] == 1 or $args[0] == 2) {
                if ($args[0] == 1) {
                  $pos = $sender->getLocation();
                  $this->pos1[$sender->getName()] = (int)$pos->getX().";".(int)$pos->getY().";".(int)$pos->getZ().";".$sender->getWorld()->getFolderName().";".$pos->getYaw.";".$pos->getPitch();
                  $sender->sendMessage("§aSuccess add position 1");
                  return true;
                }
                if ($args[0] == 2) {
                  $pos = $sender->getPosition();
                  $this->pos2[$sender->getName()] = (int)$pos->getX().";".(int)$pos->getY().";".(int)$pos->getZ();
                  $sender->sendMessage("§aSuccess add position 2");
                  return true;
                }
              }else{
                $sender->sendMessage("§cYou not input 1 or 2");
                return false;
              }
            }
          }
          break;
        case "setjumppad":
          if (!$sender instanceof \pocketmine\player\Player) {
            $sender->sendMessage("use this in game!");
            return false;
          }else{
            if (isset($this->pos1[$sender->getName()])) {
              if (isset($this->pos2[$sender->getName()])) {
                $this->getConfig()->set("JumpPad",array_merge($this->getConfig()->get("JumpPad"),[$this->pos1[$sender->getName()]."|".$this->pos2[$sender->getName()]]));
                $this->getConfig()->save();
                $sender->sendMessage("success create JumpPad");
              }else{
                $sender->sendMessage("§cYou have not set coordinates 1  coordinates 2");
                return false;
              }
            }else{
              $sender->sendMessage("§cYou have not set coordinates 1  coordinates 2");
              return false;
            }
            return true;
          }
        break;
      }
      return false;
    }
}
