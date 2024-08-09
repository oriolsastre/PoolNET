<?php

namespace PoolNET\route;

use PoolNET\service\RouterPage;
use function PoolNET\page\mainPage;
use function PoolNET\page\loginPage;

function pageRouter(): RouterPage
{
    $router = new RouterPage("/");
    $router->addPage("/", mainPage());
    $router->addPage("/login", loginPage());
    return $router;
}
