<?php
namespace App\Models;

/** Class Dashboard **/
class Dashboard {
    public $id_release;
    public $id_user;
    public $title;
    public $cover;
    public $id_type;
    public $release_date;
    public $created_at;
    public $updated_at;

    public function getIdRelease() {
        return $this->id_release;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCover() {
        return $this->cover;
    }

    public function getIdType() {
        return $this->id_type;
    }

    public function getReleaseDate() {
        return $this->release_date;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }


    public function setIdRelease($id_release) {
        $this->id_release = $id_release;
    }

    public function setIdUser($id_user) {
        return $this->id_user;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setCover($cover) {
        $this->cover = $cover;
    }
    
    public function setIdType($id_type) {
        $this->id_type = $id_type;
    }

    public function setReleaseDate($release_date) {
        $this->release_date = $release_date;
    }
    
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }
}