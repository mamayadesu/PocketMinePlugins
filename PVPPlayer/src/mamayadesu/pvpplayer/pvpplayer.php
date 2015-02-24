<?php

namespace mamayadesu\pvpplayer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByEntity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class pvpplayer extends PluginBase implements CommandExecutor, Listener{

private $pvp;

public function onEnable()
    {
        if(! file_exists($this->getDataFolder()."config.yml"))
        {
            mkdir($this->getDataFolder());
            $this->pvp = new Config($this->getDataFolder()."config.yml", Config::YAML);
        }
        
        $this->getLogger()->info("PVPPlayer v1.0 by MamayAdesu enabled!");
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
public function onDisable()
    {
        $this->getLogger()->info("PVPPlayer v1.0 by MamayAdesu disabled!");
    }

public function onCommand(CommandSender $sender, Command $command, $label, array $params)
    {
        $username = strtolower($sender->getName());
        $player = $this->getServer()->getPlayer($username);
        if(! ($player instanceof Player))
            {
                $sender->sendMessage("Use this command in-game!");
                return true;
            }
        switch($command->getName())
	        {
                case "pvppon":
                    $this->getConfig()->set(strtolower($sender->getName()), true);
                    $this->getConfig()->save();
                    $sender->sendMessage("PvP enabled for you.\nUse '/pvppoff' for disable.");
                    break;

                case "pvppoff":
                    $this->getConfig()->set(strtolower($sender->getName()), false);
                    $this->getConfig()->save();
                    $sender->sendMessage("PvP disabled for you.\nUse '/pvppon' for enable.");
                    break;

                case "pvpp":
                    if($this->getConfig()->get(strtolower($sender->getName()))) $sender->sendMessage("How PvP enabled for you.\nUse '/pvppoff' for disable.");
                     else $sender->sendMessage("How PvP disabled for you.\nUse '/pvppon' for enable.");
                    break;
            }
            return true;
    }

public function onEntityDamageByEntity(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent)
        {
            $damager = $event->getDamager();
            if($entity instanceof Player && $damager instanceof Player)
            {
                if(! $this->getConfig()->get(strtolower($damager->getName())))
                {
                    $event->setCancelled(true);
                    $damager->sendMessage("You disabled PvP mode!\nUse '/pvppon' for enable!");
                }
                if(! $this->getConfig()->get(strtolower($entity->getName())))
                {
                    $event->setCancelled(true);
                    $damager->sendMessage("Your target disabled PvP mode!");
                }
            }
        }
    }
}