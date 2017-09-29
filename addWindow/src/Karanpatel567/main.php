<?php

namespace Karanpatel567;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as c;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\item\Item;

class main extends PluginBase{
	public function onEnable(){
		$this->getLogger()->Info(c::GREEN. "addWindow by Karan enabled!");
	}
	public function sendInventory (Player $player) {
		$nbt = new CompoundTag ( "", [
				new StringTag ( "id", Tile::CHEST ),
				new IntTag ( "Inventory", 1 ),
				new IntTag ( "x", ( int ) $player->getX () ),
				new IntTag ( "y", ( int ) $player->getY () ),
				new IntTag ( "z", ( int ) $player->getZ () )
		] );
		/** @var Chest $tile */
		$tile = Tile::createTile ( "Chest", $player->getLevel (), $nbt );
		$block = Block::get ( Block::CHEST );
		$block->x = ( int ) $tile->x;
		$block->y = ( int ) $tile->y;
		$block->z = ( int ) $tile->z;
		$block->level = $tile->getLevel ();
		$block->level->sendBlocks ( [
				$player
		], [
				$block
		] );
		if ($tile instanceof Chest) {
			// Items
			$i = $tile->getInventory();
			$i->addItem(Item::get(Item::STONE, 0, 1));
		}
		$player->addWindow($tile->getInventory());
	}
	public function onCommand(CommandSender $sender, Command $cmd, $labels, array $args){
		if(strtolower($cmd->getName()) == "addwindow"){
			if($sender->hasPermission("addwindow.command")){
				$sender->sendMessage(c::YELLOW. "Sending Inventory! Please be patient.");
				if($sender instanceof Player){
				$this->sendInventory($sender);
                }
			}elseif(!$sender->hasPermission("addwindow.command")){
				$sender->sendMessage(c::RED. "You don't have permission to use this command!");
			}
		}
	}
	public function onDisable(){
		$this->getLogger()->Info(c::RED. "addWindow by Karan disabled!");
	}
}