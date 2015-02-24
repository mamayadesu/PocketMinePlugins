<?php

namespace mamayadesu\secureaccount;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

class secureaccount extends PluginBase implements CommandExecutor, Listener{

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
        
        $this->getLogger()->info("SecureAccount v1.1 by MamayAdesu enabled!");
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
public function onDisable()
    {
        $this->getLogger()->info("SecureAccount v1.1 by MamayAdesu disabled!");
    }
    
public function onPlayerPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $somenumber = str_replace('*', '([0-9]+)', $this->getConfig()->get(strtolower($player->getName())));
        if(! empty($this->getConfig()->get(strtolower($player->getName()))) && ! preg_match("/^".$somenumber."$/", $player->getAddress()))
        {
            $player->close("", "Account is secured!");
            $event->setCancelled();
            $this->getLogger()->info($player->getName()." can't join server! His IP ".$player->getAddress()." doesn't match with ".$this->getConfig()->get(strtolower($player->getName())));
            return true;
        }
    }

public function onCommand(CommandSender $sender, Command $command, $label, array $params)
    {
        switch($command->getName())
        {
            case "secure":
                $player = array_shift($params);
                $ip = implode(" ", $params);
                if(! empty($player) && ! empty($ip))
                    {
                        $this->getConfig()->set(strtolower($player), $ip);
                        $this->getConfig()->save();
                        $sender->sendMessage("Now this account is secured!");
                    }
                else
                    {
                        $sender->sendMessage("Write nickname and IP!");
                    }
                break;
                
            case "unsecure":
                $player = array_shift($params);
                if(! empty($player))
                    {
                        if(! empty($this->getConfig()->get(strtolower($player))))
                        {
                            $this->getConfig()->remove(strtolower($player));
                            $this->getConfig()->save();
                            $sender->sendMessage("Now this account is unsecured!");
                        }
                        else
                        {
                            $sender->sendMessage("Account not found in list of secure!");
                        }
                    }
                else
                    {
                        $sender->sendMessage("Write nickname!");
                    }
                break;
        }
        return true;
    }
}