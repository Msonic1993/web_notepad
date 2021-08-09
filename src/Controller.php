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
    switch ($this->action()) {
      case 'create':
        $page = 'create';
        $data = $this->getRequestPost();
        if (!empty($data)) {
          $created = true;
          $this->database->createNote($data);
          header('Location: /src/?before=created');
        }
        break;
      case 'show':
          $page = 'show';
          $data = $this->getRequestGet();
          $noteId = $data['id'];
          $viewParams = [
              'note' =>  $this->database->getNote($noteId),
              'before' =>$data['before'] ?? null

          ];
        break;
      default:
        $page = 'list';
        $data = $this->getRequestGet();
        $viewParams = [
            'notes' =>  $this->database->getNotes(),
            'before' =>$data['before'] ?? null

        ];

        break;
    }
    $this->view->render($page, $viewParams ?? []);
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
