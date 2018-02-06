<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use App\Classes\Response;

class Test
{
    private $request;    
    private $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function __invoke()
    {
        $this->response->setView('test.html.php');

        return $this->response;
    }
}