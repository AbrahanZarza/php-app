<?php


function env(string $key): ?string
{
    return $_ENV[$key] ?? null;
}

function debug(...$data)
{
    var_dump($data);
}

function dddebug(...$data)
{
    var_dump($data);die;
}

function response($data, int $status = \App\Classes\Response::HTTP_SUCCESS, array $headers = ['Content-Type' => 'application/json']): void
{
	http_response_code($status);
	
	foreach ($headers as $key => $value) {
		header("$key: $value");
	}

	echo $data;
}