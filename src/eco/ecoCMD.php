<?php

declare(strict_types=1);

namespace eco;

use jojoe77777\FormAPI\FormAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as T;

class ecoCMD extends Command implements PluginIdentifiableCommand{
	
	public function getPlugin() : Plugin{
   return true;
}
	
	private $main;
	
	public function __construct(Loader $main){
		$this->main = $main;
		parent::__construct("GeldUI", "GeldSystem", "/GeldSystem", ["Geld", "GeldSystem", "GeldS"]);
	}

public function execute(CommandSender $sender, string $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage("§crun command in game!");
			return false;
		}
		if(!isset($args[0]) || $args[0] !== "op"){
			if($sender->hasPermission("eco.cmd.use")){
				$this->memberForm($sender);
				return true;
			}else{
				$sender->sendMessage("§eYou don't have permission");
				return false;
			}
		}
		if($args[0] === "op"){
			if($sender->hasPermission("eco.use.op")){
				$this->opForm($sender);
			}else{
				$sender->sendMessage("§cYou don't have permission!");
				return false;
			}
		}
		return false;
	}
public function memberForm(Player $sender){
	        $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function(Player $sender, ?int $data){
			if(!isset($data)) return;
			switch($data){
			case 0:
                            $this->mymoney($sender);
			    break;
                        case 1:
                            $this->pay($sender);
                            break;
                        case 2:
                            $this->see($sender);
                            break;
                        case 3:
                            $this->top($sender);
                            break;
            }
          });
       $form->setTitle(T::GREEN . "GeldUI");
       $form->addButton(T::AQUA . "•Dein Geld•");
       $form->addButton(T::YELLOW . "•Uberweisen•");
       $form->addButton(T::GOLD . "•Sehe Geld•");
       $form->addButton(T::AQUA . "•Sehe Wer Am Meisten Geld Hat•");  
       $form->addButton(T::RED . "•GeldUI Schlissen•");
       $form->sendToPlayer($sender);
     }

public function mymoney(Player $sender){
                $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$f = $api->createCustomForm(function(Player $player, ?array $data){
		});
                $economy = EconomyAPI::getInstance();
                $mny = $economy->myMoney($sender);
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addLabel(T::YELLOW . "Dein Geld : $mny");
		$f->sendToPlayer($sender);
	     }
public function pay(Player $sender){
               $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
	       $f = $api->createCustomForm(function(Player $sender, ?array $data){
                if(!isset($data)) return;
		 $this->main->getServer()->getCommandMap()->dispatch($sender, "pay $data[0] $data[1]");
	    });
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addInput("Spieler Name", "GucciEnte128");
                $f->addInput("Wieviel", "1000");
		$f->sendToPlayer($sender);
	     }
public function see(Player $player){
		$api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$f = $api->createCustomForm(function(Player $player, ?array $data){
			if(!isset($data)) return;
			if($this->main->getServer()->getOfflinePlayer($data[0])->hasPlayedBefore() || $this->main->getServer()->getOfflinePlayer($data[0])->isOnline() && EconomyAPI::getInstance()->myMoney($data[0]) !== null){
				$this->seeForm($player, $data[0]);
			}else{
				$player->sendMessage(T::RED . "Spieler Ist Offline");
			}
		});
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addInput("Spieler Name", "GucciEnte128");
		$f->sendToPlayer($player);
	}
public function seeForm(Player $player, string $player1){
		$api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$f = $api->createCustomForm(function(Player $player, ?array $data){
		});
                $economy = EconomyAPI::getInstance();
                $mny = $economy->myMoney($player1);
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addLabel(T::YELLOW . "Geld : $mny/n");
		$f->sendToPlayer($player);
	}
public function top(Player $player){
		$money = $this->main->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$money_top = $money->getAllMoney();
		$message = "";
		if(count($money_top) > 0){
			arsort($money_top);
			$i = 1;
			foreach($money_top as $name => $money){
				$message .= "  §f".$i.". ".$name.":    ".$money." §a$"."\n";
				if($i >= 10){
					break;
					}
					++$i;
				}}
	    $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
				}
				switch($result){
					case "0";
					break;
				}
			});
			$form->setTitle(T::GREEN . "Top 10 Reicheste Spieler");
			$form->setContent("".$message);
	                $form->addButton("ok");
			$form->sendToPlayer($player);
			return $form;
	}
   public function opForm(Player $sender){
	        $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function(Player $sender, ?int $data){
			if(!isset($data)) return;
			switch($data){
			case 0:
                            $this->reduce($sender);
			    break;
                        case 1:
                            $this->add($sender);
                            break;
                        case 2:
                            $this->set($sender);
                            break;
                        case 3:
                            break;
            }
          });
       $form->setTitle(T::GREEN . "GeldUI");
       $form->addButton(T::AQUA . "•Reduziere Geld•");
       $form->addButton(T::YELLOW . "•Gebe Geld Andern•");
       $form->addButton(T::GOLD . "•Setzte Geld•");  
       $form->addButton(T::RED . "•Verlassen•");
       $form->sendToPlayer($sender);
     }
public function reduce(Player $sender){
               $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
	       $f = $api->createCustomForm(function(Player $sender, ?array $data){
                if(!isset($data)) return;
		 $this->main->getServer()->getCommandMap()->dispatch($sender, "takemoney $data[0] $data[1]");
	    });
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addInput("Spieler Name", "GucciEnte128");
                $f->addInput("Wieviel", "1000");
		$f->sendToPlayer($sender);
	     }
public function add(Player $sender){
               $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
	       $f = $api->createCustomForm(function(Player $sender, ?array $data){
                if(!isset($data)) return;
		 $this->main->getServer()->getCommandMap()->dispatch($sender, "givemoney $data[0] $data[1]");
	    });
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addInput("Spieler Name", "GucciEnte128");
                $f->addInput("Wieviel", "1000");
		$f->sendToPlayer($sender);
	     }
/**public function drop(Player $sender){
		$api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
		$f = $api->createCustomForm(function(Player $player, ?array $data){
			if(!isset($data)) return;
			if($this->main->getServer()->getOfflinePlayer($this->onlinepy[$data[0]])->hasPlayedBefore() || $this->main->getServer()->getOfflinePlayer($this->onlinepy[$data[0]])->isOnline()){
				$this->drop1($player, $this->onlinepy[$data[0]]);
			}else{
				$player->sendMessage(T::RED . "Spieler Ist Offline");
			}
		});
		$onlinepy = [];
		foreach($this->main->getServer()->getOnlinePlayers() as $onlinePlayer){
			array_push($onlinepy, $onlinePlayer->getName());
		}
		$this->onlinepy = $onlinepy;
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addDropdown(T::LIGHT_PURPLE . "Wahle Ein Spieler", $onlinepy);
		$f->sendToPlayer($sender);
	}*/
public function set(Player $sender){
               $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");
	       $f = $api->createCustomForm(function(Player $sender, ?array $data){
                if(!isset($data)) return;
		 $this->main->getServer()->getCommandMap()->dispatch($sender, "setmoney $data[0] $data[1]");
	    });
		$f->setTitle(T::GREEN . "GeldUI");
		$f->addInput("Spieler Name", "GucciEnte128");
                $f->addInput("Wieviel", "1000");
		$f->sendToPlayer($sender);
	     }
}
