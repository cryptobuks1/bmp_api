<?php
$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->share('PDO');
$injector->define('PDO', [
    ':dsn' => 'mysql:dbname=' . getenv('DB_DATABASE') . ';charset=utf8;host=' . getenv('DB_HOST') . '',
    ':username' => getenv('DB_USERNAME'),
    ':passwd' => getenv('DB_PASSWORD')
]);

$pdo = $injector->make('PDO');
/*
$injector->share('Redis');
$injector->prepare('Redis', function($redis, $injector) {
    return $redis->connect(getenv('REDIS_HOST'));
});
$redis = $injector->make('Redis');
*/
return $injector;
