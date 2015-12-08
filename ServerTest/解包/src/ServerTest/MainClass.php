<?php

namespace ServerTest;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MainClass extends PluginBase implements Listener{

	public function onLoad(){
		$this->getLogger()->info(TextFormat::WHITE . "加載！ §7翻譯：PocketMine-MP 伺服插件資源網 (http://PocketMinePlugins.reh.tw)");
	}

	public function onEnable(){
		$this->getLogger()->info(TextFormat::DARK_GREEN . "啟用！ §7翻譯：PocketMine-MP 伺服插件資源網 (http://PocketMinePlugins.reh.tw)");
    }

	public function onDisable(){
		$this->getLogger()->info(TextFormat::DARK_RED . "停用！ §7翻譯：PocketMine-MP 伺服插件資源網 (http://PocketMinePlugins.reh.tw)");
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "servertest":
				$sender->sendMessage("伺服器測試參照對像 \t".TextFormat::YELLOW."整數運算能力檢測(1+1運算300萬次) \t".TextFormat::WHITE."浮點運算能力檢測(圓周率開平方300萬次) \t".TextFormat::YELLOW."數據I/O能力檢測(讀取10K文件1萬次) \t".TextFormat::WHITE."CPU信息");
				$sender->sendMessage("美國 LinodeVPS \t".TextFormat::YELLOW." 0.357秒				\t".TextFormat::WHITE." 0.802秒				\t".TextFormat::YELLOW." 0.023秒\t".TextFormat::WHITE." 4 x Xeon L5520 @ 2.27GHz");
				$sender->sendMessage("美國 PhotonVPS.com \t".TextFormat::YELLOW." 0.431秒				\t".TextFormat::WHITE." 1.024秒				\t".TextFormat::YELLOW." 0.034秒\t".TextFormat::WHITE." 8 x Xeon E5520 @ 2.27GHz");
				$sender->sendMessage("德國 SpaceRich.com \t".TextFormat::YELLOW." 0.421秒				\t".TextFormat::WHITE." 1.003秒				\t".TextFormat::YELLOW." 0.038秒\t".TextFormat::WHITE." 4 x Core i7 920 @ 2.67GHz");
				$sender->sendMessage("美國 RiZie.com \t".TextFormat::YELLOW." 0.521秒				\t".TextFormat::WHITE." 1.559秒				\t".TextFormat::YELLOW." 0.054秒\t".TextFormat::WHITE." 2 x Pentium4 3.00GHz");
				$sender->sendMessage("埃及 CitynetHost.com \t".TextFormat::YELLOW." 0.343秒				\t".TextFormat::WHITE." 0.761秒				\t".TextFormat::YELLOW." 0.023秒\t".TextFormat::WHITE." 2 x Core2Duo E4600 @ 2.40GHz");
				$sender->sendMessage("美國 IXwebhosting.com \t".TextFormat::YELLOW." 0.535秒				\t".TextFormat::WHITE." 1.607秒				\t".TextFormat::YELLOW." 0.058秒\t".TextFormat::WHITE." 4 x Xeon E5530 @ 2.40GHz");
				$sender->sendMessage(TextFormat::GREEN."你的伺服器	\t".TextFormat::RED." ".$this->test_int()."				\t ".$this->test_float()."				\t ".$this->test_io());
				$sender->sendMessage("§7翻譯：PocketMine-MP 伺服插件資源網 (http://PocketMinePlugins.reh.tw)");
				return true;
			default:
				return false;
		}
	}

	//整數運算能力測試
	private function test_int(){
		$timeStart = gettimeofday();
		$t = 0;
		for($i = 0; $i < 3000000; $i++)
		{
			$t = 1 + 1;
		}
		$timeEnd = gettimeofday();
		$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
		$time = round($time, 3)."秒";
		return $time;
	}



	//浮點運算能力測試
	private function test_float(){
		//得到圓周率值
		$t = pi();
		$timeStart = gettimeofday();
		for($i = 0; $i < 3000000; $i++)
		{
			//開平方
			sqrt($t);
		}
		$timeEnd = gettimeofday();
		$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
		$time = round($time, 3)."秒";
		return $time;
	}



	//IO能力測試
	private function test_io(){
		$fp = @fopen(PHPSELF, "r");
		$timeStart = gettimeofday();
		for($i = 0; $i < 10000; $i++) 
		{
			@fread($fp, 10240);
			@rewind($fp);
		}
		$timeEnd = gettimeofday();
		@fclose($fp);
		$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
		$time = round($time, 3)."秒";
		return($time);
	}
}
