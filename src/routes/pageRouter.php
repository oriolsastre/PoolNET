<?php

use PoolNET\service\RouterPage;

require_once __DIR__ . "/../pages/main.php";

$pageRouter = new RouterPage("/");
$pageRouter->addPage("/", $mainPage);
