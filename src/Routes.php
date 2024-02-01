<?php

declare (strict_types=1);

/**
 * Hämtar rutt information från inskickad URI
 * @param string $querystring
 * @param RequestMethod $method
 * @return Route
 */
function getRoute(string $querystring, RequestMethod $method = RequestMethod::GET): Route {
    // Ta bort avslutande snedstreck
    if (substr($querystring, -1) === "/") {
        $querystring = substr($querystring, 0, -1);
    }

    // Dela upp strängen till en array
    $uri = explode("/", $querystring);
    $parametrar = [];

    // Räkna antalet delar och fördela dem mellan rutt och parametrar
    switch (count($uri)) {
        case 0:
        case 1:
        case 2:
        case 3:
            $rutt = "/";
            break;
        case 4:
            $rutt = "/{$uri[3]}/";
            break;
        default :
            $rutt = "/{$uri[3]}/";
            $parametrar = array_slice($uri, 4);
    }

    // Kontrollera inskickad metod och läs av eventuell $_POST[action]
    if ($method === RequestMethod::POST) {
        if (isset($_POST["action"]) && $_POST["action"] === "delete") {
            $method = RequestMethod::DELETE;
        } elseif (isset($_POST["action"]) && $_POST["action"] === "save" && count($parametrar) > 0) {
            $method = RequestMethod::PUT;
        }
    } else {
        $method = RequestMethod::GET;
    }

    // Skapa och returnera ett Route-objekt
    return new Route($rutt, $parametrar, $method);
}