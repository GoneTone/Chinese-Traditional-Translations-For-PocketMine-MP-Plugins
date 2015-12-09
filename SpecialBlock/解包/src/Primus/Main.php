<?php

namespace Primus;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use Primus\Timer;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\entity\Effect;
use pocketmine\utils\Config;
use pocketmine\Player;

class Main extends PluginBase implements Listener{
	
	private $cfg;
	private $blockCmds;
	private $killedByBlock;
	private $damageBlock;
	private $healingBlock;
	private $effectBlock;
	private $blockClass;
	private $list;
	private $interval;
	
	public function onEnable(){
		$this->interval = $this->getConfig()->get('interval');
		$this->cfg = $this->getConfig();
		$this->damageBlock = $this->getConfig()->get('damage-block');
		$this->healingBlock = $this->getConfig()->get('healing-block');
		$this->effectBlock = $this->getConfig()->get('effect-block');
		$this->killedByBlock = false;
		$default = array("x:y:z:world" => array(
		"- cmd1"
		));
		// -------------------------------------------------------------
		$this->blockCmds = new Config($this->getDataFolder()."block_cmds.yml", Config::YAML, $default);
		$this->getLogger()->info("??");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->reloadConfig();
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new Timer($this), $this->interval);
	}
	public function onDisable(){
		$this->getLogger()->info("??");
		$this->saveDefaultConfig();
		
	}
	
	public function checkBlock($player, $x, $y, $z){
		$pos = new Vector3($x, $y - 1, $z);
		$blockId = $player->getLevel()->getBlock($pos);
		//$this->getLogger()->info($blockId." is on pos".$x."-".$y."-".$z."");
		$world = $player->getLevel()->getName();
		if($blockId instanceof Block){
			$this->getLogger($pos);
			if($blockId->getId() === Block::get($this->damageBlock)->getId()){
			//	$this->getLogger()->info($blockId." is on pos".$x."-".$y."-".$z."");
				$this->doDamage($player);
				return true;
			}elseif($blockId->getId() === Block::get($this->healingBlock)->getId()){
				$this->healPlayer($player);
				return true;
			}elseif($blockId->getId() === Block::get($this->effectBlock)->getId()){
				$this->giveEffect($player);
				return true;
			}elseif($this->isCommandBlock($x, $y, $z, $world)){
				$this->executeCmds($x, $y, $z, $world, $player);
				return true;
				}else{
				return true;
				}
		}else{
			//$this->getLogger()->info($blockId." is not a block on pos".$x."-".$y."-".$z."");
		}
	}
	
	public function doDamage($player){
		$damage = $this->getConfig()->get('damage');
		$currentHealth = $player->getHealth();
		$finalDmg = $currentHealth - $damage;
		if($currentHealth - $damage <= 0){
			$this->killedByBlock = true;
			$player->setHealth($finalDmg);
		}else{
			$player->setHealth($finalDmg);
		}
	}
	
	public function onDeath(PlayerDeathEvent $e){
		$msg = $this->getConfig()->get("death-message");
		$msg = str_replace("{PLAYER}", $e->getEntity()->getName(), $msg);
		$msg = str_replace("{BLOCK}", strtolower($this->getConfig()->get('damage-block-name')), $msg);
		if($this->killedByBlock){
			if($this->getConfig()->get("broadcast-on-chat") === false){
				foreach($this->getServer()->getOnlinePlayers() as $allP){
					$allP->sendPopup('/n');
					$allP->sendPopup($msg);
					unset($this->killedByBlock);
					$e->setDeathMessage(null);
				}
			}else{
			$e->setDeathMessage($msg);
			unset($this->killedByBlock);
			}

		}else{
			}
	}
	
	public function healPlayer($player){
		$currentHealth = $player->getHealth();
		$hpGain = $this->getConfig()->get('hp-gain');
		$msg = $this->getConfig()->get('healing-message');
		$player->sendPopup($msg);
		$player->setHealth($currentHealth + $hpGain);
	}
	
	public function giveEffect($player){
		$cfg = $this->getConfig();
        $id = $cfg->get('effect-id');
        $amplifier = $cfg->get('effect-amplifier');
        $visibility = $cfg->get('effect-visibility');
        $duration = $cfg->get('effect-duration');
        $effect = Effect::getEffect($id);
      
      if($effect != null){
        $effect->setVisible($visibility);
        $effect->setDuration($duration);
        $effect->setAmplifier($amplifier);
        $player->addEffect($effect);
        if($cfg->get('send-message-on-recieve')){
			$player->sendMessage($cfg->get('effect-message'));
		}elseif($cfg->get('send-popup-on-recieve')){
			$player->sendPopup($cfg->get('effect-popup'));
		}else{
			
			}
		}else{
			$this->getLogger()->info('§4???????\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
			$this->getLogger()->info('effect-id: '.$this->getConfig()->get('effect-id').' - ??');
			}
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "sb":
			if(isset($args[0])){
				switch($args[0]){
					case "get":
					if(isset($args[1])){
						if($this->getConfig()->get($args[1])){
							$value = $this->getConfig()->get($args[1]);
							$key = $args[1];
							$sender->sendMessage($key.": ". $value);
							return true;
						}else{
							$sender->sendMessage('§4Key ???\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
							return true;
							}
					}
					case "set":
					if(isset($args[1])){
						if($this->getConfig()->get($args[1])){
							if(isset($args[2])){
								$this->getConfig()->set($args[1], $args[2]);
								$this->getConfig()->save();
								$sender->sendMessage('Key ??\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
								$sender->sendMessage($args[1].": ". $args[2]);
								return true;
							}else{
								$sender->sendMessage('Enter value for '. $args[1]);
								return false;
								}
						}else{
							$sender->sendMessage('Key ???\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
							return true;
							break;
							}
					}else{
						$sender->sendMessage('???????? key\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
						return false;
						}
					case "reload":
					$this->getConfig()->reload();
					$sender->sendMessage('???????\n7??:PocketMine-MP ??????? (http://PocketMinePlugins.reh.tw)');
					return true;
					case "list":
					$list = $this->getConfig()->getAll();
					foreach($list as $key => $value){
						$sender->sendMessage("$key: $value");
					}
					return true;
				}
				
				unset($this->list);
				return true;
				}
			}
		}
		
		public function isCommandBlock($x, $y, $z, $world){
			$needle = $x."_".$y."_".$z."_".$world;
			$commandBlock = $this->blockCmds->get($needle);
			if($commandBlock){
				return true;
			}else{
				return false;
				}
		}
		
		public function executeCmds($x, $y, $z, $world, $player){
			$needle = $x."_".$y."_".$z."_".$world;
			$commands = $this->blockCmds->get($needle);
			foreach($commands as $command){
			$this->getServer()->dispatchCommand($player, $command);
		//	$this->getLogger()->info("$needle runned command: $command. For $player");
		}
		}
		
	}
