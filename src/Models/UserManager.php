<?php
namespace App\Models;

use App\Models\User;
/** Class UserManager **/
class UserManager {

    private $bdd;

    public function __construct() {
        $this->bdd = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getBdd()
    {
        return $this->bdd;
    }

    public function findUser(String $username): User | false
    {
        $stmt = $this->bdd->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute(array(
            $username
        ));
        $stmt->setFetchMode(\PDO::FETCH_CLASS,"App\Models\User");

        return $stmt->fetch();
    }

    public function findEmail(String $email): User | false
    {
        $stmt = $this->bdd->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(array(
            $email
        ));
        $stmt->setFetchMode(\PDO::FETCH_CLASS,"App\Models\User");

        return $stmt->fetch();
    }

    public function store($password) {
        $stmt = $this->bdd->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
        $stmt->execute(array(
            $_POST["username"],
            isset($_POST["email"]) ? $_POST["email"] : "",
            $password,
        ));
    }
}