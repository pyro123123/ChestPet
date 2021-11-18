<?php

namespace CaptMusix\chestPet\util;

use CaptMusix\chestPet\{
  Main
};

class cfg {
  
  private $cfg;
  private $path;
  
  public function __construct($config) {
    $this->config = $config;
    $this->path = Main::getInstance()->getServer()->getPluginPath()."chestPet/chest.json";
  }
  
  public function setData(string $name,array $item) {
   
   $values = [];
   
   foreach ($item as $i) {
    $names = $i->hasCustomName() ? $i->getCustomName() : $i->getVanillaName();
         
     $ce = $i->getEnchantments();
     $ench = [];
      
      if(count($ce) !== 0) {
      
     foreach ($ce as $c) {
       array_push($ench,[
           "id" => $c->getId(),
           "level" => $c->getLevel()
           ]);
      }
      
     }
   
      array_push($values,[
          "id" => $i->getId(),
          "name" => $names,
          "size" => $i->getCount(),
          "ce" => $ench
         ]);
         
   }
   
    $this->config->set($name,$values);
    
    $this->config->save();
  }
  
  public function getData(string $target) {
    return $this->config->get($target);
  }
  
}