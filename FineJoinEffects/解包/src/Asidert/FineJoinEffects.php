<?php

namespace Asidert;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Effect;
use pocketmine\entity\InstantEffect;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use Asidert\FineTask;

class FineJoinEffects extends PluginBase implements Listener {
	
	public function onEnable() {
          $this->saveDefaultConfig();
          $this->reloadConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);				
		$this->getLogger()->info(TextFormat::GREEN . "Activation FineJoinEffects by Asidert");
  }

  public function onRespawn(PlayerRespawnEvent $event) {
        $cg=$this->getConfig();
        $enablerecieveafterdeath=$cg->get("Enable-Receive-After-Death");
     if($enablerecieveafterdeath==true){
        $p=$event->getPlayer();
        $this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"Receive"],[$p]),0);
    }
}

  public function onJoin(PlayerJoinEvent $event) {
        $cg=$this->getConfig();
        $enablerecieveafterdeath=$cg->get("Enable-Receive-After-Death");
     if($enablerecieveafterdeath==false){
        $p=$event->getPlayer();
        $this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"Receive"],[$p]),0);
    }
}

  public function Receive(Player $p) {
    if($p->hasPermission("finejoineffects.player")) {
        $cg=$this->getConfig();
        $jnmsg=$cg->get("Enable-Message-With-Join");
        $eft1=$cg->get("Effect-1-Type");
        $dtn1=$cg->get("Effect-1-Duration");
        $eft2=$cg->get("Effect-2-Type");
        $dtn2=$cg->get("Effect-2-Duration");
        $eft3=$cg->get("Effect-3-Type");
        $dtn3=$cg->get("Effect-3-Duration");
        $eft4=$cg->get("Effect-4-Type");
        $dtn4=$cg->get("Effect-4-Duration");
        $eft5=$cg->get("Effect-5-Type");
        $dtn5=$cg->get("Effect-5-Duration");
        $amp1=$cg->get("Effect-1-Amplifier");
        $amp2=$cg->get("Effect-2-Amplifier");
        $amp3=$cg->get("Effect-3-Amplifier");
        $amp4=$cg->get("Effect-4-Amplifier");
        $amp5=$cg->get("Effect-5-Amplifier");
        $enb2=$cg->get("Enable-2-Effect");
        $enb3=$cg->get("Enable-3-Effect");
        $enb4=$cg->get("Enable-4-Effect");
        $enb5=$cg->get("Enable-5-Effect");
        $msg=$cg->get("Message-With-Effects");
        $effect = Effect::getEffect($eft1);
        $effect->setVisible(true);
        $effect->setDuration($dtn1)->setAmplifier($amp1);
        $p->addEffect($effect);
    if($enb2 == true){
        $effect = Effect::getEffect($eft2);
        $effect->setVisible(true);
        $effect->setDuration($dtn2)->setAmplifier($amp2);
        $p->addEffect($effect);
        }
    if($enb3 == true){
        $effect = Effect::getEffect($eft3);
        $effect->setVisible(true);
        $effect->setDuration($dtn3)->setAmplifier($amp3);
        $p->addEffect($effect);
        }
    if($enb4 == true){
        $effect = Effect::getEffect($eft4);
        $effect->setVisible(true);
        $effect->setDuration($dtn4)->setAmplifier($amp4);
        $p->addEffect($effect);
        }
    if($enb5 == true){
        $effect = Effect::getEffect($eft5);
        $effect->setVisible(true);
        $effect->setDuration($dtn5)->setAmplifier($amp5);
        $p->addEffect($effect);
        }
    if($jnmsg == true){
        $p->sendMessage("§l§b[FineJoinEffect]§a $msg");
  }
 }
    if($p->hasPermission("finejoineffects.vip")) {
        $cg=$this->getConfig();
        $p=$event->getPlayer();
        $jnmsgv=$cg->get("Enable-Message-With-VIP-Join");
        $eft1v=$cg->get("Effect-1-VIP-Type");
        $dtn1v=$cg->get("Effect-1-VIP-Duration");
        $eft2v=$cg->get("Effect-2-VIP-Type");
        $dtn2v=$cg->get("Effect-2-VIP-Duration");
        $eft3v=$cg->get("Effect-3-VIP-Type");
        $dtn3v=$cg->get("Effect-3-VIP-Duration");
        $eft4v=$cg->get("Effect-4-VIP-Type");
        $dtn4v=$cg->get("Effect-4-VIP-Duration");
        $eft5v=$cg->get("Effect-5-VIP-Type");
        $dtn5v=$cg->get("Effect-5-VIP-Duration");
        $amp1v=$cg->get("Effect-1-VIP-Amplifier");
        $amp2v=$cg->get("Effect-2-VIP-Amplifier");
        $amp3v=$cg->get("Effect-3-VIP-Amplifier");
        $amp4v=$cg->get("Effect-4-VIP-Amplifier");
        $amp5v=$cg->get("Effect-5-VIP-Amplifier");
        $enb2v=$cg->get("Enable-2-VIP-Effect");
        $enb3v=$cg->get("Enable-3-VIP-Effect");
        $enb4v=$cg->get("Enable-4-VIP-Effect");
        $enb5v=$cg->get("Enable-5-VIP-Effect");
        $msgv=$cg->get("Message-With-VIP-Effects");
        $effect = Effect::getEffect($eft1v);
        $effect->setVisible(true);
        $effect->setDuration($dtn1v)->setAmplifier($amp1v);
        $p->addEffect($effect);
    if($enb2v == true){
        $effect = Effect::getEffect($eft2v);
        $effect->setVisible(true);
        $effect->setDuration($dtn2v)->setAmplifier($amp2v);
        $p->addEffect($effect);
        }
    if($enb3v == true){
        $effect = Effect::getEffect($eft3v);
        $effect->setVisible(true);
        $effect->setDuration($dtn3v)->setAmplifier($amp3v);
        $p->addEffect($effect);
        }
    if($enb4v == true){
        $effect = Effect::getEffect($eft4v);
        $effect->setVisible(true);
        $effect->setDuration($dtn4v)->setAmplifier($amp4v);
        $p->addEffect($effect);
        }
    if($enb5v == true){
        $effect = Effect::getEffect($eft5v);
        $effect->setVisible(true);
        $effect->setDuration($dtn5v)->setAmplifier($amp5v);
        $p->addEffect($effect);
        }
    if($jnmsgv == true){
        $p->sendMessage("§l§b[FineJoinEffect]§a $msgv");
  }
 }
}
	public function onDisable() {
		$this->getLogger ()->info (TextFormat::RED . "Deactivation FineJoinEffects by Asidert" );
          $this->saveDefaultConfig();
	}
}