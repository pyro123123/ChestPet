<?php

namespace CaptMusix\chestPet\util;

use pocketmine\{
  Player,
  item\Item,
  item\enchantment\Enchantment,
  item\enchantment\EnchantmentInstance,
  inventory\Inventory
};

use CaptMusix\chestPet\Main;

use muqsit\invmenu\{
  InvMenu,
  transaction\InvMenuTransaction,
  transaction\InvMenuTransactionResult
};

class inv {
   
  private $name;
  private $player;
  private $listener;
  private $inv;
  private $inv2;
  
 public function __construct(String $name,Player $player) {
    $this->name = $name;
    $this->player = $player;
    
    $this->inv = InvMenu::create(InvMenu::TYPE_CHEST);
    $this->inv2 = InvMenu::create(InvMenu::TYPE_CHEST);

  }
  
  public function starter() {
    $existInv = $this->_getCfg()->getPlayerInv($this->player->getName());
    
    // Send selected Inventory Menu
    if(count($existInv) !== 0) {
     
      $this->inv->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult { 
      
      $itemClicked = $transaction->getItemClickedWith();
      
   	if($itemClicked->hasCustomBlockData()) {
               
      if($itemClicked->getCustomBlockData()->hasTag("chest_pet")) {
                       
           return $transaction->discard();
             }
           }
    if($transaction->getOut()->getCustomName() == "Create empty inventory") {
      $this->create();
      return $transaction->discard();
    }
                 	
        $this->remove($transaction);
        $this->playerInv($transaction->getAction()->getSlot());
        
          return $transaction->discard();
      });
      
      $this->available();

      return;
    }
    
    // First time inventory
    $this->inv->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult{ 
                                    
     $itemClicked = $transaction->getItemClickedWith();
                                          
  	if($itemClicked->hasCustomBlockData()) {
       
       if($itemClicked->getCustomBlockData()->hasTag("chest_pet")) {
               
          return $transaction->discard();
             }
         	}
             	
        return $transaction->continue();
        });
  
     $this->inv->setInventoryCloseListener(function(Player $p,Inventory $inv) {
        $this->_getCfg()->update($p->getName(),$inv->getContents());
      });
      
    $this->inv->setName("Empty Inventory");
    $this->inv->send($this->player);
  }
  
  // Setup selected inventory item
  public function playerInv($slot) {
    
    $ctn = Main::getCfg()->getData($this->player->getName()." #".$slot + 1) !== false  ? $this->changeItem(Main::getCfg()->getData($this->player->getName()." #".$slot + 1)) : [];
    
    $this->inv2->getInventory()->setContents($ctn);
    $this->inv2->setName($this->player->getName()." #".$slot + 1);
   
   $this->inv2->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult{ 
                                          
     $itemClicked = $transaction->getItemClickedWith();
     
   	if($itemClicked->hasCustomBlockData()) {
             
         if($itemClicked->getCustomBlockData()->hasTag("chest_pet")) {
                     
            return $transaction->discard();
             }
         	}
                   	
         return $transaction->continue();
       });
           
    $this->inv2->setInventoryCloseListener(function(Player $p,Inventory $inv) {
      $ctn = $inv->getContents();
      $this->_getCfg()->update($this->inv2->getName(),$ctn);
    });
    
    $this->inv2->send($this->player);
    
  }
  
  // Setup Selected Inventory Menu
  private function available() {
    $this->inv->setName($this->name);
   $create = Item::get(Item::EMERALD_BLOCK);
   $create->setCustomName("Create empty inventory");
   
   $this->_getInv()->setItem(26,$create);
    $count = 1;
    $getAll = $this->_getCfg()->getPlayerInv($this->player->getName());
    $lore = [];
    
    foreach ($getAll as $i) {
      $chest = Item::get(Item::CHEST);
      $chest->setCustomName("§a".$this->player->getName()." #$count");
      
      // Set lore to item that in it
      if(count($i) !== 0) {
      
       foreach ($i as $v) {
           array_push($lore,"§6• ".$v["name"]." x".$v["size"]);
        } 
         
        } else {
          $lore[] = "§4• No Item";
       }
      
     $count++;
     $chest->setLore($lore);
     $this->_getInv()->setItem($this->_getInv()->firstEmpty(),$chest);
     $lore = [];
     
    }
     
    $this->inv->send($this->player);
    
  }
  
  // Create new inventory
  private function create() {
    $old = $this->_getCfg()->getPlayerInv($this->player->getName());
    if(count($old) >= 26) {
      $this->player->sendMessage("§4Unable to create new inventory\nDo /cp delete <number> to remove inventory");
      return;
    }
    
    $this->inv2->setName($this->player->getName()." #".count($old) + 1);
   
    $this->inv2->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult{ 
                                       
        $itemClicked = $transaction->getItemClickedWith();
                                             
     	if($itemClicked->hasCustomBlockData()) {
          
          if($itemClicked->getCustomBlockData()->hasTag("chest_pet")) {
                  
             return $transaction->discard();
                }
            	}
                	
          return $transaction->continue();
        });
   
    $this->inv2->setInventoryCloseListener(function(Player $p,Inventory $inv) {
          $ctn = $inv->getContents();
          $this->_getCfg()->update($this->player->getName(),$ctn);
       });
        
    $this->inv2->send($this->player);
    
  }
  
  private function changeItem($item) {
    $items = [];
    
    foreach ($item as $i) {
    
      $itm = Item::get($i["id"]);
      
      // Set the custom name
      if($itm->getVanillaName() !== $i["name"]) {
        $itm->setCustomName($i["name"]);
      }
      
     // Set enchant if exist
      if(count($i["ce"]) !== 0) {
        foreach ($i["ce"] as $c) {
          $ce = new EnchantmentInstance(Enchantment::getEnchantment($c["id"]),$c["level"]);
          $itm->addEnchantment($ce);
        }
      }
      
      $itm->setCount($i["size"]);
      
      array_push($items,$itm);
      
    }
    
    return $items;
  }
  
  private function _getCfg() {
    return Main::getCfg();
  }
  
  private function _getInv() {
      return $this->inv->getInventory();
  }
 
  private function remove($trans) {
    $this->player->removeWindow($trans->getAction()->getInventory());
  }
  
}