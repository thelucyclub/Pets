<?php
namespace TheLucyClub/Pets;
use pocketmine\block\Block;
class DatabaseManager {
    private $database;
    public function __construct($path) {
        $this->database = new \SQLite3($path);
        $sql = "CREATE TABLE IF NOT EXISTS Pets(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    PetOwner TEXT NOT NULL,
                    petName INTEGER NOT NULL,
		)
		CREATE TABLE IF NOT EXISTS PetInventory(
                    itemCount INTEGER PRIMARY KEY AUTOINCREMENT,
                    item INTEGER NOT NULL,
                    damage INTEGER NOT NULL,
		)";
        $this->database->exec($sql);
    }
    public function makePet($petOwner, $petName) {
        return $this->database->exec("INSERT INTO Pets (petOwner, petName) VALUES ('$petOwner', $petName)");
    }
    public function makePet($item, $damage) {
        return $this->database->exec("INSERT INTO PetInventory (item, damage) VALUES ('$item', $damage)");
    }
    public function selectByCondition(array $condition) {
        $where = $this->formatCondition($condition);
        return ($res = $this->database->query("SELECT * FROM Pets WHERE $where")) === false ? false : $res->fetchArray(SQLITE3_ASSOC);
    }
    public function deleteByCondition(array $condition) {
        $where = $this->formatCondition($condition);
        return $this->database->exec("DELETE FROM Pets WHERE $where");
    }
    private function formatCondition(array $condition) {
        $result = "";
        $first = true;
        foreach ($condition as $key => $val) {
            if ($first) $first = false;
            else $result .= "AND ";
            $result .= "$key = $val ";
        }
        return trim($result);
    }
}
