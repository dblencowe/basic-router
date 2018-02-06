<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use App\Classes\Response;

class NotFound
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
        $urlPath = parse_url($this->request->server->get('REQUEST_URI'), PHP_URL_PATH);
        $this->response->setView('not-found.html.php');
        $this->response->setVars([
            'url' => $urlPath,
        ]);

        return $this->response;
    }
}