<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResumenController extends Controller
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
		$form_periodo = $this->createFormBuilder($defaultData)
			->add('Estudio', 'choice', array(
				'choices'   => $choices_estudio,
				'required'  => true,
				'multiple'  => false,
				'data' => '0'			
			))		
			->add('Periodo', 'choice', array(
				'choices'   => $mediciones,
				'required'  => true,
				'multiple'  => false,
				'data' => $ultima_medicion
			))
			->getForm();
		$form_canal = $this->createFormBuilder($defaultData)
			->add('Canal', 'choice', array(
				'choices'   => array(
						'1' => 'TODOS',
						'2' => 'SUPERMERCADO',
						'3' => 'MAYORISTA',
						'4' => 'MINORISTA'
				),
				'required'  => true,
				'multiple'  => false,
				'data' => '1'			
			))
			->getForm();
			
		$form_region = $this->createFormBuilder($defaultData)
			->add('Region', 'choice', array(
				'choices'   => $choices_regiones,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_regiones)
			))
			->getForm();
			
		$form_provincia = $this->createFormBuilder($defaultData)
			->add('Provincia', 'choice', array(
				'choices'   => $choices_provincias,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_provincias)
			))
			->getForm();
			
		$form_comuna = $this->createFormBuilder($defaultData)
			->add('Comuna', 'choice', array(
				'choices'   => $choices_comunas,
				'required'  => true,
				'multiple'  => true,
				'data' => array_keys($choices_comunas)
			))
			->getForm();
			
		//CONSULTA
		
		$sql = "SELECT (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre, ni.NOMBRE as SEGMENTO, ni2.NOMBRE as CATEGORIA, cad.NOMBRE as CADENA FROM QUIEBRE q
		INNER JOIN SALAMEDICION sm on sm.ID = q.SALAMEDICION_ID
		INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID
		INNER JOIN SALACLIENTE sc on sc.ID = sm.SALACLIENTE_ID
		INNER JOIN SALA s on s.ID = sc.SALA_ID
		INNER JOIN CADENA cad on cad.ID = s.CADENA_ID
		INNER JOIN CLIENTE c on c.ID = sc.CLIENTE_ID
		INNER JOIN ITEMCLIENTE ic on ic.ID = q.ITEMCLIENTE_ID AND ic.CLIENTE_ID = c.ID
		INNER JOIN NIVELITEM ni on ni.ID = ic.NIVELITEM_ID
		INNER JOIN NIVELITEM ni2 on ni2.ID = ic.NIVELITEM_ID2

		WHERE c.ID = 12 AND m.ID = 20

		GROUP BY ni.NOMBRE, ni2.NOMBRE, cad.NOMBRE";
		$datos_tabla = $em->getConnection()->executeQuery($sql)->fetchAll();
		
		// return print_r($datos_tabla);
		
		$min = 0;
		$max = 100;
		
