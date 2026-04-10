<?php
namespace App\Models;

/** Class Planning **/
class Planning {

    private $id;
    private $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    

    public function setId() {
        $this->id = uniqid();
    }

    public function setUName(String $name) {
        $this->name = $name;
    }
}