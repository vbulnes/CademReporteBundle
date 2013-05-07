<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="ITEM")
 * @ORM\Entity
 */
class Item
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
     * @ORM\Column(name="CODIGO", type="string", length=128, nullable=false)
     */
    private $codigo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Fabricante
     *
     * @ORM\ManyToOne(targetEntity="Fabricante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FABRICANTE_ID", referencedColumnName="ID")
     * })
     */
    private $fabricante;

    /**
     * @var \Marca
     *
     * @ORM\ManyToOne(targetEntity="Marca")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MARCA_ID", referencedColumnName="ID")
     * })
     */
    private $marca;

    /**
     * @var \Tipocodigo
     *
     * @ORM\ManyToOne(targetEntity="Tipocodigo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TIPOCODIGO_ID", referencedColumnName="ID")
     * })
     */
    private $tipocodigo;



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
     * @return Item
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
     * Set codigo
     *
     * @param string $codigo
     * @return Item
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Item
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
     * Set fabricante
     *
     * @param \Cadem\ReporteBundle\Entity\Fabricante $fabricante
     * @return Item
     */
    public function setFabricante(\Cadem\ReporteBundle\Entity\Fabricante $fabricante = null)
    {
        $this->fabricante = $fabricante;
    
        return $this;
    }

    /**
     * Get fabricante
     *
     * @return \Cadem\ReporteBundle\Entity\Fabricante 
     */
    public function getFabricante()
    {
        return $this->fabricante;
    }

    /**
     * Set marca
     *
     * @param \Cadem\ReporteBundle\Entity\Marca $marca
     * @return Item
     */
    public function setMarca(\Cadem\ReporteBundle\Entity\Marca $marca = null)
    {
        $this->marca = $marca;
    
        return $this;
    }

    /**
     * Get marca
     *
     * @return \Cadem\ReporteBundle\Entity\Marca 
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set tipocodigo
     *
     * @param \Cadem\ReporteBundle\Entity\Tipocodigo $tipocodigo
     * @return Item
     */
    public function setTipocodigo(\Cadem\ReporteBundle\Entity\Tipocodigo $tipocodigo = null)
    {
        $this->tipocodigo = $tipocodigo;
    
        return $this;
    }

    /**
     * Get tipocodigo
     *
     * @return \Cadem\ReporteBundle\Entity\Tipocodigo 
     */
    public function getTipocodigo()
    {
        return $this->tipocodigo;
    }
}