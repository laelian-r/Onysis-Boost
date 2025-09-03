<?php
namespace App\Controllers;

use App\Models\DashboardManager;
use App\Validator;

class DashboardController {
    private $manager;

    public function __construct() {
        $this->manager = new DashboardManager();
    }

    public function index() {
        require VIEWS . 'App/landing.php';
    }

    public function dashboard() {
        $data = $this->manager->releases();
        require VIEWS . 'App/dashboard.php';
    }

    public function newRelease() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id_user'])) {
            header('Location: /login/');
            exit;
        }
        $idUser = $_SESSION['user']['id_user'];
        $types = $this->manager->getTypes();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $releaseDate = $_POST['release_date'] ?? '';
            $idType = $_POST['id_type'] ?? '';
            $cover = $_POST['cover'] ?? '';
            $idUser = $_SESSION['user']['id_user'];

            if ($title && $releaseDate && $idType && $cover) {
                $this->manager->newRelease($title, $releaseDate, $idType, $cover, $idUser);
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Tous les champs sont obligatoires.";
            }
        }
        require VIEWS . 'App/newRelease.php';
    }

    public function viewLink() {
        // $data = $this->manager->all();
        require VIEWS . 'App/link.php';
    }
}