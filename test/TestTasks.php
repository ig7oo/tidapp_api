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
    //misslyckas med hämta sida -1
    $svar = hamtaSida("-1");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida -1 misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida -1<br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }

    //misslyckas med hämta sida 0
    $svar = hamtaSida("0");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida 0 misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida 0<br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }


    //misslyckas med hämta sida sju
    $svar = hamtaSida("sju");
    if ($svar->getStatus() === 400) {
        $retur.= "<p class='ok'>Hämta uppgifter för sida <i>sju</i> misslyckades som förväntat</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat med att hämta sida -<i>sju</i><br>"
        . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
    }


    //lyckas med att hämta sida 1
    $svar = hamtaSida("1", 2);
    if ($svar->getStatus() === 200) {
        $retur.= "<p class='ok'>lyckades med att hämta sida</p>";
        $sista = $svar->getContent()->pages;
    } else {
        $retur.= "<p class='error'>misslyckat test att hämta sida 1<br>"
        . $svar->getStatus() . "returnerades istället för förväntat 200</p>";
    }

    //misslyckas med att hämta > antal sidor
    if(isset($sista)) {
        $sista++;
        $svar=hamtaSida("$sista", 2);
        if ($svar->getStatus() === 400) {
            $retur .="<p class='ok'>Misslyckades med att hämta sida > antal sidor, som förväntat</p>";
        } else {
            $retur .="<p class='error'>Misslyckat test att hämta sida > antal sidor<br>"
                . $svar->getStatus() . "returnerades istället för förväntat 400</p>";
        }
    }

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
        // Misslyckas med från = igår till = 2024-01-01
        $svar = hamtaDatum('igår', '2024-01-01');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta poster mellan <i>igår</i> och 2024-01-01</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att hämta poster mellan <i>igår</i> och 2024-01-01<br>"
           . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
        }

        // Misslyckas med från = 2024-01-01 till imorgon
        $svar = hamtaDatum('2024-01-01', 'imorgon');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och <i>imorgon</i></p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och <i>imorgon</i><br>"
           . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
        }

        // Misslyckas med från = 2024-12-37 till 2024-01-01
        $svar = hamtaDatum('2024-12-37', '2024-01-01');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-12-37 och 2024-01-01</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-12-37 och 2024-01-01<br>"
           . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
        }

        // Misslyckas med från = 2024-01-01 till 2024-12-37
        $svar = hamtaDatum('2024-01-01', '2024-12-37');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2024-12-37</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2024-12-37<br>"
           . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
        }

        // Misslyckas med från = 2024-01-01 till 2023-01-01
        $svar = hamtaDatum('2024-01-01', '2023-01-01');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta poster mellan 2024-01-01 och 2023-01-01</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att hämta poster mellan 2024-01-01 och 2024-12-37<br>"
           . $svar->getStatus() . "Returnerades istället för förväntat 400</p>";
        }

        // Lyckas med korrekta datum
        // Leta upp en månad med poster
        $db = connectDb();
        $stmt=$db->query("SELECT YEAR(datum), MONTH(datum), COUNT(*) AS ANTAL "
        . "FROM uppgifter "
        . "GROUP BY YEAR(datum), MONTH(datum) "
        . "ORDER BY antal DESC "
        . "LIMIT 0,1");
        $row=$stmt->fetch();
        $ar=$row[0];
        $manad=substr("0$row[1]", -2);
        $antal=$row[2];

        // Hämta alla poster från den funna månaden
        var_dump("Last day of $ar-$manad");
        $svar = hamtaDatum("$ar-$manad-01", date('Y-m-d', strtotime("Last day of $ar-$manad")));
        if ($svar->getStatus() === 200 && count($svar->getContent()->tasks) === $antal) {
            $retur.= "<p class='ok'>Lyckades hämta $antal poster för månad $ar-$manad</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta poster för $ar-$manad<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
           . print_r($svar->getContent(), true) . "</p>";
        }
    


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
        // Misslyckas med att hämta id=0
        $svar = hamtaEnskildUppgift("0");
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta en uppgift med id=0, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta en uppgift med id=0<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
           . print_r($svar->getContent(), true) . "</p>";

        }

        // Misslyckas med att hämta id=sju
        $svar = hamtaEnskildUppgift("sju");
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta en uppgift med id=sju</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta en uppgift med id=sju<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
           . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med att hämta id= 3.14
        $svar = hamtaEnskildUppgift("3.14");
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta en uppgift med id=3.14, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta en uppgift med id=3.14<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
           . print_r($svar->getContent(), true) . "</p>";
        }

        /*
        // Lyckas hämta id som finns
        */
        



        // Koppla databas - skapa transaktion
        $db = connectDb();
        $db->beginTransaction();

        // Förbered data
        $content = hamtaAllaAktiviteter() -> getContent();
        $aktiviteter = $content['activities'];
        $aktivitetId = $aktiviteter[0]->id;
        $postdata=["date"=>date('Y-m-d'), 
        'time'=>'01:00', 
        'description'=>'Testpost',
        "activityId"=>"$aktivitetId"] ;

        // Skapa post
        $svar = sparaNyUppgift($postdata);
        $taskId = $svar->getContent()->id;

        // Hämta nyss skapad post
        $svar = hamtaEnskildUppgift($taskId);
        if ($svar->getStatus() === 200) {
            $retur.= "<p class='ok'>Lyckades hämta ny uppgift</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta ny uppgift<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
           . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med att hämta id som inte finns
        $taskId++;
        $svar = hamtaEnskildUppgift("$taskId");
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades hämta en uppgift som inte finns</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta uppgift som inte finns<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
           . print_r($svar->getContent(), true) . "</p>";
        }


    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        if($db){
            $db->rollBack();
        }
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
        $db = connectDb();

        // Skapa en transaktion så att vi slipper skräp i databasen
        $db->beginTransaction();

        // Misslyckas med att spara pga saknad aktivitetid
        $postdata=['time' =>'01:00',
        'date'=>'2023-12-31',
        'description' => 'Detta är en testpost',
    ];

    $svar = sparaNyUppgift($postdata);
    if ($svar->getStatus() === 400) {
        $retur .= "<p class='ok'>Misslyckades med att spara uppgift utan aktivitetid, som förväntat</p>";
    } else {
        $retur .= "<p class='error'>Misslyckat test med att spara uppgift utan aktivitetid<br>"
        . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
    }

        // Lyckas spara post utan beskrivning
        // Förbered data
        $content = hamtaAllaAktiviteter() -> getContent();
        $aktiviteter = $content['activities'];
        $aktivitetId = $aktiviteter[0]->id;
      $postdata=['time' =>'01:00',
    'date'=>'2023-12-31',
    'activityId' => "$aktivitetId"];

    // Testa
    $svar = sparaNyUppgift($postdata);
    if ($svar->getStatus() === 200) {
        $retur.= "<p class='ok'>Lyckades med att spara uppgift utan beskrivning</p>";
    } else {
        $retur.= "<p class='error'>Misslyckat test med att spara uppgift utan beskrivning<br>"
       . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
    }

        // Lyckas spara post med alla uppgifter

        $postdata ['description'] = 'Detta är en testpost';
        $svar = sparaNyUppgift($postdata);
        if ($svar->getStatus() === 200) {
            $retur.= "<p class='ok'>Lyckades med att spara uppgift med alla uppgifter</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att spara uppgift med alla uppgifter<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
                . print_r($svar->getContent(), true) . "</p>";
        }

        
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        if($db) {
            $db->rollBack();
        }
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
        // Koppla databas - skapa transaktion
        $db = connectDb();
        $db->beginTransaction();

        // Hämta postdata!
        $svar = hamtaSida("1");
        if ($svar->getStatus() !=200) {
            throw new Exception("Kunde inte hämta poster för test av Uppdatera uppgift");
        }
        $aktiviteter = $svar->getContent()->tasks;

        // Misslyckas med ogiltigt id=0
        $postdata=get_object_vars($aktiviteter[0]);
        $svar=uppdateraUppgift('0', $postdata);
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta post med id=0, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta post med id=0<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med ogiltigt id=sju
        $svar=uppdateraUppgift('sju', $postdata);
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta post med id=sju, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta post med id=sju<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
        
        // Misslyckas med ogiltigt id=3.14
        $svar=uppdateraUppgift('3.14', $postdata);
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att hämta post med id=3.14, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att hämta post med id=3.14<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Lyckas med id som finns
        $id= $postdata['id'];
        $postdata['activityId'] = (string) $postdata['activityId'];
        $postdata['description'] = $postdata['description'] . "(Uppdaterad)";
        $svar=uppdateraUppgift("$id", $postdata);
        if ($svar->getStatus() === 200 && $svar->getContent()->result===true) {
            $retur.= "<p class='ok'>Uppdatera post lyckades, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Uppdatera post misslyckades<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med samma data
        $svar=uppdateraUppgift("$id", $postdata);
        if ($svar->getStatus() === 200 && $svar->getContent()->result===false) {
            $retur.= "<p class='ok'>Uppdatera post lyckades, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Uppdatera post misslyckades<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med id som inte finns

        // Misslyckas med felaktig indata
        $postdata['time'] = "09:70";
        $svar=uppdateraUppgift("$id", $postdata);
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att uppdatera post med felaktig indata, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Uppdatera uppgift med felaktig indata  misslyckades<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Lyckas med saknad beskrivning
        $postdata['time'] = "01:30";
        unset($postdata['description']);
        $svar=uppdateraUppgift("$id", $postdata);
        if ($svar->getStatus()===200) {
            $retur.= "<p class='ok'>Uppdatera uppgift med saknad beskrivning lyckades, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Uppdatera uppgift med saknad beskrivning misslyckades<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        if($db) {
            $db->rollBack();
        }
    }

    return $retur;
}

