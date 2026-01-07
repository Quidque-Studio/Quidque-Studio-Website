<?php

namespace Api\Core;

class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        extract($data);

        $viewPath = BASE_PATH . '/api/Views/' . $view . '.php';

        if ($layout) {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            require BASE_PATH . '/api/Views/layouts/' . $layout . '.php';
        } else {
            require $viewPath;
        }
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}