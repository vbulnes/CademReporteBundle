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
		$form = $this->createFormBuilder($defaultData)
			->add('Filtro1', 'choice', array(
				'choices'   => array('d1' => 'Dato1', 'd2' => 'Dato2', 'd3' => 'Dato3'),
				'required'  => true,
				'multiple'  => true,
				'data' => array('d1','d2','d3')				
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

        return $this->render('CademReporteBundle:Default:index.html.twig', array('form' => $form->createView(), 'logo' => $logos[0], 'variables_clientes' => $variables_clientes ));
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
        //return $this->render('CademReporteBundle:Default:indicadores.html.twig', array('data' => $data['form']));
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
				)
		);
		if(in_array('d1', $data['form']['Filtro1'])) return new JsonResponse($responseA);
		else return new JsonResponse($responseB);
		
    }
}
