<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller
{
    
	public function indexAction()
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		
		//CLIENTE, ESTUDIO
		$query = $em->createQuery(
			'SELECT c,e,ev,v FROM CademReporteBundle:Cliente c
			JOIN c.estudios e
			JOIN c.usuarios u
			JOIN e.estudiovariables ev
			JOIN ev.variable v
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
		
		$defaultData = array();
		$form_estudio = $this->createFormBuilder($defaultData)
			->add('Estudio', 'choice', array(
				'choices'   => $choices_estudio,
				'required'  => true,
				'multiple'  => false,
				'data' => '0'			
			))
			->getForm();
		
		$logofilename = $cliente->getLogofilename();
		$logostyle = $cliente->getLogostyle();
		
		//QUIEBRE ULTIMA MEDICION |||| OCUPANDO CASE Y DBAL SE PUEDE HACER CON UNA SOLA CONSULTA
		$query = $em->createQuery(
			'SELECT COUNT(q) FROM CademReporteBundle:Quiebre q
			WHERE q.hayquiebre = 1');
		$quiebre = $query->getSingleScalarResult();
		
		$query = $em->createQuery(
			'SELECT COUNT(q) FROM CademReporteBundle:Quiebre q');
		$cantidad_total = $query->getSingleScalarResult();
		
		$porc_quiebre = round($quiebre/$cantidad_total*100,1);
		
		
		//RESPONSE
		$response = $this->render('CademReporteBundle:Dashboard:index.html.twig',
		array(
			'forms' => array(
				'form_estudio' => $form_estudio->createView(),
			),
			'logofilename' => $logofilename,
			'logostyle' => $logostyle,
			'porc_quiebre' => $porc_quiebre,
			'estudios' => $estudios
		));

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

		$em = $this->getDoctrine()->getManager();
		$start = microtime(true);//SE MIDE CUANTO SE DEMORAN LAS CONSULTAS Y PROCESAMIENTO
		$user = $this->getUser();
		
		//medicion join estudio
		$query = $em->createQuery(
			'SELECT m.nombre FROM CademReporteBundle:Medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			JOIN c.usuarios u
			WHERE u.id = :id')
			->setParameter('id', $user->getId());
		$mediciones_q = $query->getArrayResult();
		
		foreach($mediciones_q as $m) $mediciones[] = $m['nombre'];
		
		//quiebre join salamedicion join medicion
		$query = $em->createQuery(
			'SELECT COUNT(q) FROM CademReporteBundle:Quiebre q
			JOIN q.salamedicion sm
			JOIN sm.medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			JOIN c.usuarios u
			WHERE u.id = :id
			GROUP BY m.id')
			->setParameter('id', $user->getId());
		$quiebres_totales = $query->getResult();

		$query = $em->createQuery(
			'SELECT COUNT(q) FROM CademReporteBundle:Quiebre q
			JOIN q.salamedicion sm
			JOIN sm.medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			JOIN c.usuarios u
			WHERE u.id = :id
			AND q.hayquiebre = 1
			GROUP BY m.id')
			->setParameter('id', $user->getId());
		$quiebres = $query->getResult();
		
		foreach ($quiebres_totales as $key => $value) $porc_quiebre[] = round($quiebres[$key][1]/$quiebres_totales[$key][1]*100,1);
		
		$time_taken = microtime(true) - $start;
		
		$response = array(
			'evolutivo' => array(
				'mediciones' => $mediciones,
				'serie_quiebre' => array(
					'name' => '% Quiebre',
					'color' => '#4572A7',
					'type' => 'spline',
					'data' => $porc_quiebre,
					'tooltip' => array(
						'valueSuffix' => ' %'
					)
				)
			),
			'time_ms' => $time_taken*1000
		);
		
		
		// $responseA = array(
				// 'cobertura' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Cobertura',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 20, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 80, 'color' => '#EB3737')
						// )
				// ),
				// 'atributo' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Atributo',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 35.5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 64.5, 'color' => '#EB3737')
						// )
				// ),
				// 'quiebre' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Quiebre',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 55.5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 44.5, 'color' => '#EB3737')
						// )
				// ),
				// 'precio' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Presencia',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						// )
				// ),
				// 'evo_quiebre_precio' => array(
					// 'precio' => array(
						// 'name' => 'Promedio Precio',
						// 'color' => '#89A54E',
						// 'yAxis' => 1,
						// 'type' => 'spline',
						// 'data' => array(1300.0, 1100.9, 1000.5, 4490.5, 1889.2, 1198.5, 1500.2, 1612.5, 1332.3, 845.3, 1753.9, 1798.6),
						// 'tooltip' => array(
							// 'valuePrefix' => '$'
						// )
					// ),
					// 'quiebre' => array(
						// 'name' => '% Quiebre',
						// 'color' => '#4572A7',
						// 'type' => 'spline',
						// 'data' => array(73.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 39.6),
						// 'tooltip' => array(
							// 'valueSuffix' => ' %'
						// )
					// )
				// ),
				// 'evo_cobertura' => array(
					// 'cobertura' => array(
						// 'name' => '% de Cobertura',
						// 'color' => '#4572A7',
						// 'type' => 'spline',
						// 'data' => array(13.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 13.3, 18.3, 13.9, 39.6),
						// 'tooltip' => array(
							// 'valueSuffix' => ' %'
						// )
					// )
				// )
				
		// );
		// $responseB = array( 
				// 'cobertura' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Cobertura',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 60, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 40, 'color' => '#EB3737')
						// )
				// ),
				// 'atributo' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Atributo',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 15.5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 84.5, 'color' => '#EB3737')
						// )
				// ),
				// 'quiebre' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Quiebre',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 95, 'color' => '#EB3737')
						// )
				// ),
				// 'precio' =>	array(
					// 'type' => 'pie',
					// 'name' => 'Presencia',
					// 'data' => array(
							// array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							// array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						// )
				// ),
				// 'evo_quiebre_precio' => array(
					// 'precio' => array(
						// 'name' => 'Promedio Precio',
						// 'color' => '#89A54E',
						// 'yAxis' => 1,
						// 'type' => 'spline',
						// 'data' => array(1300.0, 1100.9, 1000.5, 4490.5, 1889.2, 1198.5, 1500.2, 1612.5, 1332.3, 845.3, 1753.9, 1798.6),
						// 'tooltip' => array(
							// 'valuePrefix' => '$'
						// )
					// ),
					// 'quiebre' => array(
						// 'name' => '% Quiebre',
						// 'color' => '#4572A7',
						// 'type' => 'spline',
						// 'data' => array(73.0, 61.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 39.6),
						// 'tooltip' => array(
							// 'valueSuffix' => ' %'
						// )
					// )
				// ),
				// 'evo_cobertura' => array(
					// 'cobertura' => array(
						// 'name' => '% de Cobertura',
						// 'color' => '#4572A7',
						// 'type' => 'spline',
						// 'data' => array(73.0, 11.9, 20.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 19.6),
						// 'tooltip' => array(
							// 'valueSuffix' => ' %'
						// )
					// )
				// )
		// );
		
		//RESPONSE
		// if('1' === $data['form']['Estudio']) $response = new JsonResponse($responseA);
		// else $response = new JsonResponse($responseB);
		$response = new JsonResponse($response);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
}
