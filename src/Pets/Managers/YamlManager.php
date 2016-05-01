<?php
namespace Pets\Managers;
use Pets\Pets;
use pocketmine\utils\Config;
class YamlManager implements PointlessManager{
  private $PetsDataFolder, $Main;
  public function __construct(Pets $Main) {
    $this->Main = $Main;
    
    $this->PetsDataFolder = $this->Main->getDataFolder()."Pets/";
    if(!file_exists($this->PetsDataFolder)) {
      @mkdir($this->PetsDataFolder,0777,true);
    }
  }
  public function makePet($petOwner, $petName) {
    $this->getPetConfigFile($petName, $petOwner)->save(true);
    return;
  }
  
  public function getPetOwner($petName) {
    return $this->getPetConfigFile($petName, null)->get("OwnerName");
  }
  public function getOwnerPet($ownerName) {
    return;
  }
  public function setPetName($newName, $ownerName, $petName=null) {
    return $this->getPetConfigFile($petName, $ownerName)->set("PetName",$newName);
  }
  public function removePet($petName) {
    return @unlink($this->PetsDataFolder . strtolower($petName) . ".yml");
  }
  public function getPetConfigFile($PetName, $Owner) {
    return new Config($this->PetsDataFolder . strtolower($PetName) . ".yml", Config::YAML, [
        "OwnerName" => $Owner,
        "PetName" => $PetName
    ]);
  }
  public function close() {
    //
  }
}
