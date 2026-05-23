<?php
namespace App\Models;

use App\Models\Release;
/** Class ReleaseManager **/
class ReleaseManager {

    private $bdd;
    
    public function __construct() {
        $this->bdd = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getBdd() {
        return $this->bdd;
    }

    public function releases() {
        $stmt = $this->bdd->query('SELECT * FROM releases ORDER BY id_release DESC');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, "App\Models\Release");
    }

    public function deleteRelease($id) {
        $stmt = $this->bdd->prepare('DELETE FROM releases WHERE id_release = :id');
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getTypes() {
        $stmt = $this->bdd->query('SELECT * FROM types');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function newRelease($title, $releaseDate, $idType, $numberSongs, $budget, $details, $idUser) {
        $stmt = $this->bdd->prepare('INSERT INTO releases (title, release_date, id_type, number_songs, id_user, budget, details, created_at, updated_at) VALUES (:title, :release_date, :id_type, :number_songs, :id_user, :budget, :details, NOW(), NOW())');
        $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
        $stmt->bindValue(':release_date', $releaseDate, \PDO::PARAM_STR);
        $stmt->bindValue(':id_type', $idType, \PDO::PARAM_INT);
        $stmt->bindValue(':number_songs', $numberSongs, \PDO::PARAM_INT);
        $stmt->bindValue(':id_user', $idUser, \PDO::PARAM_INT);
        $stmt->bindValue(':budget', $budget, \PDO::PARAM_STR);
        $stmt->bindValue(':details', $details, \PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->bdd->lastInsertId();
        }

        return false;
    }
}