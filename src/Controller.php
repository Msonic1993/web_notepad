<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;

require_once("Exceptions/ConfigurationException.php");
require_once("src/View.php");
require_once("src/Database.php");

class Controller
{
  private const DEFAULT_ACTION = 'list';

  private static $configuration = [];

  private $database;
  private $request;
  private  $view;

  public static function initConfiguration(array $configuration): void
  {
    self::$configuration = $configuration;
  }

    /**
     * @throws ConfigurationException
     * @throws Exception\StorageException
     */
    public function __construct(array $request)
  {
    if (empty(self::$configuration['db'])){
        throw new ConfigurationException('Configuration error');
    }
    $this->database = new Database(self::$configuration['db']);

    $this->request = $request;
    $this->view = new View();
  }

  public function run(): void
  {
    $viewParams = [];

    switch ($this->action()) {
      case 'create':
        $page = 'create';
        $created = false;

        $data = $this->getRequestPost();
        if (!empty($data)) {
          $created = true;
          $this->database->createNote($data);
          header('Location: /src');

        }

        $viewParams['created'] = $created;
        break;
      case 'show':
        $viewParams = [
          'title' => 'Moja notatka',
          'description' => 'Opis'
        ];
        break;
      default:
        $page = 'list';
        $viewParams['resultList'] = "wyświetlamy notatki";
        break;
    }

    $this->view->render($page, $viewParams);
  }

  private function action(): string
  {
    $data = $this->getRequestGet();
    return $data['action'] ?? self::DEFAULT_ACTION;
  }

  private function getRequestGet(): array
  {
    return $this->request['get'] ?? [];
  }

  private function getRequestPost(): array
  {
    return $this->request['post'] ?? [];
  }
}
