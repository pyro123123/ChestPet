<?php

namespace CaptMusix\chestPet\command;

use pocketmine\{
  command\Command,
  command\CommandSender,
  item\Item,
  nbt\tag\CompoundTag,
  nbt\tag\ListTag
};

use CaptMusix\chestPet\Main;

class giveCP extends Command {
  
  public function __construct() {
    parent::__construct("cp","Give yourself chest pet");
  }
  
  public function execute(CommandSender $sender,string $label,array $args) {

   $inv = $sender->getInventory();
   $item = $this->pet();
   $inv->setItem($inv->firstEmpty(),$item);
  
   $sender->sendMessage("§aYou have received 1 Chest Pet");
    
  }
  
  public function pet() {
    $result = Item::get(Item::CHEST);
    $result->setCustomName("§bChest Pet");
    $tag = new CompoundTag();
    $tag->setTag(new ListTag("chest_pet"));
    $result->setCustomBlockData($tag);
    $result->setNamedTagEntry(new ListTag("ench"));
    
    return $result;
  }
  
}