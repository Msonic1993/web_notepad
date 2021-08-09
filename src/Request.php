<?php

declare(strict_types=1);

namespace App;


class Request
{
    private $get = [];
    private  $post = [];

    public function __construct(array $get, array $post)
    {
        $this->get = $get;
        $this->post = $post;
    }
    public function getParam(string $name)
    {
        return $this->get[$name] ?? null;
    }

    public function postParam(string $name)
    {
        return $this->post[$name] ?? null;
    }

    }