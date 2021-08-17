<?php

declare(strict_types=1);

namespace App;

use App\Controller\AbstractController;
use App\Controller\NoteController;
use App\Exception\AppException;
use Throwable;

require_once("src/Utils/debug.php");
require_once("src/controller/NoteController.php");
require_once("src/controller/AbstractController.php");
require_once("src/Exceptions/AppException.php");
require_once("src/Request.php");

$configuration = require_once("config/config.php");

$request = new Request($_GET, $_POST, $_SERVER);


try {
    //$controller = new NoteController($request);
    //$controller->run();
    AbstractController::initConfiguration($configuration);
    (new NoteController($request))->run();

} catch (AppException $e) {
    echo "<h1> Wystąpił błąd w aplikacji </h1>";
    echo $e->getMessage();
} catch (Throwable $e) {
    echo "<h1> Wystąpił błąd w aplikacji </h1>";
    dump($e);
}

