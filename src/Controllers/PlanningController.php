<?php
namespace App\Controllers;

use App\Models\PlanningManager;

class PlanningController
{
    private $manager;

    public function __construct()
    {
        $this->manager = new PlanningManager();
    }

    public function planning()
    {
        require VIEWS . 'App/planning.php';
    }

    public function viewPlanning($id_release)
    {
        $data = $this->manager->getReleaseById($id_release);
        if (!$data) {
            header('Location: /dashboard');
            exit;
        }

        require VIEWS . 'App/planning.php';
    }
}