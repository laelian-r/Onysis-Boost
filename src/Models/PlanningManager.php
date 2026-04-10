<?php
namespace App\Models;

use App\Models\Planning;
/** Class PlanningManager **/
class PlanningManager {

    private $bdd;
    
    public function __construct() {
        $this->bdd = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function getReleaseById($id_release) {
        $sql = "SELECT r.*, u.username
                FROM releases r
                JOIN users u ON r.id_user = u.id
                WHERE r.id_release = :id_release";
        $stmt = $this->bdd->prepare($sql);
        $stmt->bindValue(':id_release', $id_release, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}