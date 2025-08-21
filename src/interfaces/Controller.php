<?php

namespace PoolNET\interface\Controller;

use PoolNET\interface\config\{Request, Response};

interface Controlador {}
interface Get extends Controlador
{
    public function get(Request $req, Response $res): void;
}
interface Post extends Controlador
{
    public function post(Request $req, Response $res): void;
}
interface Patch extends Controlador
{
    public function patch(Request $req): void;
}
interface Delete extends Controlador
{
    public function delete(Request $req): void;
}
interface CRUD extends Get, Post, Patch, Delete {}
