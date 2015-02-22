<?php

namespace mamayadesu\secureaccount;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;

class secureaccount extends PluginBase implements Listener{

private $accounts;

public function onEnable()
    {
        if(!file_exists($this->getDataFolder()."config.yml"))
        {
			mkdir($this->getDataFolder());
            $this->accounts = new Config($this->getDataFolder()."config.yml", Config::YAML);
            $this->getConfig()->set("example_player", "127.0.0.1");
            $this->getConfig()->save();
		}
        
        $this->getLogger()->info("SecureAccount by MamayAdesu enabled!");
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
public function onDisable()
    {
        $this->getLogger()->info("SecureAccount by MamayAdesu disabled!");
    }
    
public function onPlayerPreLogin(PlayerPreLoginEvent $event)
    {
        $this->getConfig()->reload();
        $player = $event->getPlayer();
        $somenumber = str_replace('*', '([0-9]+)', $this->getConfig()->get(strtolower($player->getName())));
        if(! empty($this->getConfig()->get(strtolower($player->getName()))))
        {
            if(! preg_match("/^".$somenumber."$/", $player->getAddress()))
            {
                $player->close("", "Account is secured!");
                $event->setCancelled();
                $this->getLogger()->info($player->getName()." can't join server! His IP ".$player->getAddress()." doesn't match with ".$this->getConfig()->get(strtolower($player->getName())));
                return true;
            }
        }
    }
}