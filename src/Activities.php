<?php

declare (strict_types=1);
require_once __DIR__ . '/funktioner.php';

/**
 * Läs av rutt-information och anropa funktion baserat på angiven rutt
 * @param Route $route Rutt-information
 * @param array $postData Indata för behandling i angiven rutt
 * @return Response
 */
function activities(Route $route, array $postData): Response {
    try {
        if (count($route->getParams()) === 0 && $route->getMethod() === RequestMethod::GET) {
            return hamtaAllaAktiviteter();
        }
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::GET) {
            return hamtaEnskildAktivitet($route->getParams()[0]);
        }
        if (isset($postData["activity"]) && count($route->getParams()) === 0 &&
                $route->getMethod() === RequestMethod::POST) {
            return sparaNyAktivitet((string) $postData["activity"]);
        }
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::PUT) {
            return uppdateraAktivitet( $route->getParams()[0],  $postData["activity"]);
        }
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::DELETE) {
            return raderaAktivetet($route->getParams()[0]);
        }
    } catch (Exception $exc) {
        return new Response($exc->getMessage(), 400);
    }

    return new Response("Okänt anrop", 400);
}

/**
 * Returnerar alla aktiviteter som finns i databasen
 * @return Response
 */
function hamtaAllaAktiviteter(): Response {
    // Koppla mot databas
    $db = connectDb();

    // Hämta alla aktiviteter
    $result = $db->query("SELECT id, namn FROM aktiviteter");

    // Skapa returvärde
    $retur= [];
    foreach ($result as $item) {
        $post=new stdClass();
        $post->id=$item['id'];
        $post->namn=$item['namn'];
        $retur[]=$post;
    }

    // Skicka svar
    return new Response($retur);
}

/**
 * Returnerar en enskild aktivitet som finns i databasen
 * @param string $id Id för aktiviteten
 * @return Response
 */
 
function hamtaEnskildAktivitet(string $id): Response {
        // Kontrollera inparametrar
        $kontrolleratId = filter_var($id, FILTER_VALIDATE_INT);
    
        if($kontrolleratId === false || $kontrolleratId < 1) {
            $retur = new stdClass();
            $retur->error=['Bad request', 'Ogiltigt id'];
            return new Response($retur, 400);
        }
        // Koppla mot databasen
        $db = connectDb();
    
        // Skicka fråga
        $stmt=$db->prepare("SELECT id, namn FROM aktiviteter WHERE id = :id");
        $result=$stmt->execute(['id' => $kontrolleratId]);
    
        // Kontrollera svar
        if ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $retur = new stdClass();
            $retur->id=$row['id'];
            $retur->activitiy=$row['namn'];
            return new Response($retur);
        } else {
            $retur = new stdClass();
            $retur->error=['Bad request', "Angivet id ($kontrolleratId) finns inte"];
            return new Response($retur, 400);
        }
     }

/**
 * Lagrar en ny aktivitet i databasen
 * @param string $aktivitet Aktivitet som ska sparas
 * @return Response
 */
function sparaNyAktivitet(string $aktivitet): Response {
    // Kontrollerar indata - rensa bort onödigt tecken
    $kontrolleradAktivitet = filter_var($aktivitet, FILTER_SANITIZE_ENCODED);


    // Kontrollera att aktiviteten inte är tom!
    if(trim($aktivitet) === "") {
        $retur = new stdClass();
        $retur->error=['Bad request', 'Aktivitet får inte vara tom'];
        return new Response($retur, 400);
    }
    
    try {

    // Koppla mot databasen
    $db = connectDb();

    // Exekvera frågan
    $stmt=$db->prepare("INSERT INTO aktiviteter (namn) VALUES (:aktivitet)");
    $svar=$stmt->execute([':aktivitet'=>$kontrolleradAktivitet]);

    // Kontrollera svaret och returnera svar
    if($svar === true){
        $retur = new stdClass();
        $retur->id=$db->lastInsertId();
        $retur->meddelande=['Spara lyckades', '1 post lades till'];
        return new Response($retur);
    } else {
        $retur = new stdClass();
        $retur->error=['Bad request', $svar. " Något gick fel vid spara"];
        return new Response($retur, 400);
    }
} catch (Exception $e) {
    $retur = new stdClass();
    $retur->error=['Bad request', 'Fel vid spara', $e->getMessage()];
    return new Response($retur, 400);
}
}

/**
 * Uppdaterar angivet id med ny text
 * @param string $id Id för posten som ska uppdateras
 * @param string $aktivitet Ny text
 * @return Response
 */
function uppdateraAktivitet(string $id, string $aktivitet): Response {
}

/**
 * Raderar en aktivitet med angivet id
 * @param string $id Id för posten som ska raderas
 * @return Response
 */
function raderaAktivetet(string $id): Response {
}
