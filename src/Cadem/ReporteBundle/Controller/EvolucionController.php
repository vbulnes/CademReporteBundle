<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Session;

class EvolucionController extends Controller
{
    
	public function indexAction()
    {
		$session = $this->get("session");
	
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
												
		$form_estudio = $this->get('form.factory')->createNamedBuilder('f_estudio', 'form')
			->add('Estudio', 'choice', array(
				'choices'   => $choices_estudio,
				'required'  => true,
				'multiple'  => false,
				'data' => '0',
				'attr' => array('id' => 'myValue')
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
		
		//CONSULTA
		
		$sql = "SELECT (SUM(case when q.hayquiebre = 1 then 1 else 0 END)*100.0)/COUNT(q.id) as quiebre, i.NOMBRE as PRODUCTO,  ni.NOMBRE as SEGMENTO, m.NOMBRE FROM QUIEBRE q
				INNER JOIN SALAMEDICION sm on sm.ID = q.SALAMEDICION_ID
				INNER JOIN MEDICION m on m.ID = sm.MEDICION_ID AND m.ID IN (SELECT TOP(12) m2.ID FROM MEDICION m2 WHERE m2.ID = sm.MEDICION_ID ORDER BY m2.FECHAINICIO ASC)
				INNER JOIN SALACLIENTE sc on sc.ID = sm.SALACLIENTE_ID
				INNER JOIN ITEMCLIENTE ic on ic.ID = q.ITEMCLIENTE_ID AND ic.CLIENTE_ID = 12
				INNER JOIN NIVELITEM ni on ni.ID = ic.NIVELITEM_ID
				INNER JOIN ITEM i on i.ID = ic.ITEM_ID
				GROUP BY  ni.NOMBRE,i.NOMBRE,m.NOMBRE";
		
		$evolucion_quiebre = $em->getConnection()->executeQuery($sql)->fetchAll();
		$niveles=2;
		
		// CONSTRUIR EL ENCABEZADO DE LA TABLA
		
		if($niveles==1)
			$head=array('SKU/MEDICIÓN');
		else
			$head=array('SKU/MEDICIÓN','CATEGORIA');			
		
		$mediciones=array();
		
		// Generamos el head de la tabla, y las mediciones
		foreach($evolucion_quiebre as $registro)
		{
			// print_r($resumen_quiebre);
			if(!in_array($registro['NOMBRE'],$head))
			{
				array_push($head,$registro['NOMBRE']);
				array_push($mediciones,$registro['NOMBRE']);
			}		
		}						
		
		// usort($mediciones, array($this,"sortFunction"));
		
		// print_r($mediciones);		
		
		array_merge($head,$mediciones);					
		array_push($head,'TOTAL');
		
		// Guardamos resultado de consulta en variable de sesión para reusarlas en un action posterior
		$session->set("mediciones",$mediciones);
		// $session->set("agregaciones",$agregaciones);
		$session->set("evolucion_quiebre",$evolucion_quiebre);
				
		//RESPONSE
		$response = $this->render('CademReporteBundle:Evolucion:index.html.twig',
		array(
			'forms' => array(
				'form_estudio' 	=> $form_estudio->createView(),
				'form_region' 	=> $form_region->createView(),
				'form_provincia' => $form_provincia->createView(),
				'form_comuna' 	=> $form_comuna->createView(),
			),
			'head' => $head,
			'logofilename' => $logofilename,
			'logostyle' => $logostyle,			
			// 'evolutivo' => json_encode($evolutivo),
			// 'periodos' => json_encode($periodos)
			)
		);
		//CACHE
		$response->setPrivate();
		$response->setMaxAge(1);

		return $response;
    }
	
	// Definimos un comparador de fechas para ordenar las mediciones
	function sortFunction( $a, $b ) {
		echo $a;
		return strtotime(date('Y-m',strtotime($a))) - strtotime(date('Y-m',strtotime($b)));
	}		
	
	public function tablaAction(Request $request)
	{		
		// CONSTRUIR EL CUERPO DE LA TABLA

		$session=$this->get("session");
		
		$mediciones=$session->get("mediciones");		
		$evolucion_quiebre=$session->get("evolucion_quiebre");
			
		$body=array();				
		
		/* Recorrer vector de mediciones, y resultado de la consulta de forma sincrona; cada vez que se encuentre coincidencia hacer 
		fetch en resultado consulta, si no, asignar vacio */
				
		$num_regs=count($evolucion_quiebre);
		$cont_meds=0;
		$cont_regs=0;
		$num_meds=count($mediciones);		
		// Estructura que almacena los sumarizados		
		
		while($cont_regs<$num_regs)
		{			
			$fila=array();			
			
			$fila[0]=utf8_encode(substr($evolucion_quiebre[$cont_regs]['PRODUCTO'],0,20));				
			$fila[1]=$evolucion_quiebre[$cont_regs]['SEGMENTO'];					
			
			while($cont_meds<$num_meds)
			{
				// Si el contador de registros excede su numero de elementos, aun pueden haber mediciones que no hagan match
				if($cont_regs>=$num_regs)
				{
					$fila[$cont_meds+2]='-';										
					$cont_meds++;
				}	
				else
				{
					if($mediciones[$cont_meds]==$evolucion_quiebre[$cont_regs]['NOMBRE'])
					{
						$fila[$cont_meds+2]=round($evolucion_quiebre[$cont_regs]['quiebre'],1);		
						// $agregaciones[$resumen_quiebre[$cont_regs]['SEGMENTO']][$cont_cads]+=round($resumen_quiebre[$cont_regs]['quiebre'],1);
						$cont_meds++;
						$cont_regs++;
					}
					else
					{
						$fila[$cont_meds+2]='-';	
						$cont_meds++;					
					}
				}
			}			
			$fila[$cont_meds+2]=0;
			// Si se recorrieron todas las cadenas, agrego la fila al body y reseteo el contador de cadenas
			$cont_meds=0;
			array_push($body,(object)$fila);
		}			
		// print_r($body);
		// print_r($agregaciones);
		// $session->close();					
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $num_regs,
			"iTotalDisplayRecords" => $num_regs,
			"aaData" => $body
		);		
		return new JsonResponse($output);
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
