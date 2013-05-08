<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comuna
 *
 * @ORM\Table(name="COMUNA")
 * @ORM\Entity
 */
class Comuna
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
     * @var \Provincia
     *
     * @ORM\ManyToOne(targetEntity="Provincia", inversedBy="comunas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PROVINCIA_ID", referencedColumnName="ID")
     * })
     */
    private $provincia;
	
	/**
     * @ORM\OneToMany(targetEntity="Sala", mappedBy="comuna")
     */
	 
	protected $salas;
	
	public function __construct()
    {
        $this->salas = new ArrayCollection();
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
     * @return Comuna
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
     * @return Comuna
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
     * Set provincia
     *
     * @param \Cadem\ReporteBundle\Entity\Provincia $provincia
     * @return Comuna
     */
    public function setProvincia(\Cadem\ReporteBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;
    
        return $this;
    }

    /**
     * Get provincia
     *
     * @return \Cadem\ReporteBundle\Entity\Provincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Add salas
     *
     * @param \Cadem\ReporteBundle\Entity\Sala $salas
     * @return Comuna
     */
    public function addSala(\Cadem\ReporteBundle\Entity\Sala $salas)
    {
        $this->salas[] = $salas;
    
        return $this;
    }

    /**
     * Remove salas
     *
     * @param \Cadem\ReporteBundle\Entity\Sala $salas
     */
    public function removeSala(\Cadem\ReporteBundle\Entity\Sala $salas)
    {
        $this->salas->removeElement($salas);
    }

    /**
     * Get salas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSalas()
    {
        return $this->salas;
    }
}