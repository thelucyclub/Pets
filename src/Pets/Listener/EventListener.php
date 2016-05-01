<?php
namespace Pets\Listener;
use Pets\Pets;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\entity\Wolf;
use pocketmine\entity\Ozelot;
class EventListener implements Listener {
    public $Main;
    public function __construct(Pets $Main) {
        $this->Main = $Main;
    }
    public function onDeath(EntityDeathEvent $event) {
        $entity = $event->getEntity();
        if($entity instanceof Wolf) {
            $this->Main->provider->removePet($entity->getName());
            return;
        }
        if($entity instanceof Ozelot) {
            $this->Main->provider->removePet($entity->getName());
            return;
        }
        return;
    }
}
