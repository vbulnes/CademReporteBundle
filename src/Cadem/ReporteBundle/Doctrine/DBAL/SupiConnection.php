<?php

namespace Cadem\ReporteBundle\Doctrine\DBAL;
use Doctrine\DBAL\Connection;

class SupiConnection extends Connection
{
    public function findEstudios()
    {
        $stmt = $this->prepare('SELECT * FROM ESTUDIO');
        $stmt->execute();

        return $stmt;
    }
}