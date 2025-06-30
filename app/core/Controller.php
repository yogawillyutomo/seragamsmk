<?php

class Controller
{
    /**
     * Load dan tampilkan view
     *
     * @param string $view     Nama file view (tanpa path lengkap)
     * @param array  $data     Data yang akan diextract ke view
     */
    public function view(string $view, array $data = []): void
    {
        $viewPath = "../app/views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new Exception("View tidak ditemukan: {$viewPath}");
        }

        extract($data);
        require_once $viewPath;
    }

    /**
     * Load dan kembalikan instance model
     *
     * @param string $model    Nama file model
     * @return object|null
     */
    public function model(string $model): ?object
    {
        $modelPath = "../app/models/{$model}.php";

        if (!file_exists($modelPath)) {
            throw new Exception("Model tidak ditemukan: {$modelPath}");
        }

        require_once $modelPath;
        return new $model;
    }
}
