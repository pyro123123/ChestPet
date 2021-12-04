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
  
  public function update(string $name,array $item) {
   
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
      
      $count = [];
            
       foreach ($this->getData("",true) as $key => $value) {
            
         if(str_contains($key,$name)) {
              array_push($count,$key);
           }
        }
    
    if($this->config->exists($name)) {
      $this->config->set($name,$values);
      $this->config->save();
      return;
    }
    
    $this->config->set($name." #".count($count) + 1,$values);
    
    $this->config->save();
 
  }
  
  public function getData(string $target = "",$all = false) {
   
    if($all) {
      return $this->config->getAll();
    }
    
    
    return $this->config->get($target);

    
  }
  
  public function deleteData($name) {

    $this->config->remove($name);
    $this->config->save();
    
  }
 
  public function has($name) {
    return $this->config->exists($name);
  }
  public function getPlayerInv(String $name) {
    
    $all = $this->config->getAll();
    $result = [];
    
    foreach ($all as $pname => $val) {
      
      if(str_contains($pname,$name)) {
        array_push($result,$val);
      }
      
    }
    
    return $result;
  }
  
}