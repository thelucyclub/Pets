<?php
namespace Pets;

use Pets\Managers\PointlessManager;
use Pets\Managers\Sqlite3Manager;
use Pets\Managers\YamlManager;
use Pets\Listener\EventListener;

use pocketmine\plugin\PluginBase;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Entity;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;

class Pets extends PluginBase {
  public $cfg, $provider, $prefix = (TF::BLUE."[".TF::GREEN.TF::BOLD."Pets".TF::RESET.TF::BLUE."] ".TF::RESET);
  public function onEnable() {
    $this->configProvider();
    Entity::registerEntity(PetWolf::class, true);
    // Entity::registerEntity(PetOcelot::class, true);
    $this->getLogger()->debug("Entities have been registered!");
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $this->provider), $this);
    $this->getLogger()->debug("Events have been registered!");
    $this->getLogger()->notice(TF::GREEN."Enabled!");
  }
  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if((strtolower($command) === "pet" or strtolower($command) === "pets")) {
      if($this->cfg instanceof Config);
      if($this->provider instanceof PointlessManager);
      $pet = $this->getPet($sender->getName());
      if($pet instanceof Entity);
      if(!$sender instanceof Player) {
        $sender->sendMessage($this->prefix.TF::YELLOW."Console Cannot Use Pets Command!");
        return true;
      }
      if($args[0] === "clear") {
        foreach($this->getServer()->getLevels() as $level) {
          foreach($level->getEntities() as $entity) {
            if($entity instanceof PetWolf) {
              $entity->kill();
            }
            /* if($entity instanceof PetOcelot) {
              $entity->kill();
            } */
          }
        }
        return true;
      }
      if((count($args) == 0 or $args[0] === "help") and $sender->hasPermission("pet.cmd.help")) {
        $sender->sendMessage(TF::YELLOW."------------------".TF::BLUE."[".TF::GREEN.TF::BOLD."Pets".TF::RESET.TF::BLUE."]".TF::RESET.TF::YELLOW."-----------------");
        $sender->sendMessage(TF::YELLOW."/pet help");
        $sender->sendMessage(TF::YELLOW."/pet spawn <PetName>");
        $sender->sendMessage(TF::YELLOW."/pet rename <NewName>");
        //$sender->sendMessage(TF::YELLOW."/pet items <add|Remove|List>");
        $sender->sendMessage(TF::YELLOW."/pet tp");
        $sender->sendMessage(TF::YELLOW."/pets clear");
        return true;
      }
      if($args[0] === "spawn" and $sender->hasPermission("pet.cmd.make")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage(TF::RED."You need to name your pet!");
          return true;
        }
        if($pet !== null) {
          $sender->sendMessage($this->prefix.TF::DARK_RED."You already have a pet!");
          return true;
        }
        $nbt = $this->makeNBT(null, null, $sender->getName()."'s pet ".$args[1], null, $sender->getYaw(), $sender->getPitch(), $sender->getX(), $sender->getY(), $sender->getZ());
        $petEntity = Entity::createEntity("PetWolf", $sender->getLevel()->getChunk($sender->getX() >> 4, $sender->getZ() >> 4), $nbt);
        $this->provider->makePet($petEntity->getId(), $sender->getName(),$args[1]);
        if($petEntity) {
          $sender->sendMessage($this->prefix.TF::GREEN."Pet Created!");
          return true;
        }
        $sender->sendMessage($this->prefix.TF::GREEN."Pet Creation Failed!");
          return true;
      }elseif($args[0] === "rename" and $sender->hasPermission("pet.cmd.name")) {
        if($args[1] === "" or $args[1] === null) {
          $sender->sendMessage($this->prefix.TF::RED."You need to name your pet!");
          return true;
        }elseif(strtolower($this->cfg->get("allow-rename")) === "false") {
          $sender->sendMessage($this->prefix.TF::RED."Pet renaming is Disabled!");
          return true;
        }
        if($pet == null) {
          $sender->sendMessage($this->prefix.TF::DARK_RED."You already have a pet!");
          return true;
        }
        $pet->setNameTag($sender->getName()."'s pet ".$args[1]);
        $sender->sendMessage($this->prefix.TF::GREEN."Pet Renamed!");
        return true;
      }elseif(($args[0] === "tp" or $args[0] === "teleport") and $sender->hasPermission("pet.cmd.tp")) {
        if($args[1] === null and $args[2] !== null) {
          $position = new Vector3($sender->getX(), $sender->getY(), $sender->getZ());
          $sender->sendMessage($this->prefix.TF::GREEN."Pet Teleporting...");
          $pet->teleport($position);
          $sender->sendMessage($this->prefix.TF::GREEN."Pet Teleported!");
          return true;
        }
        return true;
      }
      return false;
    }
    return false;
  }
  private function makeNBT($skin, $skinData, $name, $inv, $yaw, $pitch, $x, $y, $z) {
    $nbt = new CompoundTag;
    $nbt->Pos = new ListTag("Pos", [
        new DoubleTag("", $x),
        new DoubleTag("", $y),
        new DoubleTag("", $z)
    ]);
    $nbt->Rotation = new ListTag("Rotation", [
        new FloatTag("", $yaw),
        new FloatTag("", $pitch)
    ]);
    $nbt->Health = new ShortTag("Health", 20);
    $nbt->Inventory = new ListTag("Inventory", $inv); //TODO v2 pets inventory
    $nbt->CustomName = new StringTag("CustomName", $name);
    $nbt->CustomNameVisible = new ByteTag("CustomNameVisible", 1);
    // $nbt->Invulnerable = new Byte("Invulnerable", 0);
    return $nbt;
  }
  public function getPet($name) {
    if($this->provider instanceof PointlessManager);
    $petId = $this->provider->getPetId($name);
    foreach($this->getServer()->getLevels() as $level) {
      if($level->getEntity($petId) != null) {
        return $level->getEntity($petId);
      }
    }
    return null;
  }
  public function configProvider() {
    if(!file_exists($this->getDataFolder())) {
      @mkdir($this->getDataFolder());
    }
    $this->saveDefaultConfig();
    $this->cfg = new Config($this->getDataFolder()."config.yml");
    $DataProvider = $this->cfg->get("dataProvider");
    if(strtolower($DataProvider) === "sqlite3" or strtolower($DataProvider) === "sql") {
      $this->provider = new Sqlite3Manager($this);
      $this->getLogger()->info("Using SQLITE3 data provider");
    }elseif(strtolower($DataProvider) === "yaml" or strtolower($DataProvider) === "yml") {
      $this->provider = new YamlManager($this);
      $this->getLogger()->info("Using YAML data provider");
    }else{
      $this->provider = new YamlManager($this);
      $this->getLogger()->info("Using SQLITE3 data provider");
      throw new \RuntimeException("Invalid data provider");
    }
  }
  public function onDisable() {
    $this->getLogger()->notice(TF::GREEN."Disabled!");
  }
}
