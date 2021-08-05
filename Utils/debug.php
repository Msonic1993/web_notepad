<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function dump($data)
{
    echo '<p style="background: lightgoldenrodyellow; display: inline-block;">'. "Wynik: ";
    echo '<br>';
    var_dump($data);
    echo '</p>'. "";
}