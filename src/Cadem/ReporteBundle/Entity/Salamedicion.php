<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salamedicion
 *
 * @ORM\Table(name="SALAMEDICION")
 * @ORM\Entity
 */
class Salamedicion
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
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Medicion
     *
     * @ORM\ManyToOne(targetEntity="Medicion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDICION_ID", referencedColumnName="ID")
     * })
     */
    private $medicion;

    /**
     * @var \Salacliente
     *
     * @ORM\ManyToOne(targetEntity="Salacliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SALACLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $salacliente;



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
     * Set activo
     *
     * @param boolean $activo
     * @return Salamedicion
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
     * Set medicion
     *
     * @param \Cadem\ReporteBundle\Entity\Medicion $medicion
     * @return Salamedicion
     */
    public function setMedicion(\Cadem\ReporteBundle\Entity\Medicion $medicion = null)
    {
        $this->medicion = $medicion;
    
        return $this;
    }

    /**
     * Get medicion
     *
     * @return \Cadem\ReporteBundle\Entity\Medicion 
     */
    public function getMedicion()
    {
        return $this->medicion;
    }

    /**
     * Set salacliente
     *
     * @param \Cadem\ReporteBundle\Entity\Salacliente $salacliente
     * @return Salamedicion
     */
    public function setSalacliente(\Cadem\ReporteBundle\Entity\Salacliente $salacliente = null)
    {
        $this->salacliente = $salacliente;
    
        return $this;
    }

    /**
     * Get salacliente
     *
     * @return \Cadem\ReporteBundle\Entity\Salacliente 
     */
    public function getSalacliente()
    {
        return $this->salacliente;
    }
}