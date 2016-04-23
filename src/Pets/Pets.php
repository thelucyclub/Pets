<?php
namespace TheLucyClub/Pets;
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
        $sender->sendMessage(TF::YELLOW."/pet items <add|Remove|List>");
        $sender->sendMessage(TF::YELLOW."/pet tp [Me|Pet]");
        return true;
      }
      if($args[0] === "spawn" and $sender->hasPermission("pet.cmd.make")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."You need to name your pet!");
          return true;
        }
        $petName = $args[1];
        $this->db->makePet($sender->getName(),$petName);
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = 14;
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
      }elseif($args[0] === "rename" and $sender->hasPermission("pet.cmd.name")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."You need to name your pet!");
          return true;
        }
      }
    }
  }
  public function onDisable() {
    $this->db->close();
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
}
