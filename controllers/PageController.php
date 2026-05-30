<?php

require_once __DIR__ . '/../models/Personal.php';
require_once __DIR__ . '/PartialController.php';

class PageController
{
    private $personalModel;

    public function __construct()
    {
        $this->personalModel = new Personal();
    }

    public function home()
    {
        $doctors = $this->personalModel->getDoctors();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/home/index.php';
    }

    public function about()
    {
        $headerData = PartialController::getHeaderData();
        require_once __DIR__ . '/../views/home/about.php';
    }

    public function contact()
    {
        header('Location: /vetclinic/contact');
        exit;
    }
    public function services()
    {
        $serviceModel = new Service();
        $services = $serviceModel->findAll();
        $headerData = PartialController::getHeaderData();

        require_once __DIR__ . '/../views/home/services.php';
    }

    public function petHealthPrograms()
{
    $headerData = PartialController::getHeaderData();
    require_once __DIR__ . '/../views/home/pet-health-programs.php';
}
}
