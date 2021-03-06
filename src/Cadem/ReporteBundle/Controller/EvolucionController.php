<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class EvolucionController extends Controller
{
    
	public function indexAction()
    {
		$defaultData = array();
		$form_periodo = $this->createFormBuilder($defaultData)
			->add('Estudio', 'choice', array(
				'choices'   => array(
						'1' => 'QUIEBRE',
						'2' => 'QUIEBRE Y PRECIO',
						'3' => 'COBERTURA',
				),
				'required'  => true,
				'multiple'  => false,
				'data' => '1'			
			))		
			->add('Periodo', 'choice', array(
				'choices'   => array(
						'1' => '2013-03 SEM 1_7',
						'2' => '2013-03 SEM 8_14',
						'3' => '2013-03 SEM 15_24',
						'4' => '2013-03 SEM 25_31'
				),
				'required'  => true,
				'multiple'  => false,
				'data' => '1'			
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
				'choices'   => array(
						'1' => strtoupper ('Arica y Parinacota'),
						'2' => strtoupper ('Tarapacá'),
						'3' => strtoupper ('Antofagasta'),
						'4' => strtoupper ('Atacama'),
						'5' => strtoupper ('Coquimbo'),
						'6' => strtoupper ('Valparaíso'),
						'7' => strtoupper ('Metropolitana de Santiago'),
						'8' => strtoupper ('Libertador General Bernardo O\'Higgins'),
						'9' => strtoupper ('Maule'),
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6','7','8','9')			
			))
			->getForm();
			
		$form_provincia = $this->createFormBuilder($defaultData)
			->add('Provincia', 'choice', array(
				'choices'   => array(
						'1' => strtoupper ('Chacabuco'),
						'2' => strtoupper ('Cordillera'),
						'3' => strtoupper ('Maipo'),
						'4' => strtoupper ('Melipilla'),
						'5' => strtoupper ('Santiago'),
						'6' => strtoupper ('Talagante'),
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6')			
			))
			->getForm();
			
		$form_comuna = $this->createFormBuilder($defaultData)
			->add('Comuna', 'choice', array(
				'choices'   => array(
						'1' => strtoupper ('Colina'),
						'2' => strtoupper ('Lampa'),
						'3' => strtoupper ('Puente Alto'),
						'4' => strtoupper ('San José de Maipo'),
						'5' => strtoupper ('Calera de Tango'),
						'6' => strtoupper ('Curacaví'),
						'7' => strtoupper ('Cerrillos'),
						'8' => strtoupper ('La Pintana'),
						'9' => strtoupper ('Lo Barnechea'),
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5','6','7','8','9')			
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
		
		$min = 0;
		$max = 100;
		
		$tabla_resumen = array(
		'head' => array('SKU/SALA',
						'CATEGORIA',						
						'2013-02 SEM 07_09',
						'2013-02 SEM 14_16',
						'2013-02 SEM 21_23',
						'2013-03 SEM 28_02',
						'2013-03 SEM 28_02',
						'2013-03 SEM 14_16',
						'2013-03 SEM 28_02',
						'2013-03 SEM 28_02',
						'2013-03 SEM 14_16',						
						'52 Semanas',
						),
		'body' => array(	
						array(								
								'SKU'=> 'IMPERIAL BOTELLA PACK 4X330CC',
								'categoria'=> 'CERVEZAS',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),
						array(								
								'SKU'=> 'CORONITA 5° CAJA 24 X 207CC',
								'categoria'=> 'CERVEZAS',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),		
						array(								
								'SKU'=> 'RED BULL SUGAR FREE 250CC',
								'categoria'=> 'ENERGETICAS',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),
						array(
								'SKU'=> 'RED BULL ENERGY DRINK VETTEL EDITION LATA 355CC',
								'categoria'=> 'ENERGETICAS',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),
						array(								
								'SKU'=> 'CHAMPAGNE UNDURRAGA BRUT 12,5° 750CC',
								'categoria'=> 'ESPUMANTES',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),		
						array(								
								'SKU'=> 'RON FLOR DE CAÑA 40° DORADO 1750CC',
								'categoria'=> 'RON',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),
						array(
								'SKU'=> 'RON FLOR DE CAÑA 40° 7 AÑOS 750CC',
								'categoria'=> 'RON',
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),					
						array(
								'SKU'=> 'CORTTON ERRAZURIZ CABERNET SAUVIGNON 750CC',
								'categoria'=> 'VINO',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),	
						array(
								'SKU'=> 'VINO UNDURRAGA PINOT CABERNET SAUVIGNON 750CC',
								'categoria'=> 'VINO',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),	
						array(
								'SKU'=> 'IMPERIAL BOTELLA PACK 4X330CC',
								'categoria'=> 'VINO',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),	
						array(
								'SKU'=> 'VINO CALITERRA RESERVA CABERNET SAUVIGNON',
								'categoria'=> 'VINO',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),	
						array(
								'SKU'=> 'VINO VERAMONTE SAUVIGNON BLANC',
								'categoria'=> 'VINO',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
							),	
						array(
								'SKU'=> 'VODKA STOLICHNAYA CLASSIC 40° 750CC',
								'categoria'=> 'VODKA',								
								'ST1' => mt_rand($min, $max),
								'ST2' => mt_rand($min, $max),
								'ST3' => mt_rand($min, $max),
								'ST4' => mt_rand($min, $max),
								'ST5' => mt_rand($min, $max),
								'ST6' => mt_rand($min, $max),
								'ST7' => mt_rand($min, $max),
								'ST8' => mt_rand($min, $max),
								'ST9' => mt_rand($min, $max),								
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
		
		$periodos= array('2012-03 SEM 1_7','2013-03 SEM 8_13','2013-03 SEM 15_20','2013-03 SEM 21_26','2013-03 SEM 27_31','2013-04 SEM 1_7','2013-04 SEM 8_13','2013-04 SEM 14_19');
		$evolutivo= array(mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max),mt_rand($min, $max),mt_rand($min, $max));	
		
		//RESPONSE
		$response = $this->render('CademReporteBundle:Evolucion:index.html.twig',
		array(
			'forms' => array(
				'form_periodo' => $form_periodo->createView(),
				'form_region' 	=> $form_region->createView(),
				'form_provincia' => $form_provincia->createView(),
				'form_comuna' 	=> $form_comuna->createView(),
			),
			'tabla_resumen' => $tabla_resumen,
			'logo' => $logos[0],
			'variables_clientes' => $variables_clientes,
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
