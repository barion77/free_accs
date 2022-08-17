<?php 

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller
{
    public function index()
    {
        $this->view->layout = 'layouts.default';
        $this->view->render('main.index', 'Panel');
    }
}