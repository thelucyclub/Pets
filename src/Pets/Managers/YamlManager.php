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
  public function makePet($id, $petOwner, $petName) {
    $this->getPetConfigFile($petName, $petOwner, $id)->save(true);
    return;
  }
  public function getPetName($ownerName, $id) {
    return $this->getPetConfigFile(null, $ownerName, $id)->get("PetName");
  }

  public function getPetId($ownerName) {
    // TODO: Implement getPetId() method.
  }
  public function setPetName($newName, $ownerName) {
    return $this->getPetConfigFile($petName, $ownerName, $this->provider->getPetId())->set("PetName",$newName); //returns Pets name
  }
  public function removePet($petName) {
    return @unlink($this->PetsDataFolder . strtolower($petName) . ".yml");
  }
  public function getPetConfigFile($PetName, $Owner, $id) {
    return new Config($this->PetsDataFolder . $id . ".yml", Config::YAML, [
        "OwnerName" => $Owner,
        "PetName" => $PetName,
        "PetId" => $id
    ]);
  }
  public function close() {
    //
  }
}
