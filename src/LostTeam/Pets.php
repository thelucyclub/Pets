<?php
namespace LostTeam;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class Pets extends PluginBase {
  public $Pets;
  public function onEnable() {
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
}
