<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function index(): Response
    {
        return new Response('Symfony Responde a la llamada de React');
    }
}
