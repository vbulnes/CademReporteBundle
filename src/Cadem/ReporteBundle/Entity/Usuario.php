<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * Usuario
 *
 * @ORM\Table(name="USUARIO")
 * @ORM\Entity
 */
class Usuario extends BaseUser
{
    /**
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="RUT", type="string", length=64, nullable=true)
     */
    private $rut;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $cliente;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rut
     *
     * @param string $rut
     * @return Usuario
     */
    public function setRut($rut)
    {
        $this->rut = $rut;
    
        return $this;
    }

    /**
     * Get rut
     *
     * @return string 
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set cliente
     *
     * @param \Cadem\ReporteBundle\Entity\Cliente $cliente
     * @return Usuario
     */
    public function setCliente(\Cadem\ReporteBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Cadem\ReporteBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }
	
	 public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}