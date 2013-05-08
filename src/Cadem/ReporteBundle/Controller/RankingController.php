<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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
		
		if(count($mediciones) > 0) $ultima_medicion = array_keys($mediciones)[0];
		else $ultima_medicion = null;
		
		
		$defaultData = array();
		$form_estudio = $this->createFormBuilder($defaultData)
			->add('Estudio', 'choice', array(
				'choices'   => $choices_estudio,
				'required'  => true,
				'multiple'  => false,
				'data' => '0'			
			))
			->getForm();
		$form_periodo = $this->createFormBuilder($defaultData)
			->add('Periodo', 'choice', array(
				'choices'   => $mediciones,
				'required'  => true,
				'multiple'  => false,
				'data' => $ultima_medicion			
			))
			->getForm();
			
		$form_region = $this->createFormBuilder($defaultData)
			->add('Region', 'choice', array(
				'choices'   => $choices_regiones,
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6','7','8','9')			
			))
			->getForm();
			
		$form_provincia = $this->createFormBuilder($defaultData)
			->add('Provincia', 'choice', array(
				'choices'   => $choices_provincias,
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6')			
			))
			->getForm();
			
		$form_comuna = $this->createFormBuilder($defaultData)
			->add('Comuna', 'choice', array(
				'choices'   => $choices_comunas,
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6','7','8','9')			
			))
			->getForm();
			
		
		
		//RANKING POR SALA--------------------------------------------------------------------
		// $rsm = new ResultSetMapping;
		// $rsm->addEntityResult('CademReporteBundle:Sala', 's');
		// $rsm->addJoinedEntityResult('CademReporteBundle:Salacliente' , 'sc', 's', 'salaclientes');
		// $rsm->addJoinedEntityResult('CademReporteBundle:Salamedicion' , 'sm', 'sc', 'salamediciones');
		// $rsm->addJoinedEntityResult('CademReporteBundle:Cliente' , 'c', 'sc', 'cliente');
		// $rsm->addJoinedEntityResult('CademReporteBundle:Medicion' , 'm', 'sm', 'medicion');
		// $rsm->addJoinedEntityResult('CademReporteBundle:Quiebre' , 'q', 'sm', 'quiebres');
		// $rsm->addScalarResult('quiebre','quiebre');
		// $rsm->addScalarResult('quiebre2','quiebre2');
		// $rsm->addScalarResult('id2','id2');
		// $rsm->addScalarResult('id','id');
		

		// $query = $em->createNativeQuery('SELECT * FROM 
// (SELECT s.id, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALA s
			// INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			// INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			// INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			// INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			// INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			// WHERE c.id = 12 AND m.id = 1
			// GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			// ) AS A LEFT JOIN
			
// (SELECT s.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre2 FROM SALA s
			// INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			// INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			// INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			// INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			// INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			// WHERE c.id = 12 AND m.id = 2
			// GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			// ) AS B on A.ID = B.id2', $rsm);

		// $result = $query->getArrayResult();
		
		
		
		$sql = "SELECT *, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT s.id, s.calle, s.numerocalle, sc.codigosala, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = 12 AND m.id = 1
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS A LEFT JOIN
			
(SELECT s.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALA s
			INNER JOIN SALACLIENTE sc on s.ID = sc.SALA_ID
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			WHERE c.id = 12 AND m.id = 2
			GROUP BY sc.id, s.id, s.calle, s.numerocalle, sc.codigosala
			) AS B on A.ID = B.id2
			ORDER BY quiebre ASC";

		$ranking_sala = $em->getConnection()->executeQuery($sql)->fetchAll();
		
		//RANKING POR PRODUCTO-----------------------------------------------
		$sql = "SELECT *, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT ic.id, ic.codigoitem,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			WHERE c.ID = 12 AND m.ID = 1
			GROUP BY ic.id, ic.codigoitem
			) AS A LEFT JOIN
			
(SELECT ic.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN ITEMCLIENTE ic on q.ITEMCLIENTE_ID = ic.ID
			WHERE c.ID = 12 AND m.ID = 2
			GROUP BY ic.id
			) AS B on A.ID = B.ID2
			ORDER BY quiebre ASC";
			
		$ranking_item = $em->getConnection()->executeQuery($sql)->fetchAll();
		
		
		// RANKING POR VENDEDOR--------------------------------------------------
		$sql = "SELECT *, ROUND(quiebre-quiebre_anterior, 1) as diferencia FROM 
