<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Provincia
 *
 * @ORM\Table(name="PROVINCIA")
 * @ORM\Entity
 */
class Provincia
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
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="provincias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="REGION_ID", referencedColumnName="ID")
     * })
     */
    private $region;

	/**
     * @ORM\OneToMany(targetEntity="Comuna", mappedBy="provincia")
     */
	 
	protected $comunas;
	
	public function __construct()
    {
        $this->comunas = new ArrayCollection();
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
     * @return Provincia
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
     * @return Provincia
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
     * Set region
     *
     * @param \Cadem\ReporteBundle\Entity\Region $region
     * @return Provincia
     */
    public function setRegion(\Cadem\ReporteBundle\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \Cadem\ReporteBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Add comunas
     *
     * @param \Cadem\ReporteBundle\Entity\Comuna $comunas
     * @return Provincia
     */
    public function addComuna(\Cadem\ReporteBundle\Entity\Comuna $comunas)
    {
        $this->comunas[] = $comunas;
    
        return $this;
    }

    /**
     * Remove comunas
     *
     * @param \Cadem\ReporteBundle\Entity\Comuna $comunas
     */
    public function removeComuna(\Cadem\ReporteBundle\Entity\Comuna $comunas)
    {
        $this->comunas->removeElement($comunas);
    }

    /**
     * Get comunas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComunas()
    {
        return $this->comunas;
    }
}