$tabla_resumen = array(
		'head' => array('CATEGORIA/CADENA','SEGMENTO','LIDER','JUMBO','TOTTUS','SANTA ISABEL','SMU','TOTAL'),
		'body' => array(	
						array(/////////////////////////// AIR CARE //////////////////////////////////////
								'categoria'=> 'continuo electrico',
								'segmento' => 'air care',								
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array(							
								'categoria'=> 'continuo no electrico',
								'segmento' => 'air care',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),		
						array(
								'categoria'=> 'desinfectante',
								'segmento' => 'air care',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array(								
								'categoria'=> 'ambientales auto',
								'segmento' => 'air care',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array( /////////////////////////// HOME CLEANING //////////////////////////////////////								
								'categoria'=> 'baño',
								'segmento' => 'home cleaning',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),		
						array(								
								'categoria'=> 'baño-crema',
								'segmento' => 'home cleaning',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array(								
								'categoria'=> 'cocina',
								'segmento' => 'home cleaning',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),								
						array( /////////////////////////// HOME STORAGE //////////////////////////////////////																
								'categoria'=> 'bolsas',
								'segmento' => 'home storage',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),		
						array(								
								'categoria'=> 'potes',
								'segmento' => 'home storage',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array( /////////////////////////// pest control //////////////////////////////////////								
								'categoria'=> 'electricos',
								'segmento' => 'pest control',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),		
						array(								
								'categoria'=> 'continuos',
								'segmento' => 'pest control',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						array(								
								'categoria'=> 'instantaneos',
								'segmento' => 'pest control',
								'lider' => mt_rand($min, $max),
								'jumbo' => mt_rand($min, $max),
								'tottus' => mt_rand($min, $max),
								'sta isabel' => mt_rand($min, $max),
								'smu' => mt_rand($min, $max),
							),
						)
					);
		
	
		// $tabla_resumen = array(
		// 'cadenas' => array('LIDER','JUMBO','SANTA ISABEL','SMU','SODIMAC','MAYORISTA 10','ALVI','TOTAL'),
		// 'totales' => array('nombre'=>'QUIEBRE SC JOHNSON',
						   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max))
					 // ),
		// 'segmento' => array(array('nombre'=>'AIR CARE',
								  // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  // 'categoria'=>array(array('nombre'=>'AMBIENTALES AUTO',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															// ),		
													 // array('nombre'=>'CONTINUO ELECTRICO',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															// ),					
													 // array('nombre'=>'CONTINUO NO ELECTRICO',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															// )			
													 // ),		
																													 
								 // ),
							// array('nombre'=>'AUTO CARE',
								  // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  // 'categoria'=>array(array('nombre'=>'AMBIENTALES AUTO',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															// ),									
													 // ),		
																													 
								 // ), 
							// array('nombre'=>' HOME CLEANING',
								  // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  // 'categoria'=>array(array('nombre'=>'BAÑO',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															// ),																						
													 // array('nombre'=>'BAÑO-CREMA',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															// ),																						 
													 // array('nombre'=>'COCINA',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															// ),																						 
													 // array('nombre'=>'LIMPIAHORNOS',
														   // 'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															// ),									
																										 
																													 
								 // ), 								 
							// ),
						// ),
					// );
					
					
		
		//medicion join estudio
		$query = $em->createQuery(
			'SELECT m.nombre, m.fechafin FROM CademReporteBundle:Medicion m
			JOIN m.estudio e
			JOIN e.cliente c
			JOIN c.usuarios u
			WHERE u.id = :id')
			->setParameter('id', $user->getId());
		$mediciones_q = $query->getArrayResult();
		
		foreach($mediciones_q as $m)
		{
			$mediciones[] = $m['nombre'];
			$mediciones_corta[] = $m['fechafin']->format('d/m');
		}
		
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
		
		foreach ($quiebres_totales as $key => $value) $porc_quiebre[] = $quiebres[$key][1]/$quiebres_totales[$key][1]*100;
		
		
		
		
		$periodos= array(
			'tooltip' => $mediciones,
			'data' => $mediciones_corta,
		);
		$evolutivo= $porc_quiebre;	
		
		
		
		
		
		//RESPONSE
		$response = $this->render('CademReporteBundle:Resumen:index.html.twig',
		array(
			'forms' => array(
				'form_periodo' => $form_periodo->createView(),
				'form_region' 	=> $form_region->createView(),
				'form_provincia' => $form_provincia->createView(),
				'form_comuna' 	=> $form_comuna->createView(),
			),
			'tabla_resumen' => $tabla_resumen,
			'logofilename' => $logofilename,
			'logostyle' => $logostyle,
			'evolutivo' => json_encode($evolutivo),
			'periodos' => json_encode($periodos)
			)
		);

		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
	
	public function periodoAction(Request $request)
	{
	
		$min = 0;
		$max = 100;				
	
	$tabla_resumen = array(
		'cadenas' => array('LIDER','JUMBO','SANTA ISABEL','SMU','SODIMAC','MAYORISTA 10','ALVI','TOTAL'),
		'totales' => array('nombre'=>'QUIEBRE SC JOHNSON',
						   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max))
					 ),
		'segmento' => array(array('nombre'=>'AIR CARE',
								  'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  'categoria'=>array(array('nombre'=>'AMBIENTALES AUTO',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															),		
													 array('nombre'=>'CONTINUO ELECTRICO',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															),					
													 array('nombre'=>'CONTINUO NO ELECTRICO',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															)			
													 ),		
																													 
								 ),
							array('nombre'=>'AUTO CARE',
								  'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  'categoria'=>array(array('nombre'=>'AMBIENTALES AUTO',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
											
															),									
													 ),		
																													 
								 ), 
							array('nombre'=>' HOME CLEANING',
								  'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
								  'categoria'=>array(array('nombre'=>'BAÑO',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															),																						
													 array('nombre'=>'BAÑO-CREMA',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															),																						 
													 array('nombre'=>'COCINA',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															),																						 
													 array('nombre'=>'LIMPIAHORNOS',
														   'valores'=>array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
															),									
																										 
																													 
								 ), 								 
							),
						),
					);
					
		
		$evolutivo= array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max));	
		
		//RESPONSE
		$response = 
		array(
			'tabla_resumen' => $tabla_resumen,
			'evolutivo' => $evolutivo,
			);
		
		return new JsonResponse($response);
	}
	
	public function evolutivoAction(Request $request)
    {
		$min = 0;
		$max = 100;	
	
		$evolutivo= array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max));
		
		return new JsonResponse($evolutivo);
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
		
		$min = 0;
		$max = 100;
		
		switch($data['form']['Canal']){
			case '1':
				$min = 60;
				$max = 100;
			break;
			case '2':
				$min = 40;
				$max = 100;
			break;
			case '3':
				$min = 0;
				$max = 60;
			break;
		}
		
		$ranking = array(
			'head' => array('CATEGORIA','JUMBO','LIDER','TOTTUS','TOTAL'),
			'body' => array(
				array("CERVEZA", mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
				array("ENERGETICA", mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max)),
				array("RON", mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max))
			)
		);
		
		
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
				'presencia' =>	array(
					'type' => 'pie',
					'name' => 'Presencia',
					'data' => array(
							array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						)
				),
				'ranking' => $ranking
				
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
							array('name' => 'Cumple', 'y' => 0, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 100, 'color' => '#EB3737')
						)
				),
				'presencia' =>	array(
					'type' => 'pie',
					'name' => 'Presencia',
					'data' => array(
							array('name' => 'Cumple', 'y' => 44.5, 'color' => '#83A931'),
							array('name' => 'No cumple', 'y' => 55.5, 'color' => '#EB3737')
						)
				),
				'ranking' => $ranking
		);
		
		//RESPONSE
		if('1' === $data['form']['Periodo']) $response = new JsonResponse($responseA);
		else $response = new JsonResponse($responseB);
		
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);


		return $response;
    }
}
