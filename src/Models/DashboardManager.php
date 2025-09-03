<?php
namespace App\Models;

use App\Models\Dashboard;
/** Class DashboardManager **/
class DashboardManager {

    private $bdd;
    
    public function __construct($id_travel = null) {
        $this->bdd = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function releases() {
        $stmt = $this->bdd->query('SELECT * FROM releases ORDER BY id_release DESC');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, "App\Models\Dashboard");
    }

    public function getTypes() {
        $stmt = $this->bdd->query('SELECT * FROM types');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function newRelease($title, $releaseDate, $idType, $cover, $idUser) {
        $stmt = $this->bdd->prepare('INSERT INTO releases (title, release_date, id_type, cover, id_user, created_at, update_at) VALUES (:title, :release_date, :id_type, :cover, :id_user, NOW(), NOW())');
        $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
        $stmt->bindValue(':release_date', $releaseDate, \PDO::PARAM_STR);
        $stmt->bindValue(':id_type', $idType, \PDO::PARAM_INT);
        $stmt->bindValue(':cover', $cover, \PDO::PARAM_STR);
        $stmt->bindValue(':id_user', $idUser, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}