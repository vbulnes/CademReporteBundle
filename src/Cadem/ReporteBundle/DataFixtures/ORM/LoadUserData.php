<?php

namespace Cadem\ReporteBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cadem\ReporteBundle\Entity\Cliente;
use Cadem\ReporteBundle\Entity\Logo;
use Cadem\ReporteBundle\Entity\Variable;
use Cadem\ReporteBundle\Entity\VariableCliente;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
	$cliente_soprole = new Cliente();
	$cliente_soprole->setNombre('soprole');
	$cliente_soprole->setRut('111-1');
	$cliente_soprole->setTipo('fabricante');
	$cliente_nestle = new Cliente();
	$cliente_nestle->setNombre('nestle');
	$cliente_nestle->setRut('111-2');
	$cliente_nestle->setTipo('fabricante');
        
	
	$userManager = $this->container->get('fos_user.user_manager');
 	
	$user1 = $userManager->createUser();
	$user1->setUsername('soprole');
	$user1->setEmail('usuario@soprole.cl');
	$user1->setPlainPassword('1234');
	$user1->setCliente($cliente_soprole);
	$user1->setEnabled(true);
	$userManager->updateUser($user1,false);
	
 	$user2 = $userManager->createUser();
	$user2->setUsername('nestle');
	$user2->setEmail('usuario@nestle.cl');
	$user2->setPlainPassword('1234');
	$user2->setCliente($cliente_nestle);
	$user2->setEnabled(true);
	$userManager->updateUser($user2,false);

	$logo1 = new Logo();
	$logo1->setFile('logosoprole.gif');	
	$logo1->setWidth('auto');	
	$logo1->setHeight('auto');	
	$logo1->setActivo(true);
	$logo1->setCliente($cliente_soprole);
	$logo2 = new Logo();
	$logo2->setFile('logonestle.jpg');	
	$logo2->setWidth('auto');	
	$logo2->setHeight('auto');	
	$logo2->setActivo(true);	
	$logo2->setCliente($cliente_nestle);

	//Falta la Variable y VariableCliente

	$manager->persist($cliente_soprole);
	$manager->persist($cliente_nestle);
	$manager->persist($logo1);
	$manager->persist($logo2);
        $manager->flush();

    }
}
