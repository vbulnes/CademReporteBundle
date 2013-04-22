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
	
	//RETORNA EL NUMERO DE SALAS TOTALES A MEDIR PARA UN CLIENTE, PERIODO Y ESTUDIO
	public function getTotalSalas($id_cliente, $id_calendario, $id_estudio)
	{
		$sql = "SELECT COUNT(*) as count FROM
				(
				SELECT V.ID_VISITA FROM VISITA V
				INNER JOIN ESTUDIOSALA ES ON V.ID_ESTUDIOSALA = ES.ID_ESTUDIOSALA
				INNER JOIN ESTUDIO E ON E.ID_ESTUDIO = ES.ID_ESTUDIO
				INNER JOIN CALENDARIO C ON C.ID_ESTUDIO = E.ID_ESTUDIO
				WHERE E.ID_CLIENTE = :id_cliente
				AND E.ID_ESTUDIO = :id_estudio
				AND C.ID_CALENDARIO = :id_calendario
				GROUP BY V.ID_VISITA
				) AS T";
		
		$stmt = $this->executeQuery($sql, array('id_cliente' => $id_cliente, 'id_calendario' => $id_calendario, 'id_estudio' => $id_estudio))->fetch()['count'];
		return $stmt;
	}
}