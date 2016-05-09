<?php
namespace Pets\Managers;

use Pets\Pets;
use pocketmine\utils\Config;

class YamlManager implements PointlessManager{
  private $PetsDataFolder, $Main, $OwnersDataFolder;
  public function __construct(Pets $Main) {
    $this->Main = $Main;
    
    $this->PetsDataFolder = $this->Main->getDataFolder()."Pets/";
    $this->PetsDataFolder = $this->Main->getDataFolder()."Owners/";
    if(!file_exists($this->PetsDataFolder)) {
      @mkdir($this->PetsDataFolder,0777,true);
    }
    if(!file_exists($this->OwnersDataFolder)) {
      @mkdir($this->OwnersDataFolder,0777,true);
    }
  }
  public function makePet($id, $petOwner, $petName) {
    $this->getPetConfigFile($petName, $petOwner, $id)->save(true);
    return;
  }
  public function getPetName($ownerName, $id) {
    return $this->getPetConfigFile(null, $ownerName, $id)->get("PetName"); // returns pets name
  }

  public function getPetId($ownerName) {
    $id = $this->getOwnerConfigFile($ownerName, null)->get("PetId");
    return (int) $id; // I think this works
    // TODO: Implement getPetId() method.
  }
  public function setPetName($newName, $ownerName) {
    $id = $this->getPetId($ownerName);
    $petName = $this->getPetName($ownerName, $id);
    $this->getPetConfigFile($petName, $ownerName, $id)->set("PetName",$newName);
    return;
  }
  public function removePet($petId, $ownerName) {
    @unlink($this->PetsDataFolder . strtolower($petId) . ".yml");
    @unlink($this->OwnersDataFolder . strtolower($ownerName) . ".yml");
    return true;
  }
  public function getPetConfigFile($PetName, $Owner, $id) {
    return new Config($this->PetsDataFolder . $id . ".yml", Config::YAML, [
        "OwnerName" => $Owner,
        "PetName" => $PetName,
        "PetId" => $id
    ]);
  }
  public function getOwnerConfigFile($Owner, $id) {
    return new Config($this->OwnersDataFolder . $Owner . ".yml", Config::YAML, [
        "PetId" => $id
    ]);
  }
  public function close() {
    //
  }
}
