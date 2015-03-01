<?php

namespace mamayadesu\scp;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;

class scp extends PluginBase implements CommandExecutor, Listener{

private $configfile;

public function onEnable()
    {
        if(! file_exists($this->getDataFolder()."config.yml"))
        {
            mkdir($this->getDataFolder());
            $this->configfile = new Config($this->getDataFolder()."config.yml", Config::YAML);
            $this->getConfig()->set("mysql_addr", "127.0.0.1");
            $this->getConfig()->set("mysql_user", "root");
            $this->getConfig()->set("mysql_pass", "passwd");
            $this->getConfig()->set("mysql_base", "db");
            $this->getConfig()->set("mysql_port", 3306);
            $this->getConfig()->set("mysql_table", "shoppingcartpe");
            $this->getConfig()->save();
        }
        
        $this->getLogger()->info("Loading ShoppingCartPE v1.0 beta by MamayAdesu...");
        
        $this->link = @mysqli_connect(
         $this->getConfig()->get("mysql_addr"),
         $this->getConfig()->get("mysql_user"),
         $this->getConfig()->get("mysql_pass"),
         $this->getConfig()->get("mysql_base"),
         $this->getConfig()->get("mysql_port")
        ) or die("FAILED TO CONNECT TO MYSQL SERVER!");
        
        $this->getLogger()->info("Successful connected to MySQL!");

        @mysqli_query($this->link, "

CREATE TABLE IF NOT EXISTS `".$this->getConfig()->get("mysql_table")."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL DEFAULT '0',
  `count` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

") or die("FAILED TO USE MYSQL COMMAND! QUERY 1");
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
public function onDisable()
    {
        @mysqli_close($this->link);
        $this->getLogger()->info("Disabling ShoppingCartPE v1.0 beta by MamayAdesu...");
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
            case "cart":
                $action = array_shift($params);
                $id = implode("", $params);
                $allpurchases = @mysqli_query($this->link, "SELECT * FROM `".$this->getConfig()->get("mysql_table")."` WHERE `name`='$username'") or die("FAILED TO USE MYSQL COMMAND! QUERY 2");
                $purchasesbyid = @mysqli_query($this->link, "SELECT * FROM `".$this->getConfig()->get("mysql_table")."` WHERE `id`='$id'") or die("FAILED TO USE MYSQL COMMAND! QUERY 3");
                if(empty($action))
                    {
                        if(@mysqli_num_rows($allpurchases))
                            {
                                $sender->sendMessage("======== Your basket ========");
                                while($ap = @mysqli_fetch_assoc($allpurchases))
                                    {
                                        $item = preg_replace("/^([0-9]+):([0-9]+)/", "$1", $ap['item']);
                                        $damage = preg_replace("/^([0-9]+):([0-9]+)/", "$2", $ap['item']);
                                        $fullitem = Item::get($item, $damage, $ap['count']);
                                        $fullitem = preg_replace("/x([0-9]+)/s", "", $fullitem);
                                        $fullitem = str_replace("Item ", "", $fullitem);
                                        $sender->sendMessage($ap['id'].". Item: $fullitem | Count: ".$ap['count']);
                                    }
                            }
                        else $sender->sendMessage("Your basket is empty!");
                        return true;
                    }
                    
                elseif($action == "get")
                    {
                        if($id != "all")
                            {
                                if(! empty($id))
                                    {
                                        if(@mysqli_num_rows($purchasesbyid))
                                            {
                                                $pbi = @mysqli_fetch_array($purchasesbyid);
                                                $this->getServer()->dispatchCommand(new ConsoleCommandSender(),"give $username ".$pbi['item']." ".$pbi['count']);
                                                @mysqli_query($this->link, "DELETE FROM `".$this->getConfig()->get("mysql_table")."` WHERE `id`='$id'") or die("FAILED TO USE MYSQL COMMAND! QUERY 4");
                                                $sender->sendMessage("This thing was moved to your inventory!");
                                            }
                                        else $sender->sendMessage("Unknow purchase ID!");
                                        return true;
                                    }
                                else return false;
                            }
                        else
                            {
                                if(@mysqli_num_rows($allpurchases))
                                    {
                                        while($ap = @mysqli_fetch_assoc($allpurchases))
                                            {
                                                $this->getServer()->dispatchCommand(new ConsoleCommandSender(),"give $username ".$ap['item']." ".$ap['count']);
                                                @mysqli_query($this->link, "DELETE FROM `".$this->getConfig()->get("mysql_table")."` WHERE `name`='$username'") or die("FAILED TO USE MYSQL COMMAND! QUERY 4");
                                            }
                                        $sender->sendMessage("All things were moved to your inventory!");
                                    }
                                else $sender->sendMessage("Your basket is empty!");
                                return true;
                            }
                    }
                else $sender->sendMessage("Unknow subcommand!");
                return true;
                break;
        }
    }
}
