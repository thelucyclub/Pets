<?php
namespace Pets;
use pocketmine\Player;
interface PetEntity{
  public function getName();
  public function spawnTo(Player $player);
}
