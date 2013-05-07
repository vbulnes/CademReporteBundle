<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estudiovariable
 *
 * @ORM\Table(name="ESTUDIOVARIABLE")
 * @ORM\Entity
 */
class Estudiovariable
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
     * @ORM\Column(name="NOMBREVARIABLE", type="string", length=64, nullable=false)
     */
    private $nombrevariable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Estudio
     *
     * @ORM\ManyToOne(targetEntity="Estudio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ESTUDIO_ID", referencedColumnName="ID")
     * })
     */
    private $estudio;

    /**
     * @var \Variable
     *
     * @ORM\ManyToOne(targetEntity="Variable")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="VARIABLE_ID", referencedColumnName="ID")
     * })
     */
    private $variable;



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
     * Set nombrevariable
     *
     * @param string $nombrevariable
     * @return Estudiovariable
     */
    public function setNombrevariable($nombrevariable)
    {
        $this->nombrevariable = $nombrevariable;
    
        return $this;
    }

    /**
     * Get nombrevariable
     *
     * @return string 
     */
    public function getNombrevariable()
    {
        return $this->nombrevariable;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Estudiovariable
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
     * Set estudio
     *
     * @param \Cadem\ReporteBundle\Entity\Estudio $estudio
     * @return Estudiovariable
     */
    public function setEstudio(\Cadem\ReporteBundle\Entity\Estudio $estudio = null)
    {
        $this->estudio = $estudio;
    
        return $this;
    }

    /**
     * Get estudio
     *
     * @return \Cadem\ReporteBundle\Entity\Estudio 
     */
    public function getEstudio()
    {
        return $this->estudio;
    }

    /**
     * Set variable
     *
     * @param \Cadem\ReporteBundle\Entity\Variable $variable
     * @return Estudiovariable
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