<?php

namespace CaptMusix\chestPet\util;

use CaptMusix\chestPet\{
  command\giveCP,
  event\chestUse,
  command\deleteInv

};

class register {
  
  public static function init($main) {
    self::cmd($main);
    self::event($main);
  }
  
  public static function cmd($main) {
    $cmd = ["cp" => new giveCP(),"cpdel" => new deleteInv()];
    
    foreach ($cmd as $name => $val) {
      $main->getServer()->getCommandMap()->register($name,$val);
    }
    
  }
  
  public static function event($main) {
    $event = [
      new chestUse()
      ];
    
    foreach ($event as $val) {
      $main->getServer()->getPluginManager()->registerEvents($val,$main);
     }
        
  }
  
}