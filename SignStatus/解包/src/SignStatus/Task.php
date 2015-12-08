<?php

namespace SignStatus;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server; 
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat as F;

class Task extends PluginTask{
	private $plugin;
	private $countable;
	public function __construct(SignStatus $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
		$this->countable = 0;
	}

    public function onRun($currentTick){
    	$val = $this->plugin->sign->get("sign")["enabled"];
		if($val == "true" || $val == true){
            foreach($this->plugin->getServer()->getDefaultLevel()->getTiles() as $tile){
                if($tile instanceof Sign){
                    if($tile->getText()[0] == F::GREEN."[狀態]"){
                        $tps = Server::getInstance()->getTicksPerSecond();
                        $p = count(Server::getInstance()->getOnlinePlayers());
                        $full = Server::getInstance()->getMaxPlayers();
                        $count = $this->countable++; //For debug
                        $load = $this->plugin->getServer()->getTickUsage();
                        $tile->setText(F::GREEN."[狀態]", F::YELLOW."TPS: [".$tps."]", F::AQUA."在線: ".F::GREEN.$p.F::WHITE."/".F::RED.$full."", F::GOLD."LOAD: ".F::DARK_BLUE.$load. " %");
                    }
                }
            }
		}
    }
}