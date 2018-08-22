<?php namespace Api;
require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Calcutta');

error_reporting(E_ALL);
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();
$environment = getenv('APP_ENV');

/**
 * Register the error handler
 */
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    error_reporting(E_ALL);
ini_set('display_errors', 1);
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e) {
        echo 'Friendly error page and send an email to the developer';
    });
}
$whoops->register();

//throw new \Exception;
//$request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
//$response = new \Http\HttpResponse;

$injector = include('Dependencies.php');
$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

$response->setHeader('Content-Type', 'application/json');

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

//$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$dispatcher = \FastRoute\cachedDispatcher($routeDefinitionCallback, [
    'cacheFile' => __DIR__ . '/Cache/route.cache', /* required */
    //'cacheDisabled' => IS_DEBUG_ENABLED,     /* optional, enabled by default */
]);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        //$class = new $className($request,$response);
        //$class->$method($vars);
        $class = $injector->make($className);
        $class->$method($vars);
        break;
}
//To set Header for Requested response resourcess
foreach ($response->getHeaders() as $header) {
    header($header, false);
}
//echo  $content = json_encode($response->getContent());
echo $response->getContent();
