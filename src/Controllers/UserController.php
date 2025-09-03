<?php

namespace App\Controllers;

use App\Models\UserManager;
use App\Validator;

/** Class UserController **/
class UserController {
    private $manager;
    private $validator;

    public function __construct() {
        $this->manager = new UserManager();
        $this->validator = new Validator();
    }

    public function showLogin() {
        require VIEWS . 'Auth/login.php';
    }

    public function showRegister() {
        require VIEWS . 'Auth/register.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login/');
    }

    public function register() {
        $this->validator->validate([
            "username"=>["required", "min:3", "alphaNum"],
            "email"=>["required", "email"],
            "password"=>["required", "min:6", "alphaNum", "confirm"],
            "passwordConfirm"=>["required", "min:6", "alphaNum"]
        ]);
        $_SESSION['old'] = $_POST;

        if (!$this->validator->errors()) {
            $userExists = $this->manager->findUser($_POST["username"]);
            $emailExists = $this->manager->findEmail($_POST["email"]);

            if ($userExists) {
                $_SESSION["error"]['username'] = "Le nom d'utilisateur choisi est déjà utilisé !";
                header("Location: /register");
                exit;
            }
            if ($emailExists) {
                $_SESSION["error"]['email'] = "L'adresse e-mail choisie est déjà utilisée !";
                header("Location: /register");
                exit;
            }

            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->manager->store($password);

            $_SESSION["user"] = [
                "id" => $this->manager->getBdd()->lastInsertId(),
                "username" => $_POST["username"],
                "email" => $_POST["email"],
            ];
            header("Location: /dashboard");
        } else {
            header("Location: /register");
        }
    }

    public function login() {
        $this->validator->validate([
            "username"=>["required", "min:3", "max:9", "alphaNum"],
            "email"=>["required", "email"],
            "password"=>["required", "min:6", "alphaNum"]
        ]);

        $_SESSION['old'] = $_POST;

        if (!$this->validator->errors()) {
            // Recherche par username ET email
            $user = $this->manager->findUser($_POST["username"]);
            if ($user && $user->getEmail() === $_POST["email"] && password_verify($_POST['password'], $user->getPassword())) {
                $_SESSION["user"] = [
                    "id_user" => $user->getId(),
                    "username" => $user->getUsername(),
                    "email" => $user->getEmail(),
                ];
                header("Location: /dashboard");
            } else {
                $_SESSION["error"]['message'] = "Identifiant, adresse e-mail ou mot de passe incorrect !";
                header("Location: /login");
            }
        } else {
            header("Location: /login");
        }
    }
}