<?php
declare (strict_types=1);

require_once '../src/routes.php';
require_once '../src/Route.php';
require_once '../src/Response.php';
require_once '../src/RequestMethod.php';
require_once './funktioner.php';

// Hämta begärd resurs
$uri = filter_var($_SERVER['REQUEST_URI'], FILTER_UNSAFE_RAW);
// Lagra undan aktuell mapp
$dir = dirname($_SERVER['SCRIPT_NAME']);

// Hämta ruttinformation
$route = getRoute($uri);
switch (count($route->getParams())) {
    case 0: // Inga parametrar - testa allt
        require_once './testaAllt.php';
        $html = testaAllaFunktioner();
        break;
    case 1: // En parameter - testa en avdelning
        switch ($route->getParams()[0]) {
         case "activity":
   //          require_once './TestActivities.php';
   //          $html = allaActivityTester();
   //          break;
         case "tasklist":
    //         require_once './TestTasks.php';
    //         $html = allaTasklistTester();
    //         break;
         case "task":
    //         require_once './TestTasks.php';
    //         $html = allaTaskTester();
    //         break;
         case "compilation":
    //         require_once './TestCompilation.php';
    //         $html = allaCompilationTester();
    //         break;
         default: // Ingen träff - visa info
             $html = ingenRutt($route->getParams()[0]);
             break;
     }
     break;
 case 2: // Två parametrar testa enskilda funktioner
     switch ($route->getParams()[0]) {
         case "activity":
         case "tasklist":
         case "task":
         case "compilation":
               $html = testFunction($route->getParams()[1]);
               break;
         default: // Ingen träff - visa info
                $html = ingenRutt($route->getParams()[0]);
                break;
        }
        break;
    default: // Ingen träff - Visa info
        $html = ingenRutt(implode("/", $route->getParams()));
        break;
}

// Skriv ut en snygg htlm-sida som svar
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tester för tidsredovisnings API:et</title>
        <meta charset="UTF-8">
        <link href="<?= $dir; ?>/index.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?= $html; ?>
    </body>
</html>
