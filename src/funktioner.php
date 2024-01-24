<?php

declare (strict_types=1);
require_once __DIR__ . '/Settings.php'; // Settings;

function connectDb(): PDO {
    static $db = null;

    if ($db === null) {
        // Hämta settings 
        $settings = new Settings();
        // Koppla mot databasen
        $dsn = $settings->dsn;
        $dbUser = $settings->dbUser;
        $dbPassword = $settings->dbPassword;
        $db = new PDO($dsn, $dbUser, $dbPassword);
    }

    return $db;
}

function kontrolleraIndata(array $postdata): array {
    $retur = [];

    // Kontrollera datum $postdata["date"]
    $datum=DateTimeImmutable::createFromFormat("Y-m-d", $postdata["date"]??'');
    if (!$datum) {
        $retur[] = "Ogiltigt angivet datum";
    }
    if ($datum && $datum->format ('Y-m-d') !== $postdata["date"]) {
        $retur[] = "Felaktigt formaterat datum";
    }
    if ($datum && $datum->format ('Y-m-d') > date('Y-m-d')) {
        $retur[] = "Datum får inte vara framåt i tiden";
    }

    // Kontrollera tid $postdata["time"]
    $tid=DateTimeImmutable::createFromFormat("H:i", $postdata["time"]??'');
    if (!$tid) {
        $retur[] = "Ogiltig angiven tid";
    }
    if ($tid && $tid->format ('H:i')!== $postdata["time"]) {
        $retur[] = "Felaktigt angiven tid";
    }
    if ($tid && $tid->format ('H:i') > "08:00") {
        $retur[] = "Tid får inte rapportera mer än 8 timmer per aktivitet åt gången";
    }

    // Kontrollera aktivitetsId $postdata["activityId"]
    $aktivitet=hamtaEnskildAktivitet($postdata["activityId"]?? '');
    if($aktivitet->getStatus() === 400) {
        $retur[] = "Angivet aktivitetsId saknas";
    }

    return $retur;
}