function test_KontrolleraIndata(): string {
    $retur = "<h2>test_KontrolleraIndata</h2>";

    try {
        // Testa giltigt heltals-id
        $svar = hamtaEnskildUppgift("1");
        if ($svar->getStatus() === 200) {
            $retur .= "<p class='ok'>Kontrollera indata lyckades, giltigt heltals-id</p>";
        } else {
            $retur .= "<p class='error'>Kontrollera indata misslyckades, ogiltigt heltals-id</p>";
        }

        // Testa ogiltigt id (icke-heltal)
        $svar = hamtaEnskildUppgift("abc");
        if ($svar->getStatus() === 400) {
            $retur .= "<p class='ok'>Kontrollera indata misslyckades med ogiltigt id, som förväntat</p>";
        } else {
            $retur .= "<p class='error'>Kontrollera indata misslyckades, ogiltigt id</p>";
        }

        // Testa ogiltigt id (mindre än 1)
        $svar = hamtaEnskildUppgift("0");
        if ($svar->getStatus() === 400) {
            $retur .= "<p class='ok'>Kontrollera indata misslyckades med ogiltigt id, som förväntat</p>";
        } else {
            $retur .= "<p class='error'>Kontrollera indata misslyckades, ogiltigt id</p>";
        }
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Ett fel uppstod: {$ex->getMessage()}</p>";
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
        // Skapa transaktion
        $db=connectDb();
        $db->beginTransaction();

        // Misslyckas med att radera post med id=sju
        $svar=raderaUppgift('sju');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att radera post med id=sju, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att radera post med id=sju<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med att radera post med id=0.1
        $svar=raderaUppgift('0.1');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att radera post med id=0.1, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att radera post med id=0.1<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med att radera post med id=0
        $svar=raderaUppgift('0');
        if ($svar->getStatus() === 400) {
            $retur.= "<p class='ok'>Misslyckades med att radera post med id=0, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckat test med att radera post med id=0<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 400<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        /*
        * Lyckas med att radera post som finns
        */
        // Hämta poster
        $poster=hamtaSida("1");
        if($poster->getStatus()!==200) {
            throw new Exception("Kunde inte hämta poster");
        }
        $uppgifter=$poster->getContent()->tasks;

        // Ta fram id för första posten
        $testId=$uppgifter[0]->id;
        $svar=raderaUppgift("$testId");
        if ($svar->getStatus() === 200 && $svar->getContent()->result === true) {
            $retur.= "<p class='ok'>Lyckades radera post, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att radera post<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }

        // Misslyckas med att radera samma id som tidigare
        $svar=raderaUppgift("$testId");
        if ($svar->getStatus() === 200 && $svar->getContent()->result === false) {
            $retur.= "<p class='ok'>Misslyckades radera post som inte finns, som förväntat</p>";
        } else {
            $retur.= "<p class='error'>Misslyckades med att radera post som inte finns<br>"
           . $svar->getStatus() . " returnerades istället för förväntat 200<br>"
            . print_r($svar->getContent(), true) . "</p>";
        }
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        // Avsluta transaktion
        if($db) {
            $db->rollBack();
        }
    }

    return $retur;
}
