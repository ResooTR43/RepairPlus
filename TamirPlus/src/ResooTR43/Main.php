<?php

namespace ResooTR43;

use pocketmine\plugin\{PluginBase, PluginCommand};
use pocketmine\{Player, Server};
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Durable;
use ResooTR43\Forms\{SimpleForm};
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
      $this->getLogger()->info("§aTamir Çalışıyor - ResooTR43");
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event){
      $esyalar = [/*kılıclar*/267, 268, 272, 276, 283,/*kazmalar*/ 257, 270, 274, 278, 285,/*baltalar*/ 258, 271, 275, 279, 286,/*kürekler*/ 256, 269, 273, 277, 284,/*çapalar*/ 290, 291, 292, 293, 294];
        $g = $event->getPlayer();
        foreach($esyalar as $esya){
          $item = $g->getInventory()->getItemInHand();
          if($item->getId() != 0){
           if($esya == $item->getId()){
            $item = $g->getInventory()->getItemInHand();
            $itemcan = $item->getMaxDurability() - $item->getDamage();
            if($item->getMaxDurability() - $item->getDamage() == 10){
                $this->TamirMesaj($g);
        }elseif($itemcan <= 9){
          $item = $g->getInventory()->getItemInHand();
          $itemcan = $item->getMaxDurability() - $item->getDamage();
          $g->sendPopUp("§cKırılmaya son §6".$itemcan." §cCan Kaldı");
        }
       }
      }
    }
    }

    public function TamirMesaj($p){
        $f = new SimpleForm(function(Player $p, $data = null){
            if(is_null($data)){
                return true;
            }
            switch($data){
                case 0:
                  if($p->hasPermission("vip.oto.tamir")){
                  $item = $p->getInventory()->getItemInHand();
                    $item->setDamage(0);
                    $p->getInventory()->setItemInHand($item);
                    $p->sendMessage("§f» §aKazma Ücretsiz Şekilde Tamir Edildi! Kazmaya Devam Edebilirsin!");
                  }else{
                    if(EconomyAPI::getInstance()->myMoney($p) >= 3000){
                      EconomyAPI::getInstance()->reduceMoney($p, 3000);
                      $item = $p->getInventory()->getItemInHand();
                      $item->setDamage(0);
                      $p->getInventory()->setItemInHand($item);
                      $p->sendMessage("§f» §aKazma 3000TL Karşılığında Tamir Edildi! Kazmaya Devam Edebilirsin!");
                    }
                  }
                break;
                case 1:
                break;
            }
        });
        $f->setTitle("§c§lUYARI!!");
        if($p->hasPermission("vip.oto.tamir")){
          $f->setContent("§3Kazmanın Canı Çok Azaldı, Yakında Kırılacak \nVIP'lere özel Ücretsiz Şekilde Olan Tamir Ayrıcalığı ile Etmek İstermisin?");
        }else{
          $f->setContent("§3Kazmanın Canı Çok Azaldı, Yakında Kırılacak Tamir Etmek İstermisin?");
        }
        $f->addButton("§aTamir Et");
        $f->addButton("§cÇıkış");
        $f->sendToPlayer($p);
    }
}