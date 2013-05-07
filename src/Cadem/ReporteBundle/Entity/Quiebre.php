<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiebre
 *
 * @ORM\Table(name="QUIEBRE")
 * @ORM\Entity
 */
class Quiebre
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="HAYQUIEBRE", type="boolean", nullable=false)
     */
    private $hayquiebre;

    /**
     * @var integer
     *
     * @ORM\Column(name="CANTIDAD", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAHORACAPTURA", type="datetime", nullable=true)
     */
    private $fechahoracaptura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Itemcliente
     *
     * @ORM\ManyToOne(targetEntity="Itemcliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ITEMCLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $itemcliente;

    /**
     * @var \Salamedicion
     *
     * @ORM\ManyToOne(targetEntity="Salamedicion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SALAMEDICION_ID", referencedColumnName="ID")
     * })
     */
    private $salamedicion;



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
     * Set hayquiebre
     *
     * @param boolean $hayquiebre
     * @return Quiebre
     */
    public function setHayquiebre($hayquiebre)
    {
        $this->hayquiebre = $hayquiebre;
    
        return $this;
    }

    /**
     * Get hayquiebre
     *
     * @return boolean 
     */
    public function getHayquiebre()
    {
        return $this->hayquiebre;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Quiebre
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechahoracaptura
     *
     * @param \DateTime $fechahoracaptura
     * @return Quiebre
     */
    public function setFechahoracaptura($fechahoracaptura)
    {
        $this->fechahoracaptura = $fechahoracaptura;
    
        return $this;
    }

    /**
     * Get fechahoracaptura
     *
     * @return \DateTime 
     */
    public function getFechahoracaptura()
    {
        return $this->fechahoracaptura;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Quiebre
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
     * Set itemcliente
     *
     * @param \Cadem\ReporteBundle\Entity\Itemcliente $itemcliente
     * @return Quiebre
     */
    public function setItemcliente(\Cadem\ReporteBundle\Entity\Itemcliente $itemcliente = null)
    {
        $this->itemcliente = $itemcliente;
    
        return $this;
    }

    /**
     * Get itemcliente
     *
     * @return \Cadem\ReporteBundle\Entity\Itemcliente 
     */
    public function getItemcliente()
    {
        return $this->itemcliente;
    }

    /**
     * Set salamedicion
     *
     * @param \Cadem\ReporteBundle\Entity\Salamedicion $salamedicion
     * @return Quiebre
     */
    public function setSalamedicion(\Cadem\ReporteBundle\Entity\Salamedicion $salamedicion = null)
    {
        $this->salamedicion = $salamedicion;
    
        return $this;
    }

    /**
     * Get salamedicion
     *
     * @return \Cadem\ReporteBundle\Entity\Salamedicion 
     */
    public function getSalamedicion()
    {
        return $this->salamedicion;
    }
}