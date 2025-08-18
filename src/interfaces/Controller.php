<?php

namespace PoolNET\interface\Controller;

use PoolNET\interface\config\{Request, Response};

interface Controlador {}
interface Get extends Controlador
{
    public static function get(Request $req, Response $res): void;
}
interface Post extends Controlador
{
    public static function post(Request $req, Response $res): void;
}
interface Patch extends Controlador
{
    public static function patch(Request $req): void;
}
interface Delete extends Controlador
{
    public static function delete(Request $req): void;
}
interface CRUD extends Get, Post, Patch, Delete {}
