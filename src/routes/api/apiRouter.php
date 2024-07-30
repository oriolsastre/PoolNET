<?php

use PoolNET\controller\Control;
use PoolNET\service\RouterJson;

$apiRouter = new RouterJson('/api');

$apiRouter->addController("/control", Control::class);
