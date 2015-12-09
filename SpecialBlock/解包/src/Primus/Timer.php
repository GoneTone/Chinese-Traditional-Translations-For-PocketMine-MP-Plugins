<?php

namespace Primus;

use pocketmine\scheduler\PluginTask;
use pocketmine\server\Server;
use pocketmine\player\Player;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class Timer extends PluginTask{
  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function onRun($tick){
      foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
        $x = $p->getFloorX();
        $y = $p->getFloorY();
        $z = $p->getFloorZ();
        $this->plugin->checkBlock($p, $x, $y, $z);
      }
}
}
