<?php

class App
{
    protected string $controller = 'Dashboard';
    protected string $method = 'index';
    protected array $params = [];
    protected object $controllerInstance;

    public function __construct()
    {
        $url = $this->parseURL();

        // Cek controller dari URL
        if (!empty($url) && file_exists("../app/controllers/" . ucfirst($url[0]) . ".php")) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } elseif (!empty($url)) {
            $this->controller = 'Error404';
        }

        $controllerFile = "../app/controllers/" . $this->controller . ".php";

        if (!file_exists($controllerFile)) {
            require_once "../app/controllers/Error404.php";
            $this->controller = 'Error404';
        } else {
            require_once $controllerFile;
        }

        // Instansiasi controller
        if (!class_exists($this->controller)) {
            throw new Exception("Controller class '{$this->controller}' not found.");
        }
        $this->controllerInstance = new $this->controller;

        // Cek method
        if (isset($url[1]) && method_exists($this->controllerInstance, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } elseif (isset($url[1])) {
            // Method tidak ditemukan → redirect ke method index dari Error404
            $this->controller = 'Error404';
            require_once "../app/controllers/Error404.php";
            $this->controllerInstance = new Error404;
            $this->method = 'index';
            unset($url[1]);
        }

        // Params
        $this->params = !empty($url) ? array_values($url) : [];

        // Eksekusi method
        try {
            call_user_func_array([$this->controllerInstance, $this->method], $this->params);
        } catch (Throwable $e) {
            echo "Terjadi kesalahan saat menjalankan aplikasi: " . $e->getMessage();
        }
    }

    public function parseURL(): array
    {
        if (!isset($_GET['url']) || empty($_GET['url'])) {
            return [];
        }

        return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
}
