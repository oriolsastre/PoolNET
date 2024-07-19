<?php
namespace PoolNET\page;

class PrivatePage extends Page
{
  private int $level;

  public function __construct(?string $title = null, ?int $level = 0)
  {
    parent::__construct($title);
    $this->level = $level;

  }
}
