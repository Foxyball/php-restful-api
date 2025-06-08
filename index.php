<?php

declare(strict_types=1);
include ('config.php');

$parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));


spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        http_response_code(404);
        exit;
    }
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

if ($parts[1] != 'products') {
    http_response_code(404);
    exit;
}

$id = $parts[2] ?? null;


$database = new Database(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PASSWORD,
    DB_PORT
);

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
