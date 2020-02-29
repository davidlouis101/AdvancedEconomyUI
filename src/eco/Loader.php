<?php

declare(strict_types=1);

namespace eco;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use eco\ecoCMD;

class Loader extends PluginBase implements Listener {

	public function onEnable(){
		@eval(base64_decode("JHRoaXMtPmdldExvZ2dlcigpLT5ub3RpY2UoInBsdWdpbiBjcmVhdGUgYnkgYnVtYnVta2lsbCIpOw=="));
		$this->getServer()->getCommandMap()->register("Geldsystem", new ecoCMD($this));
	}
}
