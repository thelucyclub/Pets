<?php
namespace ChestShop;
use pocketmine\block\Block;
class DatabaseManager
{
    private $database;
    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->database = new \SQLite3($path);
        $sql = "CREATE TABLE IF NOT EXISTS ChestShop(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    PetOwner TEXT NOT NULL,
                    petId INTEGER NOT NULL,
		)";
        $this->database->exec($sql);
    }

    public function registerShop($petOwner, $petId) {
        return $this->database->exec("INSERT INTO ChestShop (shopOwner, saleNum, price, productID, productMeta, signX, signY, signZ, chestX, chestY, chestZ) VALUES ('$shopOwner', $saleNum, $price, $productID, $productMeta, $sign->x, $sign->y, $sign->z, $chest->x, $chest->y, $chest->z)");
    }
    public function selectByCondition(array $condition) {
        $where = $this->formatCondition($condition);
        return ($res = $this->database->query("SELECT * FROM ChestShop WHERE $where")) === false ? false : $res->fetchArray(SQLITE3_ASSOC);
    }
    public function deleteByCondition(array $condition) {
        $where = $this->formatCondition($condition);
        return $this->database->exec("DELETE FROM ChestShop WHERE $where");
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
