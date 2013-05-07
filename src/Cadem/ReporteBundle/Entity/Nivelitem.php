<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nivelitem
 *
 * @ORM\Table(name="NIVELITEM")
 * @ORM\Entity
 */
class Nivelitem
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
     * @var \Clasnivelitem
     *
     * @ORM\ManyToOne(targetEntity="Clasnivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CLASNIVELITEM_ID", referencedColumnName="ID")
     * })
     */
    private $clasnivelitem;



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
     * @return Nivelitem
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
     * @return Nivelitem
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
     * Set clasnivelitem
     *
     * @param \Cadem\ReporteBundle\Entity\Clasnivelitem $clasnivelitem
     * @return Nivelitem
     */
    public function setClasnivelitem(\Cadem\ReporteBundle\Entity\Clasnivelitem $clasnivelitem = null)
    {
        $this->clasnivelitem = $clasnivelitem;
    
        return $this;
    }

    /**
     * Get clasnivelitem
     *
     * @return \Cadem\ReporteBundle\Entity\Clasnivelitem 
     */
    public function getClasnivelitem()
    {
        return $this->clasnivelitem;
    }
}