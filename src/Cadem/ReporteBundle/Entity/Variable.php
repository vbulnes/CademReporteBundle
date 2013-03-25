<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Variable
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Variable
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;
	
	
	/**
     * @ORM\OneToMany(targetEntity="VariableCliente", mappedBy="variable")
     */
    protected $variables_clientes;

    public function __construct()
    {
        $this->variables_clientes = new ArrayCollection();
    }


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
     * @return Variable
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Variable
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Variable
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add variables_clientes
     *
     * @param \Cadem\ReporteBundle\Entity\VariableCliente $variablesClientes
     * @return Variable
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