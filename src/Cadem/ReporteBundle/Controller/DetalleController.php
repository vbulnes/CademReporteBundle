<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DetalleController extends Controller
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
		
			
			
		
		
		$min = 0;
		$max = 2;
		
$tabla_resumen = array(
		'head' => array('SKU/SALA',
						'CATEGORIA',						
						'BIGGER (RENDIC) CAUPOLICÁN 191|K0305003',
						'JUMBO COSTANERA CENTER|K0408012',
						'JUMBO ANTOFAGASTA ANGAMOS L534|K0313003',
						'JUMBO ANTOFAGASTA PA CERDA L796|K0313013',
						'JUMBO BILBAO L501|K0310002',
						'JUMBO CALAMA L614|K0313012',
						'LIDER EXPRESS CALAMA INCA ORO L69|K0310008',
						'LIDER HIPER LA REINA L95|K0310009',
						'LIDER HIPER PUENTE ALTO L73|K0312001',
						'TOTTUS ANTOFAGASTA MALL L15|K0405028',
						'TOTTUS NATANIEL L12|K0302003',
						'UNIMARC COPIAPO ATACAMA L22|K0308001',
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),		
								'ST12' => mt_rand($min, $max),	
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
								'ST10' => mt_rand($min, $max),		
								'ST11' => mt_rand($min, $max),	
								'ST12' => mt_rand($min, $max),
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
		$response = $this->render('CademReporteBundle:Detalle:index.html.twig',
		array(
			'forms' => array(
				'form_estudio' 	=> $form_estudio->createView(),
				'form_periodo' 	=> $form_periodo->createView(),	
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
