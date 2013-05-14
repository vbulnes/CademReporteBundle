<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use PDO;

class RankingController extends Controller
{
    
	public function indexAction()
    {
		
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		//CLIENTE Y ESTUDIO, LOGO
		$query = $em->createQuery(
			'SELECT c,e FROM CademReporteBundle:Cliente c
			JOIN c.estudios e
			JOIN c.usuarios u
			WHERE u.id = :id AND c.activo = 1 AND e.activo = 1')
			->setParameter('id', $user->getId());
		$clientes = $query->getResult();
		$cliente = $clientes[0];
		$id_cliente = $cliente->getId();
		$estudios = $cliente->getEstudios();
		
		$choices_estudio = array('0' => 'TODOS');
		foreach($estudios as $e)
		{
			$choices_estudio[$e->getId()] = strtoupper($e->getNombre());
		}
		
		$logofilename = $cliente->getLogofilename();
		$logostyle = $cliente->getLogostyle();
		
		//REGIONES
		$query = $em->createQuery(
			'SELECT DISTINCT r FROM CademReporteBundle:Region r
			JOIN r.provincias p
			JOIN p.comunas c
			JOIN c.salas s
			JOIN s.salaclientes sc
			JOIN sc.cliente cl
			WHERE cl.id = :id')
			->setParameter('id', $cliente->getId());
		$regiones = $query->getResult();
		
		$choices_regiones = array();
		foreach($regiones as $r)
		{
			$choices_regiones[$r->getId()] = strtoupper($r->getNombre());
		}

		//PROVINCIA
		$query = $em->createQuery(
			'SELECT DISTINCT p FROM CademReporteBundle:Provincia p
			JOIN p.comunas c
			JOIN c.salas s
			JOIN s.salaclientes sc
			JOIN sc.cliente cl
			WHERE cl.id = :id')
			->setParameter('id', $cliente->getId());
		$provincias = $query->getResult();
		
		$choices_provincias = array();
		foreach($provincias as $r)
		{
			$choices_provincias[$r->getId()] = strtoupper($r->getNombre());
		}
		
		//COMUNA
		$query = $em->createQuery(
			'SELECT DISTINCT c FROM CademReporteBundle:Comuna c
			JOIN c.salas s
			JOIN s.salaclientes sc
			JOIN sc.cliente cl
			WHERE cl.id = :id')
			->setParameter('id', $cliente->getId());
		$comunas = $query->getResult();
		
		$choices_comunas = array();
		foreach($comunas as $r)
		{
			$choices_comunas[$r->getId()] = strtoupper($r->getNombre());
		}
		
		
		//MEDICION
		$query = $em->createQuery(
			'SELECT m.id, m.nombre FROM CademReporteBundle:Medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			WHERE c.id = :id
			ORDER BY m.fechainicio DESC')
			->setParameter('id', $cliente->getId());
		$mediciones_q = $query->getArrayResult();
		
		foreach($mediciones_q as $m) $mediciones[$m['id']] = $m['nombre'];
		
		if(count($mediciones) > 0){
			$ultima_medicion = array_keys($mediciones)[0];
			$id_medicion_actual = $ultima_medicion;
			if(count($mediciones) > 1) $id_medicion_anterior = array_keys($mediciones)[1];
			else $id_medicion_anterior = $id_medicion_actual;
		}
		else $ultima_medicion = null;
		
		

		$form_estudio = $this->get('form.factory')->createNamedBuilder('f_estudio', 'form')
			->add('Estudio', 'choice', array(
				'choices'   => $choices_estudio,
				'required'  => true,
				'multiple'  => false,
				'data' => '0',
				'attr' => array('id' => 'myValue')
			))
			->getForm();
		$form_periodo = $this->get('form.factory')->createNamedBuilder('f_periodo', 'form')
			->add('Periodo', 'choice', array(
				'choices'   => $mediciones,
				'required'  => true,
				'multiple'  => false,
				'data' => $ultima_medicion			
			))
			->getForm();
			
		$form_region = $this->get('form.factory')->createNamedBuilder('f_region', 'form')
			->add('Region', 'choice', array(
				'choices'   => $choices_regiones,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_regiones)
			))
			->getForm();
			
		$form_provincia = $this->get('form.factory')->createNamedBuilder('f_provincia', 'form')
			->add('Provincia', 'choice', array(
				'choices'   => $choices_provincias,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_provincias)
			))
			->getForm();
			
		$form_comuna = $this->get('form.factory')->createNamedBuilder('f_comuna', 'form')
			->add('Comuna', 'choice', array(
				'choices'   => $choices_comunas,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_comunas)
			))
			->getForm();
			
		
		
		//RANKING POR SALA--------------------------------------------------------------------
		$sql = "DECLARE @id_cliente integer = :id_cliente;
		SELECT TOP(20)*, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT s.id, s.calle, s.numerocalle, sc.codigosala, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = @id_cliente AND m.id = :id_medicion_actual
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS A LEFT JOIN
			
(SELECT s.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = @id_cliente AND m.id = :id_medicion_anterior
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS B on A.ID = B.id2
			ORDER BY quiebre ASC";
		$param = array('id_cliente' => $id_cliente, 'id_medicion_actual' => $id_medicion_actual, 'id_medicion_anterior' => $id_medicion_anterior);
		$ranking_sala = $em->getConnection()->executeQuery($sql,$param)->fetchAll();
		
		//RANKING POR PRODUCTO-----------------------------------------------
		$sql = "DECLARE @id_cliente integer = :id_cliente;
		SELECT TOP(20)*, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT ic.id, ic.codigoitem, i.nombre,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			INNER JOIN ITEM i on i.ID = ic.ITEM_ID
			WHERE c.ID = @id_cliente AND m.ID = :id_medicion_actual
			GROUP BY ic.id, ic.codigoitem, i.nombre
			) AS A LEFT JOIN
			
(SELECT ic.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			WHERE c.ID = @id_cliente AND m.ID = :id_medicion_anterior
			GROUP BY ic.id
			) AS B on A.ID = B.ID2
			ORDER BY quiebre ASC";
		$param = array('id_cliente' => $id_cliente, 'id_medicion_actual' => $id_medicion_actual, 'id_medicion_anterior' => $id_medicion_anterior);
		$ranking_item = $em->getConnection()->executeQuery($sql,$param)->fetchAll();
		
		
		// RANKING POR VENDEDOR--------------------------------------------------
		$sql = "DECLARE @id_cliente integer = :id_cliente;
		SELECT *, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT e.id, e.nombre,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = @id_cliente AND m.ID = :id_medicion_actual
			GROUP BY e.ID, e.NOMBRE
			) AS A LEFT JOIN
			
(SELECT e.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = @id_cliente AND m.ID = :id_medicion_anterior
			GROUP BY e.ID
			) AS B on A.ID = B.ID2
			ORDER BY quiebre ASC";
			
		$param = array('id_cliente' => $id_cliente, 'id_medicion_actual' => $id_medicion_actual, 'id_medicion_anterior' => $id_medicion_anterior);
		$ranking_empleado = $em->getConnection()->executeQuery($sql,$param)->fetchAll();
		
		
		
		//RESPONSE
		$response = $this->render('CademReporteBundle:Ranking:index.html.twig',
			array(
				'forms' => array(
					'form_estudio' 	=> $form_estudio->createView(),
					'form_periodo' 	=> $form_periodo->createView(),
					'form_region' 	=> $form_region->createView(),
					'form_provincia' => $form_provincia->createView(),
					'form_comuna' 	=> $form_comuna->createView(),
					),
				'logofilename' => $logofilename,
				'logostyle' => $logostyle,
				'ranking_sala' => $ranking_sala,
				'ranking_empleado' => $ranking_empleado,
				'ranking_item' => $ranking_item,
				'estudios' => $estudios,
				
			)
		);

		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
	
	public function filtrosAction(Request $request)
    {
		$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
		$cacheseg = 1;
		$start = microtime(true);
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$data = $request->query->all();
		
		
		//CLIENTE
		$query = $em->createQuery(
			'SELECT c FROM CademReporteBundle:Cliente c
			JOIN c.usuarios u
			WHERE u.id = :id AND c.activo = 1')
			->setParameter('id', $user->getId());
		$clientes = $query->getResult();
		$cliente = $clientes[0];
		
		//DATOS
		$id_cliente = $cliente->getId();
		$id_medicion_actual = intval($data['f_periodo']['Periodo']);
		$id_estudio = intval($data['f_estudio']['Estudio']);// 0 = TODOS
		$array_region = $data['f_region']['Region'];
		$array_provincia = $data['f_provincia']['Provincia'];
		$array_comuna = $data['f_comuna']['Comuna'];
		foreach($array_comuna as $k => $v) $array_comuna[$k] = intval($v);
		
		
		//SE BUSCA MEDICION ANTERIOR
		$query = $em->createQuery(
			'SELECT m.id FROM CademReporteBundle:Medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			WHERE c.id = :idc
			ORDER BY m.fechainicio DESC')
			->setParameter('idc', $id_cliente);
		$mediciones = $query->getArrayResult();
		$listo = false;
		foreach($mediciones as $m)
		{
			if($listo)
			{
				$id_medicion_anterior = $m['id'];
				break;
			}
			if($m['id'] === $id_medicion_actual) $listo = true;
		}
		if($listo === false) $id_medicion_anterior = $id_medicion_actual;
		
		

		if($data['tb_sala'] === 't') $orderby_sala = "ASC";
		else $orderby_sala = "DESC";
		if($data['tb_producto'] === 't') $orderby_producto = "ASC";
		else $orderby_producto = "DESC";
		if($data['tb_empleado'] === 't') $orderby_empleado = "ASC";
		else $orderby_empleado = "DESC";
		
		
		
		//RANKING POR SALA--------------------------------------------------------------------
		$sql = "DECLARE @id_cliente_ integer = ? ;
		SELECT TOP(20)*, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT s.id, s.calle, s.numerocalle, sc.codigosala, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = @id_cliente_ AND m.id = ? AND s.COMUNA_ID IN ( ? )
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS A LEFT JOIN
			
(SELECT s.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = @id_cliente_ AND m.id = ?
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS B on A.ID = B.id2
			ORDER BY quiebre {$orderby_sala}";
		$param = array($id_cliente, $id_medicion_actual, $array_comuna, $id_medicion_anterior);
		$tipo_param = array(\PDO::PARAM_INT, \PDO::PARAM_INT, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY, \PDO::PARAM_INT);
		// $ranking_sala = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
		//CACHE
		$s1 = sha1($sql.print_r($param,true));
		if($cacheDriver->contains($s1)) $ranking_sala = $cacheDriver->fetch($s1);
		else
		{
			$ranking_sala = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
			$cacheDriver->save($s1, $ranking_sala, $cacheseg);
		}
		
		
		
		
		//RANKING POR PRODUCTO-----------------------------------------------
		$sql = "DECLARE @id_cliente integer = ? ;
		SELECT TOP(20)*, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT ic.id, ic.codigoitem, i.nombre,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALA s on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			INNER JOIN ITEM i on i.ID = ic.ITEM_ID
			WHERE c.ID = @id_cliente AND m.ID = ? AND s.COMUNA_ID IN ( ? )
			GROUP BY ic.id, ic.codigoitem, i.nombre
			) AS A LEFT JOIN
			
(SELECT ic.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			WHERE c.ID = @id_cliente AND m.ID = ?
			GROUP BY ic.id
			) AS B on A.ID = B.ID2
			ORDER BY quiebre {$orderby_producto}";
		$param = array($id_cliente, $id_medicion_actual, $array_comuna, $id_medicion_anterior);
		$tipo_param = array(\PDO::PARAM_INT, \PDO::PARAM_INT, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY, \PDO::PARAM_INT);
		// $ranking_item = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
		//CACHE
		$s1 = sha1($sql.print_r($param,true));
		if($cacheDriver->contains($s1)) $ranking_item = $cacheDriver->fetch($s1);
		else
		{
			$ranking_item = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
			$cacheDriver->save($s1, $ranking_item, $cacheseg);
		}
		
		
		// RANKING POR VENDEDOR--------------------------------------------------
		$sql = "DECLARE @id_cliente integer = ?;
		SELECT *, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT e.id, e.nombre,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALA s on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = @id_cliente AND m.ID = ? AND s.COMUNA_ID IN ( ? )
			GROUP BY e.ID, e.NOMBRE
			) AS A LEFT JOIN
			
(SELECT e.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = @id_cliente AND m.ID = ?
			GROUP BY e.ID
			) AS B on A.ID = B.ID2
			ORDER BY quiebre {$orderby_empleado}";
			
		$param = array($id_cliente, $id_medicion_actual, $array_comuna, $id_medicion_anterior);
		$tipo_param = array(\PDO::PARAM_INT, \PDO::PARAM_INT, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY, \PDO::PARAM_INT);
		// $ranking_empleado = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
		//CACHE
		$s1 = sha1($sql.print_r($param,true));
		if($cacheDriver->contains($s1)) $ranking_empleado = $cacheDriver->fetch($s1);
		else
		{
			$ranking_empleado = $em->getConnection()->executeQuery($sql,$param,$tipo_param)->fetchAll();
			$cacheDriver->save($s1, $ranking_empleado, $cacheseg);
		}
		
		
		
		$time_taken = microtime(true) - $start;
		//RESPONSE
		$response = array(
			'ranking_sala' => $ranking_sala,
			'ranking_item' => $ranking_item,
			'ranking_empleado' => $ranking_empleado,
			'id_medicion_actual' => $id_medicion_actual,
			'id_medicion_anterior' => $id_medicion_anterior,
			'time_taken' => $time_taken*1000,
		);
		$response = new JsonResponse($response);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
	
	public function regionAction(Request $request)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$data = $request->query->all();
		$dataform = $data['f_region'];
		//CLIENTE
		$query = $em->createQuery(
			'SELECT c FROM CademReporteBundle:Cliente c
			JOIN c.usuarios u
			WHERE u.id = :id AND c.activo = 1')
			->setParameter('id', $user->getId());
		$clientes = $query->getResult();
		$cliente = $clientes[0];
		
		foreach($dataform['Region'] as $r) $region[] = intval($r);

		//PROVINCIA
		$qb = $em->createQueryBuilder();
		$qb->select('DISTINCT p')
		   ->from('CademReporteBundle:Provincia', 'p')
		   ->innerJoin('p.comunas', 'c')
		   ->innerJoin('c.salas', 's')
		   ->innerJoin('s.salaclientes', 'sc')
		   ->innerJoin('sc.cliente', 'cl')
		   ->where($qb->expr()->andX($qb->expr()->eq('cl.id', ':id'), $qb->expr()->in('p.region_id', $region)))
		   ->orderBy('p.nombre', 'ASC')
		   ->setParameter('id', $cliente->getId());
		$query = $qb->getQuery();
		$provincias = $query->getResult();
		
		
		$choices_provincias = array();
		foreach($provincias as $r)
		{
			$choices_provincias[$r->getId()] = strtoupper($r->getNombre());
		}
		
		
		$form_provincia =  $this->get('form.factory')->createNamedBuilder('f_provincia', 'form')
			->add('Provincia', 'choice', array(
				'choices'   => $choices_provincias,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_provincias),
				
			))
			->getForm();
		
		
		
		//RESPONSE
		$response = $this->render(
			'CademReporteBundle::form.html.twig',
			array('form' => $form_provincia->createView())
		);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);
		
		return $response;
	}
	
	
	public function provinciaAction(Request $request)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$data = $request->query->all();
		$dataform = $data['f_provincia'];
		//CLIENTE
		$query = $em->createQuery(
			'SELECT c FROM CademReporteBundle:Cliente c
			JOIN c.usuarios u
			WHERE u.id = :id AND c.activo = 1')
			->setParameter('id', $user->getId());
		$clientes = $query->getResult();
		$cliente = $clientes[0];
		
		foreach($dataform['Provincia'] as $r) $provincia[] = intval($r);

		//COMUNA
		$qb = $em->createQueryBuilder();
		$qb->select('DISTINCT c')
		   ->from('CademReporteBundle:Comuna', 'c')
		   ->innerJoin('c.salas', 's')
		   ->innerJoin('s.salaclientes', 'sc')
		   ->innerJoin('sc.cliente', 'cl')
		   ->where($qb->expr()->andX($qb->expr()->eq('cl.id', ':id'), $qb->expr()->in('c.provincia_id', $provincia)))
		   ->orderBy('c.nombre', 'ASC')
		   ->setParameter('id', $cliente->getId());
		$query = $qb->getQuery();
		$comunas = $query->getResult();
		
		
		$choices_comunas = array();
		foreach($comunas as $r)
		{
			$choices_comunas[$r->getId()] = strtoupper($r->getNombre());
		}
		
		
		$form_comuna =  $this->get('form.factory')->createNamedBuilder('f_comuna', 'form')
			->add('Comuna', 'choice', array(
				'choices'   => $choices_comunas,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_comunas),
				
			))
			->getForm();
		
		
		
		//RESPONSE
		$response = $this->render(
			'CademReporteBundle::form.html.twig',
			array('form' => $form_comuna->createView())
		);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);
		
		return $response;
	}
}
