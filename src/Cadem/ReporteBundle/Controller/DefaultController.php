<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    
	public function indexAction()
    {
		$defaultData = array();
		$form_agencia = $this->createFormBuilder($defaultData)
			->add('Agencia', 'choice', array(
				'choices'   => array(
						'1' => 'ANTOFAGASTA',
						'2' => 'COQUIMBO',
						'3' => 'VALPARAISO',
						'4' => 'METROPOLITANA'
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4')				
			))
			->getForm();
		$form_sala = $this->createFormBuilder($defaultData)
			->add('Sala', 'choice', array(
				'choices'   => array(
						'1' => 'STA ISABEL PADRE LAS CASAS',
						'2' => 'SOC.KAROMEHI LIMITAD',
						'3' => 'COMERCIALIZADORA ANDREANI LTDA',
						'4' => 'SUAREZ Y CIA LTDA',
						'5' => 'PANIFICADORA LA EURO'
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3','4','5')				
			))
			->getForm();
			
		$form_sku = $this->createFormBuilder($defaultData)
			->add('SKU', 'choice', array(
				'choices'   => array(
						'1' => 'JABON',
						'2' => 'SHAMPOO',
						'3' => 'MANTEQUILLA'
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2','3')				
			))
			->getForm();
			
		$form_categoria = $this->createFormBuilder($defaultData)
			->add('Categoria', 'choice', array(
				'choices'   => array(
						'1' => 'LIMPIEZA',
						'2' => 'ALIMENTO'
				),
				'required'  => true,
				'multiple'  => true,
				'data' => array('1','2')				
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

        return $this->render('CademReporteBundle:Default:index.html.twig',
		array(
			'forms' => array(
				'form_agencia' => $form_agencia->createView(),
				'form_sala' => $form_sala->createView(),
				'form_sku' => $form_sku->createView(),
				'form_categoria' => $form_categoria->createView()
			),
			'logo' => $logos[0],
			'variables_clientes' => $variables_clientes )
		);
    }
	
	public function indicadoresAction(Request $request)
    {
		// $defaultData = array();
		// $form = $this->createFormBuilder($defaultData)
			// ->add('Filtro1', 'choice', array(
				// 'choices'   => array('d1' => 'Dato1', 'd2' => 'Dato2', 'd3' => 'Dato3'),
				// 'required'  => true,
				// 'multiple'  => true,
				// 'data' => array('d1','d2','d3')
			// ))
			// ->getForm();
		// $form->bind($request);
		// $data = $form->getData();
		
		$data = $request->request->all();
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
				'ranking' => array(
					'head' => array(
						'ID', 'SALA', 'PROM', 'PROM ANT', 'DIF'
					),
					'body' => array(
						array('1','STA ISABEL', '91%', '90%', '1'),
						array('2','SUEAREZ Y CIA', '71%', '90%', '-29'),
						array('3','LIDER', '81%', '90%', '-9')
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
				)
		);
		if(in_array('1', $data['form']['Agencia'])) return new JsonResponse($responseA);
		else return new JsonResponse($responseB);
		
    }
}
