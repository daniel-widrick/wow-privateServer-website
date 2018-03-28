<?php

namespace lvlint67\cMangosHelper;


class database {
	private $hostname;
	private $port;
	private $username;
	private $password;
	private $name;

	protected $dbh;

	public function __construct($hostname,$port,$username,$password,$name) {
		$this->hostname = $hostname;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->name = $name;
		$this->connect();
	}

	private function connect() {
		try {
			$this->dbh = new \PDO("mysql:host=".$this->hostname .":".$this->port.";"."dbname=".$this->name.";", 
				$this->username, 
				$this->password
			);
		} catch(PDOException $ex) {
			logger::err("Failed to connect to Database.");
			logger::fatal(var_export($ex));
		}
		return true;
	}
}

class databaseCharacter extends database {

	/*
	public function __construct($hostname,$port,$username,$password,$name) {
		parent::__construct($hostname,$port,$username,$password,$name);
	}
   */

	public function getCharacterRankings($order) {
	/* Takes a string or array of fields for order by statement */
		$sort = "";
		if(is_array($order)) {
			foreach($order as $field) {
				$sort = $sort.",".$field;
			}
			$sort = trim($sort,',');
		} else {
			$sort = $order;
		}

		$query = "SELECT name, race, class, gender, level, xp, zone, ilvl from characters 
left join
(
select
characters.characters.guid,
avg(mangos.item_template.ItemLevel) ilvl

from

characters.characters
left join characters.character_inventory on characters.characters.guid = characters.character_inventory.guid
left join mangos.item_template on mangos.item_template.entry = characters.character_inventory.item_template

where 
characters.character_inventory.bag = 0 and slot <=19

group by characters.characters.guid

) a on a.guid = characters.guid
where level >= 10
			and deleteDate is null order by $sort";
		try {
			$result = $this->dbh->query($query);
			$data = $result->fetchAll();
			foreach($data as $key=>$values)
			{
				$data[$key]["raceDescription"] = helperCharacter::getRaceDescription($data[$key]["race"]);
				$data[$key]["classDescription"] = helperCharacter::getClassDescription($data[$key]["class"]);
				$data[$key]["genderDescription"] = helperCharacter::getGenderDescription($data[$key]["gender"]);
			}
			return $data;
		} catch(PDOException $ex) {
			logger::err("Error fetching character rankings");
			logger::fatal(var_export($ex));
		}
		return false;
	}
	
}

class helperCharacter {
	public static function getRaceDescription($id) {
		switch($id) {
			case(1): return "Human";
			case(2): return "Orc";
			case(3): return "Dwarf";
			case(4): return "Night Elf";
			case(5): return "Undead";
			case(6): return "Tauren";
			case(7): return "Gnome";
			case(8): return "Troll";
			default: return "Unkown";
		}
	}
	public static function getClassDescription($id) {
		switch($id) {
			case(1): return "Warrior";
			case(2): return "Paladin";
         case(3): return "Hunter";
         case(4): return "Rogue";
         case(5): return "Priest";

         case(7): return "Shaman";
         case(8): return "Mage";
         case(9): return "Warlock";

         case(11): return "Druid";
			default: return "Unkown";
		}
	}
	public static function getGenderDescription($id) {
		switch($id) {
			case(0): return "Male";
			case(1): return "Female";
			default: return "Unknown";
		}
	}
}
?>
