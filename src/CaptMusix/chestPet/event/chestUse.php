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
  Main,
  util\inv
};

class chestUse implements Listener {
 
 
 public function onInteract(PlayerInteractEvent $e) {
    
    $hand = $e->getItem();
    $player = $e->getPlayer();
    
   if($hand->getId() === Item::CHEST && $hand->hasCustomBlockData()) {
     $data = $hand->getCustomBlockData()->hasTag("chest_pet");
    
     if($data) {
       
      $inv = new inv("Choose Inventory",$player);
      
      $inv->starter();
      
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
  
}