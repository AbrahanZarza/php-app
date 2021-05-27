<?php

namespace App\Classes;

class Request
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    private $params;

    public function __construct($withBody = false)
    {
        $this->params = array_merge($_GET, $_POST);

        if ($withBody) {
            $this->params = array_merge($this->params, $this->read_body_params());
        }
    }

    public function get(string $paramName)
    {
        return !empty($this->params[$paramName]) ? $this->params[$paramName] : null;
    }

    public function set(string $paramName, $value)
    {
        $this->params[$paramName] = $value;
    }

    public function getAll()
    {
        return $this->params;
    }

    private function read_body_params(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

}