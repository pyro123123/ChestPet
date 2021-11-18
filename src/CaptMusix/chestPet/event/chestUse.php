<?php

namespace CaptMusix\chestPet\event;

use pocketmine\{
  Player,
  event\Listener,
  event\player\PlayerInteractEvent,
  event\block\BlockPlaceEvent,
  item\Item,
  inventory\Inventory,
  item\enchantment\EnchantmentInstance,
  item\enchantment\Enchantment
};

use muqsit\invmenu\{
  InvMenu,
  transaction\InvMenuTransaction,
  transaction\InvMenuTransactionResult
};

use CaptMusix\chestPet\{
  Main
};

class chestUse implements Listener {
 
 
 public function onInteract(PlayerInteractEvent $e) {
    
    $hand = $e->getItem();
    $player = $e->getPlayer();
    
   if($hand->getId() === Item::CHEST && $hand->hasCustomBlockData()) {
     $data = $hand->getCustomBlockData()->hasTag("chest_pet");
    
     if($data) {
      $this->menus($player);
     }
     
   }
    
  }

 public function onPlace(BlockPlaceEvent $e) {
     $hand = $e->getItem();
     $player = $e->getPlayer();
       
      if($hand->getId() === Item::CHEST && $hand->hasCustomBlockData()) {
        $data = $hand->getCustomBlockData()->hasTag("chest_pet");
       
        if($data) {
           $e->setCancelled();
        }
        
      }
      
 }
 
 public function menus(Player $p) {
   $menu = InvMenu::create(InvMenu::TYPE_CHEST);
   
   $content = Main::getCfg()->getData($p->getName()) !== false  ? $this->changeItem(Main::getCfg()->getData($p->getName())) : [];
  
   $inv = $menu->getInventory();
   $inv->setContents($content);
   $menu->setName($p->getName()." pet");
   
   $oldData = count($content);
   
   $menu->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult{
   
   	$itemClicked = $transaction->getItemClickedWith();
   
   	//if try to change the pet in player inventory into the chest inventroy return
   	
   	if($itemClicked->hasCustomBlockData()) {
   	  if($itemClicked->getCustomBlockData()->hasTag("chest_pet")) {
   	    return $transaction->discard();
   	  }
   	}
   	return $transaction->continue();
   });
   
 
  $menu->setInventoryCloseListener(function(Player $p,Inventory $inv)use(&$oldData) {
 
    $ctn = $inv->getContents();
  
    Main::getCfg()->setData($p->getName(),$ctn);
   
  });
  
   $menu->send($p);
 }
 
 public function changeItem($item) {
  $items = [];
  foreach ($item as $i) {
   $Item = Item::get($i["id"]);
   if($Item->getVanillaName() !== $i["name"]) {
   $Item->setCustomName($i["name"]);
   }
   
   $Item->setCount($i["size"]);
   
   if(count($i["ce"]) !== 0) {
     foreach ($i["ce"] as $c) {
       $ce = new EnchantmentInstance(Enchantment::getEnchantment($c["id"]),$c["level"]);
       $Item->addEnchantment($ce);
     }
   }
   
   array_push($items,$Item);
  }
  
   return $items;
 }
  
}