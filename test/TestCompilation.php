<?php

declare (strict_types=1);
require_once __DIR__ . '/../src/compilations.php';

/**
 * Funktion för att testa alla aktiviteter
 * @return string html-sträng med resultatet av alla tester
 */
function allaCompilationTester(): string {
// Kom ihåg att lägga till alla testfunktioner
    $retur = "<h1>Testar alla sammanställningsfunktioner</h1>";
    $retur .= test_HamtaSammanstallning();
    return $retur;
}

/**
 * Tester för funktionen hämta en sammmanställning av uppgifter mellan två datum
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaSammanstallning(): string
{
    $retur = "<h2>test_HamtaSammanstallning</h2>";

    try {
        //misslyckas med från 2024-01-01 till 2024-01-37
        $svar = hamtaSammanstallning("2024-01-01", "2024-01-37");
        if ($svar->getStatus() === 400) {
            $retur .= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2024-01-37 som förväntat</p>";
        } else {
            $retur .= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2024-01-37<br>"
                . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //misslyckas med från2024-01-01 till 2023-01-01
        $svar = hamtaSammanstallning("2024-01-01", "2023-01-01");
        if ($svar->getStatus() === 400) {
            $retur .= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2023-01-01 som förväntat</p>";
        } else {
            $retur .= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2023-01-01<br>"
                . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }

        //lyckas med korrekta datum
        //leta upp en månad mes poster
        $db = connectDb();
        $stmt = $db->query("SELECT YEAR(datum), MONTH(datum), COUNT(*) AS antal "
            . "FROM uppgifter "
            . "GROUP BY YEAR(datum), MONTH(datum) "
            . "ORDER BY antal DESC "
            . "LIMIT 0,1");
        $row = $stmt->fetch();
        $ar = $row[0];
        $manad = substr("0$row[1]", -2);


        //hämta alla poster från den funna månaden
        $svar = hamtaSammanstallning("$ar-$manad-01", date("Y-m-d", strtotime("Last day of $ar-$manad")));
        $antal = 0;
        if ($svar->getStatus() === 200 && count($svar->getContent()->tasks) > 0) {
            $antal = count($svar->getContent()->tasks);
            $retur .= "<p class='ok'>Lyckades hämta $antal poster för månad $ar-$manad som förväntat</p>";
        } else {
            $retur .= "<p class='error'>Misslyckades med att hämta $antal poster för månad $ar-$manad<br>"
                . $svar->getStatus() . "returnerades istället för förväntat 200<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }



        return $retur;
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

