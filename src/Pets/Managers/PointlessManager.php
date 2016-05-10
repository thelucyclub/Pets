<?php
namespace Pets\Managers;
interface PointlessManager {
    public function makePet($id, $petOwner, $petName);
    public function getPetName($ownerName, $id);
    public function getPetId($petName);
    public function setPetName($newName, $ownerName);
    public function removePet($petId, $ownerName);
    public function close();
}
