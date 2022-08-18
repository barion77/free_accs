<?php 

namespace app\core;

use app\core\View;
use app\models\Post;

abstract class Controller 
{
    public $model;
    public $view;
    public $parameters;

    public function __construct($values = null)
    {
        $this->view = new View();
        $this->model = new Post();
        $this->parameters = $values;
    }
}