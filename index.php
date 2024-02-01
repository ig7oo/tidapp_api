<?php

require_once './src/Routes.php';
require_once './src/funktioner.php';
require_once './src/Route.php';
require_once './src/Response.php';
require_once './src/RequestMethod.php';

// Hämta begärd resurs
$uri = filter_var($_SERVER['REQUEST_URI'], FILTER_UNSAFE_RAW);

// Läs in eventuell POST-data
if (count($_POST) > 0) {
    $postData = $POST;
    $metod = RequestMethod::POST;
} else {
    $postData = [];
    $metod = RequestMethod::GET;
}

try {
    // Hämta ruttinformation
    $route = getRoute($uri, $metod);
    // Hantera ruttinformationen
    switch ($route->getRoute()) {
        case "/":
            include __DIR__ . "/info.html";
            exit;
        case "/activity/":
            require_once './src/Activities.php';
            $retur = activities($route, $postData);
            break;
        case "/tasklist/":
            require_once './src/Tasks.php';
            $retur = tasklists($route);
            break;
        case "/task/":
            require_once './src/Tasks.php';
            $retur = tasks($route, $postData);
            break;
        case "/compilation/":
            require_once './src/Compilations.php';
            $retur = compilations($route);
            break;
        default:
            $retur = new Response("Okänt anrop", 400);
            break;
    }

    // Skicka svar som JSON-data
    $retur->skickaJSON();
} catch (\Exception $e) {

    $retur = new stdClass();
    $retur->message = ["Oväntat fel", $e->getMessage()];
    $retur->error->code = $e->getCode();
    $retur->error->file = $e->getFile();
    $retur->error->line = $e->getLine();
    $retur->error->trace = $e->getTrace();

    $out = new Response($retur, 400);

    $out->skickaJSON();
}