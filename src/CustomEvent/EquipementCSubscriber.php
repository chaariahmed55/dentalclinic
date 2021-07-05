<?php

namespace App\CustomEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EquipementCSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EquipementEvent::NAME => 'do'
        ];
    }

    public function do(EquipementEvent $equipevent)
    {
        $equipevent->changeQuantite();
    }
}


?>