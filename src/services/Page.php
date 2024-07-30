<?php

namespace PoolNET\service;

use PoolNET\config\Session;

class Page
{
  protected Session $session;

  protected ?string $title;
  protected string $body = "";
  public string $customBody = "";

  public function __construct(?string $title = null)
  {
    $this->title = $title;
    $this->session = new Session();
  }

  protected function pageHead(): void
  {
    echo <<<EOT
      <!DOCTYPE html>
      <head>
        <meta charset="UTF8">
        <meta name="viewport" content="width=device-width"/>
        <title> $this->title </title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏊</text></svg>">
        <link rel="stylesheet" type="text/css" href="css/format_mobil.css">
        <script src="https://kit.fontawesome.com/5d896814dd.js" crossorigin="anonymous"></script>
      </head>
      EOT;
  }

  protected function pageBody(): void
  {
    $content = $this->contentBody();
    echo <<<EOT
      <body>
        $content
      </body>
      </html>
    EOT;
  }

  private function contentBody(): string
  {
    // Check if custom header, body, footer
    $this->body .= $this->defaultHeader();
    $this->body .= $this->customBody;
    return $this->body;
  }

  public function addToBody(string $content): void
  {
    $this->customBody .= $content;
  }

  protected function defaultHeader(): string
  {
    $content = $this->contentHeader();
    return <<<EOT
      <div class="head_user">
        $content
      </div>
    EOT;
  }

  private function contentHeader(): string
  {
    if ($this->session->loggedInUser) {
      return <<<EOT
        <span class="head_user" id="inici"><a class="dissimulat" href="./"><i class="fa-solid fa-house"></i></a>
        <a class="dissimulat" href="calculadora.php"><i class="fa-solid fa-calculator"></i></a></span>
        <span class="head_user"><a class="dissimulat" href="alerta.php">
          <i class="fa-regular fa-bell"></i>
        </a></span>
        <span class="head_user"><a href="usuari.php?accio=personal" class="dissimulat"><i class="fa-solid fa-user"></i></a></span>
      EOT;
    } else {
      return <<<EOT
        <span class="head_user" id="inici"><a class="dissimulat" href="./"><i class="fa-solid fa-house"></i></a>
        <a class="dissimulat" href="calculadora.php"><i class="fa-solid fa-calculator"></i></a></span>
        <span class="head_user"><a href="usuari.php?accio=log_in" class="dissimulat"><i class="fa-regular fa-user"></i></a></span>
      EOT;
    }
  }

  protected function redirectTo(?string $ruta = null): void
  {
    header('Location: /' . $ruta);
    exit();
  }

  public function render(): void
  {
    $this->pageHead();
    $this->pageBody();
    exit();
  }
}
