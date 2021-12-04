<?php

namespace CaptMusix\chestPet\command;

use pocketmine\{
  command\Command,
  command\CommandSender,
  Server,
  Player
};

use CaptMusix\chestPet\Main;

use jojoe77777\FormAPI\{
  SimpleForm
};

class deleteInv extends Command {
  
  public function __construct() {
    parent::__construct("cpdel","Delete chest pet inventory");
  }
  
  public function execute(CommandSender $sender,string $label,array $args) {
    
    
    
    if(!$sender instanceof Player) {
      if(!isset($args[0])) {
       $sender->sendMesssge("§4/cpdel <player name> <inv number>");
            return;
      }
      
      if((!isset($args[1])) || !is_nan($args[1])) {
        $sender->sendMessage("§4<inv number> must be a number");
        return;
      }
      
      $this->onConsole($sender,$args[0],$args[1]);
      return;
    }
    
     if(!isset($args[0])) {
       $sender->sendMessage("§4/cpdel <inv number>");
          return;
    }
    
     $invs = $sender->getName()." #".$args[0];

    $data = Main::getCfg()->has($invs);
           
       if(!$data) {
         $sender->sendMessage("§4The inventory doesnt exist");
         return;
       }
       
   Main::getCfg()->deleteData($invs);
   
   $sender->sendMessage("§aYour #".$args[0]." inventory has been deleted");
  
    
    
  }
  
 public function onConsole($sender,String $name,$inv) {
   
    $target = Server::getInstance()->getPlayerExact($name);
    
    if(!$target) {
      $sender->sendMessage("§4Player with name $name cannot be found");
      return;
    }
    
    $invs = $target->getName()." #$inv";
    
    
   $data = Main::getCfg()->has($invs);
       
       if(!$data) {
         $sender->sendMessage("§4The inventory doesnt exist");
       }
    
    // Send delete confirmation menu if player online
    if($target->isOnline()) {
    $form = new SimpleForm(function($p,$data)use(&$inv,&$invs,&$sender) {
      if($data === null) return;
      
      if($data == 0) {
         Main::getCfg()->deleteData($invs);
         $p->sendMessage("§aYour #$inv inventory has been deleted");
        $sender->sendMessage("§aSuccesful deleted #$inv inventory");

      } else {
        $sender->sendMessage("§4Player didnt allow you to delete #$inv inventory");
        $p->sendMessage("§aYour inventory wont be deleted");
      }
      
    });
    
    $form->setTitle("Confirmation");
    $form->setContent("§bConsole wanted to delete your #$inv inventory\n\nDo you allow this action to be run?");
    
    $form->addButton("Yes");
    $form->addButton("No");
    
    $target->sendForm($form);
    } else {
      
      Main::getCfg()->deleteData($invs);
      $sender->sendMessage("§aSuccesful deleted #$inv inventory");   
    }
    
  }
  
}