<?php
namespace Pets\Managers;
interface PointlessManager {
    public function MakePet($petOwner, $petName);
    public function getPetOwner($petName);
    public function getOwnerPet($ownerName);
    public function setPetName($newName, $ownerName, $petName=null);
    public function removePet($petName);
    public function close();
}
