<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cliente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cliente
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=64)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="rut", type="string", length=13)
     */
    private $rut;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=64)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="string", length=255, nullable=true)
     */
    private $comentario;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set rut
     *
     * @param string $rut
     * @return Cliente
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
     * Set tipo
     *
     * @param string $tipo
     * @return Cliente
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     * @return Cliente
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    
        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }
	
	
	/**
     * @ORM\OneToMany(targetEntity="Logo", mappedBy="cliente")
     */
    protected $logos;
	
	
	/**
     * @ORM\OneToMany(targetEntity="VariableCliente", mappedBy="cliente")
     */
    protected $variables_clientes;

    public function __construct()
    {
        $this->logos = new ArrayCollection();
        $this->variables_clientes = new ArrayCollection();
    }

    /**
     * Add logos
     *
     * @param \Cadem\ReporteBundle\Entity\Logo $logos
     * @return Cliente
     */
    public function addLogo(\Cadem\ReporteBundle\Entity\Logo $logos)
    {
        $this->logos[] = $logos;
    
        return $this;
    }

    /**
     * Remove logos
     *
     * @param \Cadem\ReporteBundle\Entity\Logo $logos
     */
    public function removeLogo(\Cadem\ReporteBundle\Entity\Logo $logos)
    {
        $this->logos->removeElement($logos);
    }

    /**
     * Get logos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogos()
    {
        return $this->logos;
    }
	
	/**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="cliente")
     */
    protected $usuarios;

    /**
     * Add usuarios
     *
     * @param \Cadem\ReporteBundle\Entity\Usuario $usuarios
     * @return Cliente
     */
    public function addUsuario(\Cadem\ReporteBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Cadem\ReporteBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\Cadem\ReporteBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add variables_clientes
     *
     * @param \Cadem\ReporteBundle\Entity\VariableCliente $variablesClientes
     * @return Cliente
     */
    public function addVariablesCliente(\Cadem\ReporteBundle\Entity\VariableCliente $variablesClientes)
    {
        $this->variables_clientes[] = $variablesClientes;
    
        return $this;
    }

    /**
     * Remove variables_clientes
     *
     * @param \Cadem\ReporteBundle\Entity\VariableCliente $variablesClientes
     */
    public function removeVariablesCliente(\Cadem\ReporteBundle\Entity\VariableCliente $variablesClientes)
    {
        $this->variables_clientes->removeElement($variablesClientes);
    }

    /**
     * Get variables_clientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVariablesClientes()
    {
        return $this->variables_clientes;
    }
}