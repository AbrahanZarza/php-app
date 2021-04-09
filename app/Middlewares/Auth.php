<?php

namespace App\Middlewares;

use App\Classes\Response;
use App\Exceptions\AuthException;

class Auth
{

    public function __invoke(): void
    {
        try {
            $headers = getallheaders();

            if (empty($headers['X-Api-Key'])) {
                throw new AuthException('X-Api-Key authentication required!');
            }

        } catch (AuthException $e) {
            response($e->getMessage(), Response::HTTP_UNAUTHORIZED);
            die;
        }
    }

}