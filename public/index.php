<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Handler\Contact;
use App\Router;

$router = new App\Router();

$router->get('/', function () {
    $title = 'Home';
    require __DIR__ . '/../views/home.phtml';
});

$router->get('/about', function () {
    $title = 'About';
    require __DIR__ . '/../views/about.phtml';
});

$router->get('/contact', [Contact::class]);
$router->post('/contact', function ($params) {
    var_dump($params);
});

$router->addNotFoundHandler(function () {
    $title = 'Not Found';
    $code = 404;
    $message = 'Page not found';
    require __DIR__ . '/../views/404.phtml';
});

$router->run();