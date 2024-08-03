<?php

namespace PoolNET\route;

use PoolNET\service\RouterPage;
use function PoolNET\page\mainPage;

function pageRouter(): RouterPage
{
    $mainPage = mainPage();

    $router = new RouterPage("/");
    $router->addPage("/", $mainPage);
    return $router;
}
