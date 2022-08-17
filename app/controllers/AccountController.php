<?php 

namespace app\controllers;

use app\core\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $this->view->layout = 'layouts.default';
        $this->view->render('account.index', 'Логи');
    }

}