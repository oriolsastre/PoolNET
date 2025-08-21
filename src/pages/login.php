<?php

namespace PoolNET\page;

use PoolNET\service\Page;

function loginPage(): Page
{
    $loginPage = new Page("Inicia sessió");
    $body = <<<HTML
    <h1>Inicia sessió</h1>
    <form id="log_in" method="post" action="./api/auth/login">
        <table>
            <tr>
                <td>Usuari:</td>
                <td><input type="text" size="20" maxlength="20" name="usuari" form="log_in"></td>
            </tr><tr>
                <td>Contrassenya:</td>
                <td><input type="password" size="20" name="password" form="log_in"></td>
            </tr><tr>
                <td colspan="2"><input type="checkbox" name="recorda" value="1" form="log_in">Recorda'm a aquest ordinador</td>
        </tr>
        </table>
    <input type="hidden" name="accio" value="log_in" form="log_in">
    <input type="submit" value="Entrar" form="log_in">
    </form>
    HTML;
    $loginPage->addToBody($body);
    return $loginPage;
}
