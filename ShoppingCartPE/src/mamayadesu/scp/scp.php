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
        if(!file_exists($this->getDataFolder()."config.yml"))
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
        
        $this->getLogger()->info("Loading ShoppingCartPE v1.0 by MamayAdesu...");
        
        $this->link = @mysqli_connect($this->getConfig()->get("mysql_addr") , $this->getConfig()->get("mysql_user"), $this->getConfig()->get("mysql_pass"), $this->getConfig()->get("mysql_base")) or die("FAILED TO CONNECT TO MYSQL SERVER!");
        
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
        $this->getLogger()->info("Disabling ShoppingCartPE v1.0 by MamayAdesu");
    }
    
public function itemName($id,$type)
    {
        switch($id)
            {
                case "0":
                case "0:0":
                    $t1 = "Air";
                    $t2 = "AIR";
                    break;
                    
                case "1":
                case "1:0":
                    $t1 = "Stone";
                    $t2 = "STONE";
                    break;
                    
                case "1:1":
                    $t1 = "Granite";
                    $t2 = "GRANITE";
                    break;
                    
                case "1:2":
                    $t1 = "Polished Granite";
                    $t2 = "POLISHED_GRANITE";
                    break;
                    
                case "1:3":
                    $t1 = "Diorite";
                    $t2 = "DIORITE";
                    break;
                    
                case "1:4":
                    $t1 = "Polished Diorite";
                    $t2 = "POLISHED_DIORITE";
                    break;
                    
                case "2":
                case "2:0":
                    $t1 = "Grass Block";
                    $t2 = "GRASS_BLOCK";
                    break;
                    
                case "3":
                case "3:0":
                    $t1 = "Dirt";
                    $t2 = "DIRT";
                    break;
                    
                case "3":
                case "3:1":
                    $t1 = "Coarse Dirt";
                    $t2 = "COARSE_DIRT";
                    break;
                    
                case "4":
                case "4:0":
                    $t1 = "Cobblestone";
                    $t2 = "COBBLESTONE";
                    break;
                    
                case "5":
                case "5:0":
                    $t1 = "Oak Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "5:1":
                    $t1 = "Spruce Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "5:2":
                    $t1 = "Birch Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "5:3":
                    $t1 = "Jungle Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "5:4":
                    $t1 = "Acaia Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "5:5":
                    $t1 = "Dark Oak Wooden Plank";
                    $t2 = "WOODEN_PLANK";
                    break;
                    
                case "6":
                case "6:0":
                    $t1 = "Oak Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "6:1":
                    $t1 = "Spruce Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "6:2":
                    $t1 = "Birch Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "6:3":
                    $t1 = "Jungle Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "6:4":
                    $t1 = "Acaia Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "6:5":
                    $t1 = "Dark Oak Sapling";
                    $t2 = "SAPLING";
                    break;
                    
                case "7":
                case "7:0":
                    $t1 = "Bedrock";
                    $t2 = "BEDROCK";
                    break;
                    
                case "8":
                case "8:0":
                    $t1 = "Water";
                    $t2 = "WATER";
                    break;
                    
                case "9":
                case "9:0":
                    $t1 = "Stationary Water";
                    $t2 = "STATIONARY_WATER";
                    break;
                
                case "10":
                case "10:0":
                    $t1 = "Lava";
                    $t2 = "LAVA";
                    break;
                    
                case "11":
                case "11:0":
                    $t1 = "Stationary Lava";
                    $t2 = "STATIONARY_LAVA";
                    break;
                    
                case "12":
                case "12:0":
                    $t1 = "Sand";
                    $t2 = "SAND";
                    break;
                    
                case "12:1":
                    $t1 = "Red sand";
                    $t2 = "SAND";
                    break;
                    
                case "13":
                case "13:0":
                    $t1 = "Gravel";
                    $t2 = "GRAVEL";
                    break;
                    
                case "14":
                case "14:0":
                    $t1 = "Gold Ore";
                    $t2 = "GOLD_ORE";
                    break;
                    
                case "15":
                case "15:0":
                    $t1 = "Iron Ore";
                    $t2 = "IRON_ORE";
                    break;
                    
                case "16":
                case "16:0":
                    $t1 = "Coal Ore";
                    $t2 = "COAL_ORE";
                    break;
                    
                case "17":
                case "17:0":
                    $t1 = "Oak Wood";
                    $t2 = "WOOD";
                    break;
                    
                case "17:1":
                    $t1 = "Spruce Wood";
                    $t2 = "WOOD";
                    break;
                    
                case "17:2":
                    $t1 = "Birch Wood";
                    $t2 = "WOOD";
                    break;
                    
                case "17:3":
                    $t1 = "Jungle Wood";
                    $t2 = "WOOD";
                    break;
            }
        if($type == 1 && ! empty($t1)) return $t1;
        if($type == 2 && ! empty($t2)) return $t2;
        if(empty($t1) && empty($t2)) return $id;
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
