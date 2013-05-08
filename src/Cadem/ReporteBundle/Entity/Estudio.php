<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Estudio
 *
 * @ORM\Table(name="ESTUDIO")
 * @ORM\Entity
 */
class Estudio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=64, nullable=false)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAINICIO", type="datetime", nullable=true)
     */
    private $fechainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHAFIN", type="datetime", nullable=true)
     */
    private $fechafin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="estudios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $cliente;

    /**
     * @var \Empleado
     *
     * @ORM\ManyToOne(targetEntity="Empleado", inversedBy="estudios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EMPLEADO_ID", referencedColumnName="ID")
     * })
     */
    private $empleado;

	/**
     * @ORM\OneToMany(targetEntity="Estudiovariable", mappedBy="estudio")
     */
	 
	protected $estudiovariables;
	
	/**
     * @ORM\OneToMany(targetEntity="Medicion", mappedBy="estudio")
     */
	 
	protected $mediciones;

	
	public function __construct()
    {
        $this->estudiovariables = new ArrayCollection();
        $this->mediciones = new ArrayCollection();
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
     * @return Estudio
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
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Estudio
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;
    
        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime 
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return Estudio
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;
    
        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime 
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Estudio
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
     * @return Estudio
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
     * Set empleado
     *
     * @param \Cadem\ReporteBundle\Entity\Empleado $empleado
     * @return Estudio
     */
    public function setEmpleado(\Cadem\ReporteBundle\Entity\Empleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \Cadem\ReporteBundle\Entity\Empleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Estudio
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Add estudiovariables
     *
     * @param \Cadem\ReporteBundle\Entity\Estudiovariable $estudiovariables
     * @return Estudio
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

    /**
     * Add mediciones
     *
     * @param \Cadem\ReporteBundle\Entity\Medicion $mediciones
     * @return Estudio
     */
    public function addMedicione(\Cadem\ReporteBundle\Entity\Medicion $mediciones)
    {
        $this->mediciones[] = $mediciones;
    
        return $this;
    }

    /**
     * Remove mediciones
     *
     * @param \Cadem\ReporteBundle\Entity\Medicion $mediciones
     */
    public function removeMedicione(\Cadem\ReporteBundle\Entity\Medicion $mediciones)
    {
        $this->mediciones->removeElement($mediciones);
    }

    /**
     * Get mediciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMediciones()
    {
        return $this->mediciones;
    }
}