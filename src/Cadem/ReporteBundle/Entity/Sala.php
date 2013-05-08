<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sala
 *
 * @ORM\Table(name="SALA")
 * @ORM\Entity
 */
class Sala
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
     * @ORM\Column(name="FOLIOCADEM", type="string", length=32, nullable=false)
     */
    private $foliocadem;

    /**
     * @var string
     *
     * @ORM\Column(name="CALLE", type="string", length=128, nullable=false)
     */
    private $calle;

    /**
     * @var string
     *
     * @ORM\Column(name="NUMEROCALLE", type="string", length=32, nullable=false)
     */
    private $numerocalle;

    /**
     * @var float
     *
     * @ORM\Column(name="LATITUD", type="float", nullable=true)
     */
    private $latitud;

    /**
     * @var float
     *
     * @ORM\Column(name="LONGITUD", type="float", nullable=true)
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="RESPUESTA_GMAP", type="string", length=64, nullable=true)
     */
    private $respuestaGmap;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_GMAP", type="string", length=64, nullable=true)
     */
    private $tipoGmap;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Comuna
     *
     * @ORM\ManyToOne(targetEntity="Comuna", inversedBy="salas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="COMUNA_ID", referencedColumnName="ID")
     * })
     */
    private $comuna;

    /**
     * @var \Formato
     *
     * @ORM\ManyToOne(targetEntity="Formato", inversedBy="salas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FORMATO_ID", referencedColumnName="ID")
     * })
     */
    private $formato;

    /**
     * @var \Cadena
     *
     * @ORM\ManyToOne(targetEntity="Cadena", inversedBy="salas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CADENA_ID", referencedColumnName="ID")
     * })
     */
    private $cadena;

    /**
     * @var \Canal
     *
     * @ORM\ManyToOne(targetEntity="Canal", inversedBy="salas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CANAL_ID", referencedColumnName="ID")
     * })
     */
    private $canal;
	
	/**
     * @ORM\OneToMany(targetEntity="Salacliente", mappedBy="sala")
     */
	 
	protected $salaclientes;


	public function __construct()
    {
        $this->salaclientes = new ArrayCollection();
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
     * Set foliocadem
     *
     * @param string $foliocadem
     * @return Sala
     */
    public function setFoliocadem($foliocadem)
    {
        $this->foliocadem = $foliocadem;
    
        return $this;
    }

    /**
     * Get foliocadem
     *
     * @return string 
     */
    public function getFoliocadem()
    {
        return $this->foliocadem;
    }

    /**
     * Set calle
     *
     * @param string $calle
     * @return Sala
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;
    
        return $this;
    }

    /**
     * Get calle
     *
     * @return string 
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set numerocalle
     *
     * @param string $numerocalle
     * @return Sala
     */
    public function setNumerocalle($numerocalle)
    {
        $this->numerocalle = $numerocalle;
    
        return $this;
    }

    /**
     * Get numerocalle
     *
     * @return string 
     */
    public function getNumerocalle()
    {
        return $this->numerocalle;
    }

    /**
     * Set latitud
     *
     * @param float $latitud
     * @return Sala
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    
        return $this;
    }

    /**
     * Get latitud
     *
     * @return float 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param float $longitud
     * @return Sala
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    
        return $this;
    }

    /**
     * Get longitud
     *
     * @return float 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set respuestaGmap
     *
     * @param string $respuestaGmap
     * @return Sala
     */
    public function setRespuestaGmap($respuestaGmap)
    {
        $this->respuestaGmap = $respuestaGmap;
    
        return $this;
    }

    /**
     * Get respuestaGmap
     *
     * @return string 
     */
    public function getRespuestaGmap()
    {
        return $this->respuestaGmap;
    }

    /**
     * Set tipoGmap
     *
     * @param string $tipoGmap
     * @return Sala
     */
    public function setTipoGmap($tipoGmap)
    {
        $this->tipoGmap = $tipoGmap;
    
        return $this;
    }

    /**
     * Get tipoGmap
     *
     * @return string 
     */
    public function getTipoGmap()
    {
        return $this->tipoGmap;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Sala
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
     * Set comuna
     *
     * @param \Cadem\ReporteBundle\Entity\Comuna $comuna
     * @return Sala
     */
    public function setComuna(\Cadem\ReporteBundle\Entity\Comuna $comuna = null)
    {
        $this->comuna = $comuna;
    
        return $this;
    }

    /**
     * Get comuna
     *
     * @return \Cadem\ReporteBundle\Entity\Comuna 
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * Set formato
     *
     * @param \Cadem\ReporteBundle\Entity\Formato $formato
     * @return Sala
     */
    public function setFormato(\Cadem\ReporteBundle\Entity\Formato $formato = null)
    {
        $this->formato = $formato;
    
        return $this;
    }

    /**
     * Get formato
     *
     * @return \Cadem\ReporteBundle\Entity\Formato 
     */
    public function getFormato()
    {
        return $this->formato;
    }

    /**
     * Set cadena
     *
     * @param \Cadem\ReporteBundle\Entity\Cadena $cadena
     * @return Sala
     */
    public function setCadena(\Cadem\ReporteBundle\Entity\Cadena $cadena = null)
    {
        $this->cadena = $cadena;
    
        return $this;
    }

    /**
     * Get cadena
     *
     * @return \Cadem\ReporteBundle\Entity\Cadena 
     */
    public function getCadena()
    {
        return $this->cadena;
    }

    /**
     * Set canal
     *
     * @param \Cadem\ReporteBundle\Entity\Canal $canal
     * @return Sala
     */
    public function setCanal(\Cadem\ReporteBundle\Entity\Canal $canal = null)
    {
        $this->canal = $canal;
    
        return $this;
    }

    /**
     * Get canal
     *
     * @return \Cadem\ReporteBundle\Entity\Canal 
     */
    public function getCanal()
    {
        return $this->canal;
    }

    /**
     * Add salaclientes
     *
     * @param \Cadem\ReporteBundle\Entity\Salacliente $salaclientes
     * @return Sala
     */
    public function addSalacliente(\Cadem\ReporteBundle\Entity\Salacliente $salaclientes)
    {
        $this->salaclientes[] = $salaclientes;
    
        return $this;
    }

    /**
     * Remove salaclientes
     *
     * @param \Cadem\ReporteBundle\Entity\Salacliente $salaclientes
     */
    public function removeSalacliente(\Cadem\ReporteBundle\Entity\Salacliente $salaclientes)
    {
        $this->salaclientes->removeElement($salaclientes);
    }

    /**
     * Get salaclientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSalaclientes()
    {
        return $this->salaclientes;
    }
}