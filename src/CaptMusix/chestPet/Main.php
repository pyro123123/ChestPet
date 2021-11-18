<?php

declare(strict_types=1);

namespace CaptMusix\chestPet;

use pocketmine\{
  plugin\PluginBase,
  utils\Config
};

use CaptMusix\chestPet\{
  util\register,
  util\cfg
};

use muqsit\invmenu\{
  InvMenuHandler
};

class Main extends PluginBase{
 private static $instance;
 private static $path;
 private static $cfg;
 
 public function onEnable() {
   self::$instance = $this;
   self::$path = self::$instance->getServer()->getPluginPath()."chestPet/chest.json";
   self::$cfg = new cfg(new Config(self::$path));
   
   if(!InvMenuHandler::isRegistered()){
   	InvMenuHandler::register($this);
   }
   
   register::init($this);
   
 }
 
 public static function getCfg() {
  
   return self::$cfg;
 }
 
 public static function getInstance() {
   return self::$instance;
 }
 
 
}
