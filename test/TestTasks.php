<?php

declare (strict_types=1);
require_once __DIR__ . '/../src/tasks.php';

/**
 * Funktion för att testa alla aktiviteter
 * @return string html-sträng med resultatet av alla tester
 */
function allaTaskTester(): string {
// Kom ihåg att lägga till alla testfunktioner
    $retur = "<h1>Testar alla uppgiftsfunktioner</h1>";
    $retur .= test_HamtaEnUppgift();
    $retur .= test_HamtaUppgifterSida();
    $retur .= test_RaderaUppgift();
    $retur .= test_SparaUppgift();
    $retur .= test_UppdateraUppgifter();
    return $retur;
}

/**
 * Tester för funktionen hämta uppgifter för ett angivet sidnummer
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaUppgifterSida(): string {
    $retur = "<h2>test_HamtaUppgifterSida</h2>";
    try {
    // Misslyckas med hämta sida -1
    $svar= hamtaSida("-1");
    if($svar->getStatus() === 400){
        $retur.= "<p class='ok'>Misslyckades hämta uppgifter för sida -1 som förväntat </p>";
    } else {
        $retur.= "<p class='error'>Misslyckat test att hämta sida -1<br>" 
        .$svar->getStatus(). "returnerades istället för förväntat 400</p>";
    }

    // Misslyckas med hämta sida 0
    $svar= hamtaSida("0");
    if($svar->getStatus() === 400){
        $retur.= "<p class='ok'>Misslyckades hämta uppgifter för sida 0 som förväntat </p>";
    } else {
        $retur.= "<p class='error'>Misslyckat test att hämta sida 0<br>" 
        .$svar->getStatus(). "returnerades istället för förväntat 400</p>";
    }

    // Misslyckas med hämta sida sju
    $svar= hamtaSida("sju");
    if($svar->getStatus() === 400){
        $retur.= "<p class='ok'>Misslyckades hämta uppgifter för sida <i>sju</i> som förväntat </p>";
    } else {
        $retur.= "<p class='error'>Misslyckat test att hämta sida <i>sju</i><br>" 
        .$svar->getStatus(). "returnerades istället för förväntat 400</p>";
    }

    // Lyckas med hämta sida 1
    $svar= hamtaSida("1",2);
    if($svar->getStatus() === 200){
        $sista.= "<p class='ok'>Lyckades hämta sida 1</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat test att hämta sida 1<br>" 
        .$svar->getStatus(). "returnerades istället för förväntat 200</p>";
    }

    // Misslyckas med hämta sida > antal sidor
    if(isset($sista)){
        $sista++;
        $svar= hamtaSida("$sista",2);
        if($svar->getStatus() === 400){
            $retur.= "<p class='ok'>Misslyckades med att hämta sida > antal, som förväntat </p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test att hämta sida > antal sidor<br>"
                .$svar->getStatus(). "returnerades istället för förväntat 400</p>";
        }
    }

        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen hämta uppgifter mellan angivna datum
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaAllaUppgifterDatum(): string {
    $retur = "<h2>test_HamtaAllaUppgifterDatum</h2>";
    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test av funktionen hämta enskild uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaEnUppgift(): string {
    $retur = "<h2>test_HamtaEnUppgift</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen spara uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_SparaUppgift(): string {
    $retur = "<h2>test_SparaUppgift</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen uppdatera befintlig uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_UppdateraUppgifter(): string {
    $retur = "<h2>test_UppdateraUppgifter</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

function test_KontrolleraIndata(): string {
    $retur = "<h2>test_KontrolleraIndata</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen radera uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_RaderaUppgift(): string {
    $retur = "<h2>test_RaderaUppgift</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}
