<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Variable
 *
 * @ORM\Table(name="VARIABLE")
 * @ORM\Entity
 */
class Variable
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
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=256, nullable=true)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

	/**
     * @ORM\OneToMany(targetEntity="Estudiovariable", mappedBy="variable")
     */
	 
	protected $estudiovariables;

	 
	public function __construct()
    {
        $this->estudiovariables = new ArrayCollection();
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
     * Add estudiovariables
     *
     * @param \Cadem\ReporteBundle\Entity\Estudiovariable $estudiovariables
     * @return Variable
     */
    public function addEstudiovariable(\Cadem\ReporteBundle\Entity\Estudiovariable $estudiovariables)
    {
        $this->estudiovariables[] = $estudiovariables;
    
        return $this;
    }

    /**
     * Remove estudiovariables
     *
     * @param \Cadem\ReporteBundle\Entity\Estudiovariable $estudiovariables
     */
    public function removeEstudiovariable(\Cadem\ReporteBundle\Entity\Estudiovariable $estudiovariables)
    {
        $this->estudiovariables->removeElement($estudiovariables);
    }

    /**
     * Get estudiovariables
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiovariables()
    {
        return $this->estudiovariables;
    }
}