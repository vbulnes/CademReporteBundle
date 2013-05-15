<?php

namespace Cadem\ReporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;

class HelperController extends Controller
{
	// DEVUELVE TODAS LAS PROVINCIAS QUE TENGAN COMO PADRE UN ARRAY DE REGIONES.
	// SE DEVUELVE EL HTML QUE CORRESPONDE AL FILTRO DE PROVINCIA.
	// RECIBE LAS REGIONES EN UNA VARIABLE ['f_region']
	public function filtroregionAction(Request $request)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$data = $request->query->all();
		$dataform = $data['f_region'];
		//CLIENTE DEL USUARIO. SE PODRIA SACAR EL ID DE CLIENTE DEL PROPIO USUARIO
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
	
	// DEVUELVE TODAS LAS COMUNAS QUE TENGAN COMO PADRE UN ARRAY DE PROVINCIAS.
	// SE DEVUELVE EL HTML QUE CORRESPONDE AL FILTRO DE COMUNA.
	// RECIBE LAS PROVINCIAS EN UNA VARIABLE ['f_provincia']
	public function filtroprovinciaAction(Request $request)
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