(SELECT e.id, e.nombre,(SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = 12 AND m.ID = 1
			GROUP BY e.ID, e.NOMBRE
			) AS A LEFT JOIN
			
(SELECT e.id as id2, (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre_anterior FROM SALACLIENTE sc
			INNER JOIN SALAMEDICION sm on sm.SALACLIENTE_ID = sc.ID
			INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
			INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
			INNER JOIN QUIEBRE q on q.SALAMEDICION_ID = sm.ID
			INNER JOIN EMPLEADO e on e.ID = sc.EMPLEADO_ID
			WHERE c.ID = 12 AND m.ID = 2
			GROUP BY e.ID
			) AS B on A.ID = B.ID2
			ORDER BY quiebre ASC";
			
		$ranking_empleado = $em->getConnection()->executeQuery($sql)->fetchAll();
		
		// return print_r($ranking_sala,true);

		
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
			)
		);

		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
	
	public function indicadoresAction(Request $request)
    {
		// $start = microtime(true);

		// $em = $this->getDoctrine()->getManager();
		// $query = $em->createQuery(
			// 'SELECT t FROM CademReporteBundle:Test t'
		// )->setMaxResults(10000);
		
		// $cacheDriver = new \Doctrine\Common\Cache\ApcCache();

		
		//$cacheDriver->deleteAll();
		// if($prueba = $cacheDriver->contains('my_query_result')){
			// $test = $cacheDriver->fetch('my_query_result');
		// }
		// else{
			// $test = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
			// $cacheDriver->save('my_query_result', $test, 20);
		// }
		
		// $time_taken = microtime(true) - $start;
		
		$data = $request->query->all();

		
		
		$responseA = array(
				'cobertura' =>	array(
					'type' => 'pie',
					'name' => 'Cobertura',
					'data' => array(
							array('name' => 'Cumple', 'y' => 20, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 80, 'color' => '#EB3737')
						)
				),
				'atributo' =>	array(
					'type' => 'pie',
					'name' => 'Atributo',
					'data' => array(
							array('name' => 'Cumple', 'y' => 35.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 64.5, 'color' => '#EB3737')
						)
				),
				'quiebre' =>	array(
					'type' => 'pie',
					'name' => 'Quiebre',
					'data' => array(
							array('name' => 'Cumple', 'y' => 55.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 44.5, 'color' => '#EB3737')
						)
				),
				'precio' =>	array(
					'type' => 'pie',
					'name' => 'Presencia',
					'data' => array(
							array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						)
				),
				'evo_quiebre_precio' => array(
					'precio' => array(
						'name' => 'Promedio Precio',
						'color' => '#89A54E',
						'yAxis' => 1,
						'type' => 'spline',
						'data' => array(1300.0, 1100.9, 1000.5, 4490.5, 1889.2, 1198.5, 1500.2, 1612.5, 1332.3, 845.3, 1753.9, 1798.6),
						'tooltip' => array(
							'valuePrefix' => '$'
						)
					),
					'quiebre' => array(
						'name' => '% Quiebre',
						'color' => '#4572A7',
						'type' => 'spline',
						'data' => array(73.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 39.6),
						'tooltip' => array(
							'valueSuffix' => ' %'
						)
					)
				),
				'evo_cobertura' => array(
					'cobertura' => array(
						'name' => '% de Cobertura',
						'color' => '#4572A7',
						'type' => 'spline',
						'data' => array(13.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 13.3, 18.3, 13.9, 39.6),
						'tooltip' => array(
							'valueSuffix' => ' %'
						)
					)
				)
				
		);
		$responseB = array( 
				'cobertura' =>	array(
					'type' => 'pie',
					'name' => 'Cobertura',
					'data' => array(
							array('name' => 'Cumple', 'y' => 60, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 40, 'color' => '#EB3737')
						)
				),
				'atributo' =>	array(
					'type' => 'pie',
					'name' => 'Atributo',
					'data' => array(
							array('name' => 'Cumple', 'y' => 15.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 84.5, 'color' => '#EB3737')
						)
				),
				'quiebre' =>	array(
					'type' => 'pie',
					'name' => 'Quiebre',
					'data' => array(
							array('name' => 'Cumple', 'y' => 5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 95, 'color' => '#EB3737')
						)
				),
				'precio' =>	array(
					'type' => 'pie',
					'name' => 'Presencia',
					'data' => array(
							array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						)
				),
				'evo_quiebre_precio' => array(
					'precio' => array(
						'name' => 'Promedio Precio',
						'color' => '#89A54E',
						'yAxis' => 1,
						'type' => 'spline',
						'data' => array(1300.0, 1100.9, 1000.5, 4490.5, 1889.2, 1198.5, 1500.2, 1612.5, 1332.3, 845.3, 1753.9, 1798.6),
						'tooltip' => array(
							'valuePrefix' => '$'
						)
					),
					'quiebre' => array(
						'name' => '% Quiebre',
						'color' => '#4572A7',
						'type' => 'spline',
						'data' => array(73.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 39.6),
						'tooltip' => array(
							'valueSuffix' => ' %'
						)
					)
				),
				'evo_cobertura' => array(
					'cobertura' => array(
						'name' => '% de Cobertura',
						'color' => '#4572A7',
						'type' => 'spline',
						'data' => array(73.0, 11.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 19.6),
						'tooltip' => array(
							'valueSuffix' => ' %'
						)
					)
				)
		);
		
		//RESPONSE
		if('1' === $data['form']['Estudio']) $response = new JsonResponse($responseA);
		else $response = new JsonResponse($responseB);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
}
