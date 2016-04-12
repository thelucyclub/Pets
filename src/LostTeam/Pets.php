<?php
namespace LostTeam;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class Pets extends PluginBase {
  public $Pets, $db;
  public function onEnable() {
    $this->db = new DatabaseManager($this->getdataFolder()."Pets.sqlite3");
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
  public function onCommand(CommandSender $sender, Command $command, $list, array $args) {
    if(strtolower($command) === "")
  }
  public function onDisable() {
    $this->db->close();
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
}
