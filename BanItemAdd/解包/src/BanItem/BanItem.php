<?php
namespace BanItem;

use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\block\BlockPlaceEvent;

class BanItem extends PluginBase implements Listener{
	
	private $path,$conf;
	
	public function onEnable(){ 
		$this->path = $this->getDataFolder();
		@mkdir($this->path);
		$this->conf = new Config($this->path."Config.yml", Config::YAML,array(
				"ban-item"=>array(),
				"ban-item-admin"=>array()
				));
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info("插件加載成功 ! §7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
	}
	
	public function itemheld(PlayerItemHeldEvent  $event){
	    $this->permission($event);
	}
	
	public function playerinteract(PlayerInteractEvent  $event){
	    $this->permission($event);
	}
	
	public function blockplace(BlockPlaceEvent  $event){
	    $this->permission($event);
	}	
	
	public function permission($event){
	    $player = $event->getPlayer();
	    $user = $player->getName();
		$item = $event->getItem()->getID();
		$ban_item = $this->conf->get("ban-item");
		$admin = $this->conf->get("ban-item-admin");
	    if((in_array($item,$ban_item)) and (!in_array($user,$admin))){
		$player->sendMessage("[Ban-Item] 對不起，該物品被管理員禁用了 \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
		$event->setCancelled(true);
		}
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		$user = $sender->getName();
		switch($command->getName()){
			case "banitem":
				if(isset($args[0])){
					switch($args[0]){
					case "item":
						if(isset($args[1])){
						if($args[1] > 0){
						$ban_item = $this->conf->get("ban-item");
						if(!in_array($args[1],$ban_item)){
						$ban_item[]=$args[1];
						$this->conf->set("ban-item",$ban_item);
						$this->conf->save();
						$sender->sendMessage("[Ban-Item] 成功添加禁用物品ID: $args[1] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}else{
						$inv = array_search($args[1], $ban_item);
						$inv = array_splice($ban_item, $inv, 1); 
						$this->conf->set("ban-item",$ban_item);
						$this->conf->save();
						$sender->sendMessage("[Ban-Item] 成功刪除禁用物品ID: $args[1] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}}else{
						$sender->sendMessage("[Ban-Item] 請輸入有效物品ID \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");}
						}else{
						$sender->sendMessage("[Ban-Item] 用法 ：/banitem item [num] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}
						return true;
					case "admin":
						if(isset($args[1])){
						$ban_item_admin = $this->conf->get("ban-item-admin");
						if(!in_array($args[1],$ban_item_admin)){
						$ban_item_admin[] = $args[1];
						$this->conf->set("ban-item-admin",$ban_item_admin);
						$this->conf->save();
						$sender->sendMessage("[Ban-Item] 成功添加管理員: $args[1] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}else{
						$inv = array_search($args[1], $ban_item_admin);
						$inv = array_splice($ban_item_admin, $inv, 1); 
						$this->conf->set("ban-item-admin",$ban_item_admin);
						$this->conf->save();
						$sender->sendMessage("[Ban-Item] 成功刪除管理員: $args[1] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}}else{
						$sender->sendMessage("[Ban-Item] 用法 ：/banitem admin [ID] \n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)");
						}
						return true;
					case "list":
					    $ban_item = $this->conf->get("ban-item");
						$ban_item_admin = $this->conf->get("ban-item-admin");					
					    $banitem="禁用物品列表\n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)：";
						$banitemadmin="管理員列表\n§7翻譯：旋風之音 (http://PocketMinePlugins.reh.tw)：";
						$banitem .=implode(",",$ban_item);
						$banitemadmin .=implode(",",$ban_item_admin);
						$sender->sendMessage("[Ban-Item] $banitem");
						$sender->sendMessage("[Ban-Item] $banitemadmin");
					    return true;
					}
					
				
				}		
		}
	}
}