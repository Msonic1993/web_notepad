<?php

declare(strict_types=1);

namespace App;


use App\Exception\ConfigurationException;

require_once("src/Exceptions/AppException.php");
require_once("src/View.php");
require_once("src/Database.php");

abstract class AbstractController
{
    private const DEFAULT_ACTION = 'list';

    private static $configuration = [];

    protected $database;
    protected $request;
    protected  $view;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
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
        $action = $this->action() . 'Action';
        if (!method_exists($this,$action)) {
            $action = self::DEFAULT_ACTION . 'Action';
            $this->$action();
        }
        $this->$action();
    }

    protected function redirect(string $to, array $params): void
    {
        $queryParams = [];
        foreach ($params as $key => $value) {
            $queryParams[] = urlencode($key) . "=" . urlencode($value);
        }
        $queryParams = implode('&', $queryParams);
        $to .= '?' . $queryParams;
        header("Location: $to");
    }

    private function action(): string
    {
        return $this->request->getParam('action',self::DEFAULT_ACTION);
    }
}