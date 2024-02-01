<?php

declare (strict_types=1);

/**
 * Läs av rutt-information och anropa funktion baserat på angiven rutt
 * @param Route $route Rutt-information
 * @param array $postData Indata för behandling i angiven rutt
 * @return Response
 */
function compilations(Route $route): Response {
    try {
        if (count($route->getParams()) === 2 && $route->getMethod() === RequestMethod::GET) {
            return hamtaSammanstallning($route->getParams()[0], $route->getParams()[1]);
        }
    } catch (Exception $exc) {
        return new Response($exc->getMessage(), 400);
    }

    return new Response("Okänt anrop", 400);
}

/**
 * Hämtar en sammanställning av uppgiftsposter i ett angivet datumintervall
 * @param string $from
 * @param string $tom
 * @return Response
 */
function hamtaSammanstallning(string $from, string $tom): Response {
    // Kontrollera indata
    $fromDate = DateTimeImmutable::createFromFormat("Y-m-d", $from);
    $tomDate = DateTimeImmutable::createFromFormat("Y-m-d", $tom);
    $datumFel = [];

    if($fromDate === false) {
        $datumFel[] = "Ogiltig från datum";
    }
    if($tomDate === false) {
        $datumFel[] = "Ogiltig till datum";
    }
    if($fromDate && $fromDate->format("Y-m-d")!==$from) {
        $datumFel[] = "Ogiltig angivet från-datum";
    }
    if($tomDate && $tomDate->format("Y-m-d")!==$tom) {
        $datumFel[] = "Ogiltig angivet till-datum";
    }
    if($fromDate && $tomDate &&$fromDate->format("Y-m-d") > $tomDate->format("Y-m-d")) {
        $datumFel[] = "Från-datum får inte vara större än till datum";
    }

    if(count($datumFel)>0) {
        $retur = new stdClass();
        $retur->error = $datumFel;
        array_unshift($retur->error, "Bad request");
        return new Response($retur, 400);
    }

    // Koppla databas 
    $db = connectDb();

    // Exekvera databasfråga
    $stmt = $db->prepare("SELECT aktivitetid, SEC_TO_TIME(SUM(TIME_TO_SEC(tid))) AS tid, namn AS aktivitet "
    . "FROM uppgifter u INNER JOIN aktiviteter a ON aktivitetid=a.id "
    . "WHERE datum BETWEEN :from AND :to "
    . "GROUP BY aktivitetid");
    $stmt->execute([":from" => $fromDate ->format('Y-m-d'), ":to" => $tomDate->format('Y-m-d')]);

    // Returnera svar
    $poster = [];
    while($row = $stmt->fetch()) {
        $rad=new stdClass();
        $rad->activityId=$row['aktivitetid'];
        $rad->tid=substr($row['tid'], 0,-3);
        $rad->activity=$row['aktivitet'];
        $poster[]=$rad;
}
    $retur=new stdClass();
    $retur->tasks=$poster;

    return new Response($retur);
}