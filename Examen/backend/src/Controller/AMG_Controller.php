<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AMG_Controller extends AbstractController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[Route('/api/amg', name: 'get_amg')]
    public function index(): JsonResponse
    {
        $sql = 'SELECT fraseAMG FROM secretosAMG LIMIT 1';
        $result = $this->connection->fetchOne($sql);

        return $this->json(['message' => $result ?: 'No hay mensajes en la BD']);
    }
}
