<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cadena
 *
 * @ORM\Table(name="CADENA")
 * @ORM\Entity
 */
class Cadena
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
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=64, nullable=false)
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Holding
     *
     * @ORM\ManyToOne(targetEntity="Holding")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="HOLDING_ID", referencedColumnName="ID")
     * })
     */
    private $holding;



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
     * @return Cadena
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
     * Set activo
     *
     * @param boolean $activo
     * @return Cadena
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
     * Set holding
     *
     * @param \Cadem\ReporteBundle\Entity\Holding $holding
     * @return Cadena
     */
    public function setHolding(\Cadem\ReporteBundle\Entity\Holding $holding = null)
    {
        $this->holding = $holding;
    
        return $this;
    }

    /**
     * Get holding
     *
     * @return \Cadem\ReporteBundle\Entity\Holding 
     */
    public function getHolding()
    {
        return $this->holding;
    }
}