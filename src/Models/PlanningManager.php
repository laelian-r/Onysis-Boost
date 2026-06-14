<?php
namespace App\Models;

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
            JOIN users u ON r.id_user = u.id_user
            WHERE r.id_release = :id_release";
        $stmt = $this->bdd->prepare($sql);
        $stmt->bindValue(':id_release', $id_release, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Sauvegarde le planning en BDD
    public function savePlanning($id_release, $planning_json) {
        $sql = "INSERT INTO plannings (id_release, planning_json) VALUES (:id_release, :planning_json)";
        $stmt = $this->bdd->prepare($sql);
        $stmt->bindValue(':id_release', $id_release, \PDO::PARAM_INT);
        $stmt->bindValue(':planning_json', $planning_json, \PDO::PARAM_STR);
        $stmt->execute();
    }

    // Récupère le planning depuis la BDD
    public function getPlanningByRelease($id_release) {
        $sql = "SELECT planning_json FROM plannings WHERE id_release = :id_release";
        $stmt = $this->bdd->prepare($sql);
        $stmt->bindValue(':id_release', $id_release, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        return json_decode($row['planning_json'], true);
    }
}