<?php
namespace Pets\Managers;
use Pets\Pets;
class Sqlite3Manager implements PointlessManager{
    private $database, $Main;
    public function __construct(Pets $Main) {
        $this->Main = $Main;
        $path = $this->Main->getDataFolder()."Pets.sqlite3";
        $this->database = new \SQLite3($path);
        $sql = "CREATE TABLE IF NOT EXISTS Pets(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    petOwner TEXT NOT NULL,
                    petName TEXT NOT NULL,
                    petId INTEGER NOT NULL
		)";
        $this->database->exec($sql);
    }
    public function makePet($petOwner, $petName) {
        return $this->database->exec("INSERT INTO Pets (petOwner, petName) VALUES ('$petOwner', $petName)");
    }
    public function getPetOwner($petName) {
        $Owner = $this->selectByCondition(["petName" => $petName]);
        return $Owner['petOwner'];
    }
    public function getOwnerPet($ownerName) {
        $Pet = $this->selectByCondition(["petOwner" => $ownerName]);
        return $Pet['petName'];
    }
    public function setPetName($newName, $ownerName, $petName=null) {
        if($petName != null) {
            $sql = "UPDATE Pets SET petName='$newName' WHERE ownerName='$ownerName',petName='$petName'";
            return $this->database->exec($sql);
        }
        $sql = "UPDATE Pets SET petName='$newName' WHERE ownerName='$ownerName'";
        return $this->database->exec($sql);
    }
    public function removePet($petName) {
        return $this->deleteByCondition(["petName" => $petName]);
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
    public function close() {
        $this->database->close();
    }
}
