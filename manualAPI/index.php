<?php
declare(strict_types=1);

use app\Database;
use controllers\ProductController;
use controllers\UserController;
use models\Product;
use models\User;

spl_autoload_register(function ($class){
    $class_path = str_replace('\\', '/', $class);
    $file =  __DIR__ . '/src/' . $class_path . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

set_error_handler("app\ErrorHandler::handleError");
set_exception_handler("app\ErrorHandler::handleException");

header("Content-Type: application/json; charset=UTF-8");

$uri = explode("/", $_SERVER['REQUEST_URI']);

$id = $uri[2] ?? null;
$db = new Database();
$db->getConnection();

if ( ! isset($_SERVER['PHP_AUTH_USER'])) {
    notAutheticated();
} else {

    if ((new UserController(new User($db)))->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {

        if ($uri[1] != 'products') {
            http_response_code(404);
            exit;
        }

        $controller = new ProductController(new Product($db));

        $controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
    } else {
        notAutheticated();
    }
}

function notAutheticated() {
    http_response_code(401);
    echo json_encode([
        "messege" => "You are not authorized!"
    ]);
    exit;
}
