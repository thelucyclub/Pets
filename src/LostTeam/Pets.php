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
    if(strtolower($command) === "pet" and $sender->hasPermission("pet.cmd")) {
      if(count($args) = 0 and $sender->hasPermission("pet.cmd.help") or $args[0] === "help" and $sender->hasPermission("pet.cmd.help")) {
        $sender->sendMessage(TF::YELLOW."/pet help");
        $sender->sendMessage(TF::YELLOW."/pet spawn <PetName>");
        $sender->sendMessage(TF::YELLOW."/pet rename <PetName>");
        $sender->sendMessage(TF::YELLOW."/pet items <add|Remove|List> <Item Id>");
        return true;
      }
      if($args[0] === "spawn" and $sender->hasPermission("pet.cmd.make")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."You need to name your pet!");
          return true;
        }
        $petName = $args[1];
        $this->db->makePet($sender->getName(),$petName);
        //spawn wolf tamed by command sender with petName on nametag
      }elseif($args[0] === "rename" and $sender->hasPermission("pet.cmd.name")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."You need to name your pet!");
          return true;
        }
        $name = $this->db->deleteByCondition("petOwner" => $sender->getName());
        $name['petName'] = $args[1];
      }elseif($args[0] === "items" and $sender->hasPermission("pet.cmd.storage")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."/pet items <add|Remove|List> <Item Id>");
          return true;
        }
        if($args[1] === "add") {
          //add item with damage to database
        }elseif($args[1] === "remove") {
          //remove item with damage from database and give player item with damage
        }elseif($args[1] === "list") {
          //list all items in database
        }
      }elseif($args[0] === "tp" and $sender->hasPermission("pet.cmd.tp")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."/pet tp <Me|Pet>");
          return true;
        }
        //tp pet or player depending on Sub-SubCommand
      }
    }
  }
  public function onDisable() {
    $this->db->close();
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
}
