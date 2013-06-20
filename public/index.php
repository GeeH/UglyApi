<?php
chdir(__DIR__ . '/../');
require_once('vendor/autoload.php');

$uri = parse_url($_SERVER['REQUEST_URI']);

$Router = new \Core\Router();
$Boostrap = new \Core\Bootstrap($Router);

try {
    $result = $Boostrap->init($uri['path'], $_SERVER['REQUEST_METHOD']);
    $result['status'] = 1;
} catch (Exception $e) {
    $result['status'] = 0;
    $result['error'] = 'An unhandled exception occured: ' . $e->getMessage();
}

header('Content-type: application/json');
echo json_encode($result);

