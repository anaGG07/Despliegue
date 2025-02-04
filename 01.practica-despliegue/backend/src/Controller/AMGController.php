<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AMGController
{
    #[Route('/api/AMG', name: 'amg_hello')]
    public function index(): Response
    {
        return new Response('Symfony de Ana María García García Responde a la llamada de React');
    }
}
