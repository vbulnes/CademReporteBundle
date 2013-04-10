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
		$defaultData = array();
		$form_estudio = $this->createFormBuilder($defaultData)
			->add('Estudio', 'choice', array(
				'choices'   => array(
						'0' => 'TODOS',
						'1' => 'QUIEBRE Y PRECIO',
						'2' => 'COBERTURA',
				),
				'required'  => true,
				'multiple'  => false,
				'data' => '0'			
			))
			->getForm();
		
			
		
			
		
		
		//PARAMETROS
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT c,l,v,vc FROM CademReporteBundle:Cliente c
            JOIN c.variables_clientes vc
			JOIN vc.variable v
			JOIN c.logos l
			JOIN c.usuarios u
            WHERE u.id = :id AND l.activo = 1 AND v.activo = 1 AND vc.activo = 1'
		)->setParameter('id', $user->getId());
		
		$cliente = $query->getSingleResult();
		$logos = $cliente->getLogos();
		$variables_clientes = $cliente->getVariablesClientes();

		
		//RESPONSE
		$response = $this->render('CademReporteBundle:Dashboard:index.html.twig',
		array(
			'forms' => array(
				'form_estudio' => $form_estudio->createView(),
			),
			'logo' => $logos[0],
			'variables_clientes' => $variables_clientes)
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
