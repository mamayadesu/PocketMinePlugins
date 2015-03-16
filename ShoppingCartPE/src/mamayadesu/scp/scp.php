<?php

namespace mamayadesu\simplegamemode;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

class simplegamemode extends PluginBase implements CommandExecutor, Listener{

public function onEnable()
    {      
        $this->getLogger()->info("Loading SimpleGamemode v1.0 by MamayAdesu...");
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
public function onDisable()
    {
        @mysqli_close($this->link);
        $this->getLogger()->info("Disabling SimpleGamemode v1.0 by MamayAdesu...");
    }

public function onCommand(CommandSender $sender, Command $command, $label, array $params)
    {
        $username = strtolower($sender->getName());
        $player = $this->getServer()->getPlayer($username);
        if(! ($player instanceof Player))
            {
                $sender->sendMessage("Use this command in game!");
                return true;
            }
        switch($command->getName())
        {
            case "gm":
                switch(array_shift($params))
                {
                    default:
                        $sender->sendMessage("Unknown gamemode!");
                        break;
                    case "survival":
                    case "s":
                    case "0":
                        $sender->sendMessage("Your gamemode was changed to survival!");
                        $sender->setGamemode(0);
                        $sender->close("", "Your gamemode was changed to survival!");
                        break;
                    
                    case "creative":
                    case "c":
                    case "1":
                        $sender->sendMessage("Your gamemode was changed to creative!");
                        $sender->setGamemode(1);
                        $sender->close("", "Your gamemode was changed to creative!");
                        break;
                    
                    case "adventure":
                    case "a":
                    case "2":
                        $sender->sendMessage("Your gamemode was changed to adventure!");
                        $sender->setGamemode(2);
                        $sender->close("", "Your gamemode was changed to adventure!");
                        break;
                }
                break;
        }
        return true;
    }
}
