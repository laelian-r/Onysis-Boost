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
        $types = $this->manager->getTypes();
        require VIEWS . 'App/dashboard.php';
    }

    public function deleteRelease($id) {
        $this->manager->deleteRelease($id);
        header('Location: /dashboard');
        exit;
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
            $numberSongs = $_POST['number_songs'] ?? null;
            $budget = $_POST['budget'] ?? null;
            $details = $_POST['details'] ?? null;

            $idUser = $_SESSION['user']['id_user'];

            if ($title && $releaseDate && $idType) {
                $newId = $this->manager->newRelease($title, $releaseDate, $idType, $numberSongs, $budget, $details, $idUser);
                if ($newId) {
                    header('Location: /planning/' . $newId);
                    exit;
                }
                $error = "Impossible de créer la sortie.";
            } else {
                $error = "Tous les champs sont obligatoires.";
            }
        }
        require VIEWS . 'App/newRelease.php';
    }
}