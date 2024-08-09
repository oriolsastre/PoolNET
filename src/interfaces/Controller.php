<?php

namespace PoolNET\interface\Controller;

use PoolNET\config\Request;
use PoolNET\config\Response;

interface Get
{
    public static function get(Request $req, Response $res): void;
}

interface Post
{
    public static function post(Request $req): void;
}

interface Patch
{
    public static function patch(Request $req): void;
}

interface Delete
{
    public static function delete(Request $req): void;
}
interface CRUD extends Get, Post, Patch, Delete
{
}
