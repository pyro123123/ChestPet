<?php

namespace CaptMusix\chestPet\command;

use pocketmine\{
  command\Command,
  command\CommandSender,
  item\ItemFactory,
  item\ItemIds,
  nbt\tag\CompoundTag,
  nbt\tag\ListTag,
  Player
};

use CaptMusix\chestPet\Main;

class giveCP extends Command {
  
  public function __construct() {
    parent::__construct("cp","Give yourself chest pet");
  }
  
  public function execute(CommandSender $sender,string $label,array $args) {

  if(!$sender instanceof Player) {
    $sender->sendMessage("This command need to be run in-game");
    return;
  }
  
   $inv = $sender->getInventory();
   $item = $this->pet();
   $inv->setItem($inv->firstEmpty(),$item);
  
   $sender->sendMessage("§aYou have received 1 Chest Pet");
    
    
  }
  
  public function pet() {
    $result = ItemFactory::getInstance()->get(ItemIds::CHEST);
    $result->setCustomName("§bChest Pet");
    $tag = new CompoundTag();
    $tag->setTag("pet",new ListTag("chest_pet"));
    $result->setCustomBlockData($tag);
    $result->setNamedTag(new ListTag("ench"));
    
    return $result;
  }
  
}