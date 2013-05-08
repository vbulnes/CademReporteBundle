<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\ManyToOne(targetEntity="Medicion", inversedBy="salamediciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDICION_ID", referencedColumnName="ID")
     * })
     */
    private $medicion;

    /**
     * @var \Salacliente
     *
     * @ORM\ManyToOne(targetEntity="Salacliente", inversedBy="salamediciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SALACLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $salacliente;


	/**
     * @ORM\OneToMany(targetEntity="Quiebre", mappedBy="salamedicion")
     */
	 
	protected $quiebres;
	
	
	public function __construct()
    {
        $this->quiebres = new ArrayCollection();
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

    /**
     * Add quiebres
     *
     * @param \Cadem\ReporteBundle\Entity\Quiebre $quiebres
     * @return Salamedicion
     */
    public function addQuiebre(\Cadem\ReporteBundle\Entity\Quiebre $quiebres)
    {
        $this->quiebres[] = $quiebres;
    
        return $this;
    }

    /**
     * Remove quiebres
     *
     * @param \Cadem\ReporteBundle\Entity\Quiebre $quiebres
     */
    public function removeQuiebre(\Cadem\ReporteBundle\Entity\Quiebre $quiebres)
    {
        $this->quiebres->removeElement($quiebres);
    }

    /**
     * Get quiebres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuiebres()
    {
        return $this->quiebres;
    }
}