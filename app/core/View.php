<?php

namespace app\core;

class View
{
    public $layout;
    public $config;

    public function __construct()
    {
        $this->config = require_once '../config/view.php';
    }

    public function render($view, $view_title = 'DOCUMENT', $variables = [], $include = true)
    {
        $this->layout = $this->config['path'] . str_replace('.', '/', $this->layout) . '.php';
        $view = $this->config['path'] . str_replace('.', '/', $view) . '.php';
        if (file_exists($this->layout)) {
            extract($variables);
            unset($variables);
            if ($include) {
                $includes = $this->getIncludes();
                extract($includes);
                unset($includes);
            }

            unset($include);
            if (file_exists($view)) {
                ob_start();
                require $view;
                unset($view);
                $view_content = ob_get_clean();
            }

            require $this->layout;
        }
    }

    public function getIncludes()
    {
        $includes = [];
        $files = scandir($this->config['includes_path']);

        if (!empty($files)) {
            foreach ($files as $file) {
                $file_name = explode('.', $file)[0];
                $extension = explode('.', $file)[1];
                if ($extension == 'php') {
                    ob_start();
                    require $this->config['includes_path'] . $file;
                    $includes[$file_name] = ob_get_clean();
                }   
            }
        }

        return $includes;
    }
}
