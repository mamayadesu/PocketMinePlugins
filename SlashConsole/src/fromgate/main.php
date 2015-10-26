<?php

# Автор идеи Fromgate
# Author Fromgate

# Портировал с разрешением самого Fromgate с CraftBukkit на PocketMine MamayAdesu
# Ported with the Fromgate's permission from CraftBukkit on PocketMine by MamayAdesu

namespace fromgate;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\ServerCommandEvent;

class main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onServerCommandEvent(ServerCommandEvent $event) {
        $command = $event->getCommand();
        /*if($command(){0} == "/") $event->setCommand(str_replace("/", "", $event->getCommand()));*/
        if($command{0} == "/") $event->setCommand(substr($event->getCommand(), 1));
    }
}
