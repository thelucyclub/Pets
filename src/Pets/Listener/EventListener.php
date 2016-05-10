<?php
namespace Pets\Listener;

use Pets\Managers\PointlessManager;
use Pets\PetWolf;
use Pets\PetOcelot;
use Pets\Pets;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\entity\Wolf;
use pocketmine\entity\Ocelot;

class EventListener implements Listener {
    public $Main, $provider;
    public function __construct(Pets $Main, PointlessManager $provider) {
        $this->Main = $Main;
        $this->provider = $provider;
    }
    public function onDeath(EntityDeathEvent $event) {
        $entity = $event->getEntity();
        if($entity instanceof PetWolf) {
            $this->provider->removePet($entity->getId(), null);
            return;
        }
        if($entity instanceof PetOcelot) {
            $this->provider->removePet($entity->getId(), null);
            return;
        }
        return;
    }
}
