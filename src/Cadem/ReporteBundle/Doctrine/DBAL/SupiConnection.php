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
	
	//RETORNA EL NUMERO (INT) DE SALAS TOTALES A MEDIR PARA UN CLIENTE, PERIODO Y ESTUDIO
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
	
	//RETORNA LA CANTIDAD (INT) DE SALAS MEDIDAS PARA UN CLIENTE, PERIODO Y ESTUDIO
	//EN TABLA VISITA SUPI, PARA ESTADO = 4 (TERMINADA), ESTADO = 5 (RECHAZADA)
	public function getSalasMedidas($id_cliente, $id_calendario, $id_estudio)
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
				AND V.ESTADO = 4
				GROUP BY V.ID_VISITA
				) AS T";
		
		$stmt = $this->executeQuery($sql, array('id_cliente' => $id_cliente, 'id_calendario' => $id_calendario, 'id_estudio' => $id_estudio))->fetch()['count'];
		return $stmt;
	}
	
	//RETORNA LA LISTA DE ESTUDIOS PARA UN CLIENTE
	public function getListaEstudiosByCliente($id_cliente)
	{
		$sql = "SELECT E.ID_ESTUDIO, E.NOMBREESTUDIO, E.FECHAINICIO, E.FECHACIERRE FROM ESTUDIO E
				WHERE E.ID_CLIENTE = :id_cliente";
		
		$stmt = $this->executeQuery($sql, array('id_cliente' => $id_cliente))->fetchAll();
		return $stmt;
	}
	
	//RETORNA LA LISTA DE REGIONES
	public function getListaRegion()
	{
		$sql = "SELECT REGION_ID, REGION_NOMBRE FROM REGION R
				ORDER BY REGION_NOMBRE";
		
		$stmt = $this->executeQuery($sql)->fetchAll();
		return $stmt;
	}
	
	//RETORNA LA LISTA DE PROVINCIAS, SI $REGIONES ES NULL RETORNA TODO. $REGIONES DEBE SER UN
	//ARRAY CON LOS IDS DE REGIONES
	public function getListaProvincia($regiones = NULL)
	{
		if(is_null($regiones) || !is_array($regiones))
		{
			$sql = "SELECT PROVINCIA_ID, PROVINCIA_NOMBRE FROM PROVINCIA P
					ORDER BY PROVINCIA_NOMBRE";
			$stmt = $this->executeQuery($sql)->fetchAll();
		}
		else
		{
			$sql = "SELECT PROVINCIA_ID, PROVINCIA_NOMBRE FROM PROVINCIA P
					WHERE PROVINCIA_REGION_ID IN (?)
					ORDER BY PROVINCIA_NOMBRE";
			$stmt = $this->executeQuery($sql,array($regiones),array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY))->fetchAll();
		}
		return $stmt;
	}
	
	//RETORNA LA LISTA DE COMUNAS, SI $PROVINCIAS ES NULL RETORNA TODO. $PROVINCIAS DEBE SER UN
	//ARRAY CON LOS IDS DE PROVINCIAS
	public function getListaComuna($provincias = NULL)
	{
		if(is_null($provincias) || !is_array($provincias))
		{
			$sql = "SELECT COMUNA_ID, COMUNA_NOMBRE FROM COMUNA
					ORDER BY COMUNA_NOMBRE";
			$stmt = $this->executeQuery($sql)->fetchAll();
		}
		else
		{
			$sql = "SELECT COMUNA_ID, COMUNA_NOMBRE FROM COMUNA
					WHERE COMUNA_PROVINCIA_ID IN (?)
					ORDER BY COMUNA_NOMBRE";
			$stmt = $this->executeQuery($sql,array($provincias),array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY))->fetchAll();
		}
		return $stmt;
	}
	
	//RETORNA UNA LISTA CON LAS MEDICIONES (CALENDARIO) PARA UN ESTUDIO Y CLIENTE.
	//SI EL ESTUDIO ES NULL, RETORNA TODAS LAS MEDICIONES
	public function getListaMedicion($id_cliente, $estudios = NULL)
	{
		if(is_null($estudios) || !is_array($estudios))
		{
			$sql = "SELECT C.ID_CALENDARIO, C.FECHADESDE, C.FECHAHASTA, C.DESCRIPCION FROM CALENDARIO C
					INNER JOIN ESTUDIO E ON E.ID_ESTUDIO = C.ID_ESTUDIO
					WHERE E.ID_CLIENTE = ?
					ORDER BY FECHADESDE";
			$stmt = $this->executeQuery($sql, array($id_cliente))->fetchAll();
		}
		else
		{
			$sql = "SELECT C.ID_CALENDARIO, C.FECHADESDE, C.FECHAHASTA, C.DESCRIPCION FROM CALENDARIO C
					INNER JOIN ESTUDIO E ON E.ID_ESTUDIO = C.ID_ESTUDIO
					WHERE E.ID_CLIENTE = ?
					AND C.ID_ESTUDIO IN (?)
					ORDER BY FECHADESDE";
			$stmt = $this->executeQuery($sql,array($id_cliente, $estudios),array(\Doctrine\DBAL\Connection::PARAM_INT , \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))->fetchAll();
		}
		return $stmt;
	}
	
	//RETORNA LOS DATOS DEL QUIEBRE
	public function getDatosQuiebre($id_cliente, $estudios = NULL, $id_calendario = NULL)
	{
		$sql = "SELECT COUNT(VALOR) as TOTAL,
					sum(case VALOR when '1' then 1 else 0 end) as PRESENCIA,
					sum(case VALOR when '0' then 1 else 0 end) as QUIEBRE,
					(sum(case VALOR when '1' then 1 else 0 end)*100)/COUNT(VALOR) as '% PRESENCIA',
					(sum(case VALOR when '0' then 1 else 0 end)*100)/COUNT(VALOR) as '% QUIEBRE',
					sum(case DISPONIBILIDAD when '2' then 1 else 0 end) as CAUTIVOS,
					sum(case DISPONIBILIDAD when '3' then 1 else 0 end) as 'COD. ERRONEOS' 
					
					FROM [SUPI].[dbo].WEB_BASE
					WHERE
					ID_CLIENTE = :id_cliente AND
					ID_VARIABLE = 1 ";
					
		if(is_null($estudios) && is_null($id_calendario))
		{
			$stmt = $this->executeQuery($sql, array('id_cliente' => $id_cliente))->fetchAll();
		}
		else if(!is_null($estudios) && is_null($id_calendario))
		{
			$sql .= "AND ID_ESTUDIO = :id_estudio";
			$stmt = $this->executeQuery($sql,array('id_cliente' => $id_cliente, 'id_estudio' => $estudios))->fetchAll();
		}
		else if(is_null($estudios) && !is_null($id_calendario))
		{
			$sql .= "AND ID_CALENDARIO = :id_calendario";
			$stmt = $this->executeQuery($sql,array('id_cliente' => $id_cliente, 'id_calendario' => $id_calendario))->fetchAll();
		}
		else
		{
			$sql .= " AND ID_CALENDARIO = :id_calendario AND ID_ESTUDIO = :id_estudio";
			$stmt = $this->executeQuery($sql,array('id_cliente' => $id_cliente, 'id_estudio' => $estudios, 'id_calendario' => $id_calendario))->fetchAll();
		}
		return $stmt;
	}
}