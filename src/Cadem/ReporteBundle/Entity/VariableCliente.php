<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariableCliente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class VariableCliente
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
     * @var integer
     *
     * @ORM\Column(name="id_variable", type="integer")
     */
    private $idVariable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_cliente", type="integer")
     */
    private $idCliente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="variables_clientes")
     * @ORM\JoinColumn(name="id_cliente", referencedColumnName="id")
     */
    protected $cliente;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="variables_clientes")
     * @ORM\JoinColumn(name="id_variable", referencedColumnName="id")
     */
    protected $variable;


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
     * Set idVariable
     *
     * @param integer $idVariable
     * @return VariableCliente
     */
    public function setIdVariable($idVariable)
    {
        $this->idVariable = $idVariable;
    
        return $this;
    }

    /**
     * Get idVariable
     *
     * @return integer 
     */
    public function getIdVariable()
    {
        return $this->idVariable;
    }

    /**
     * Set idCliente
     *
     * @param integer $idCliente
     * @return VariableCliente
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    
        return $this;
    }

    /**
     * Get idCliente
     *
     * @return integer 
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return VariableCliente
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
     * Set cliente
     *
     * @param \Cadem\ReporteBundle\Entity\Cliente $cliente
     * @return VariableCliente
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

    /**
     * Set variable
     *
     * @param \Cadem\ReporteBundle\Entity\Variable $variable
     * @return VariableCliente
     */
    public function setVariable(\Cadem\ReporteBundle\Entity\Variable $variable = null)
    {
        $this->variable = $variable;
    
        return $this;
    }

    /**
     * Get variable
     *
     * @return \Cadem\ReporteBundle\Entity\Variable 
     */
    public function getVariable()
    {
        return $this->variable;
    }
}