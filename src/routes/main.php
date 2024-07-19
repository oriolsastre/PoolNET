<?php
use PoolNET\page\PrivatePage;

$main_page = new PrivatePage("PoolNET");
$request_uri = $_SERVER['REQUEST_URI'];
$body = <<<EOT
    <h1 class="principal">PISCINA</h1>
    <div>
        <div class="boto_inici" id="estat"><h2><a href="estat.php" class="dissimulat">ESTAT DE LA PISCINA</a></h2></div>
        <div class="boto_inici" id="control"><h2><a href="control.php" class="dissimulat">CONTROL DE L'AIGUA</a></h2></div>
        <div class="boto_inici" id="accio"><h2><a href="accio.php" class="dissimulat">ACCIO SOBRE L'AIGUA</a></h2></div>
    </div>
    $request_uri
EOT;
$main_page->addToBody($body);
$main_page->render();