<?php

namespace Cadem\ReporteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itemcliente
 *
 * @ORM\Table(name="ITEMCLIENTE")
 * @ORM\Entity
 */
class Itemcliente
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
     * @ORM\Column(name="CODIGOITEM", type="string", length=64, nullable=true)
     */
    private $codigoitem;

    /**
     * @var string
     *
     * @ORM\Column(name="NIVELESITEM", type="string", length=512, nullable=true)
     */
    private $nivelesitem;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ACTIVO", type="boolean", nullable=false)
     */
    private $activo;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $cliente;

    /**
     * @var \Item
     *
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ITEM_ID", referencedColumnName="ID")
     * })
     */
    private $item;

    /**
     * @var \Itemcliente
     *
     * @ORM\ManyToOne(targetEntity="Itemcliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ITEMCLIENTE_ID", referencedColumnName="ID")
     * })
     */
    private $itemcliente;

    /**
     * @var \Nivelitem
     *
     * @ORM\ManyToOne(targetEntity="Nivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIVELITEM_ID4", referencedColumnName="ID")
     * })
     */
    private $nivelitem4;

    /**
     * @var \Nivelitem
     *
     * @ORM\ManyToOne(targetEntity="Nivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIVELITEM_ID3", referencedColumnName="ID")
     * })
     */
    private $nivelitem3;

    /**
     * @var \Nivelitem
     *
     * @ORM\ManyToOne(targetEntity="Nivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIVELITEM_ID", referencedColumnName="ID")
     * })
     */
    private $nivelitem;

    /**
     * @var \Nivelitem
     *
     * @ORM\ManyToOne(targetEntity="Nivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIVELITEM_ID2", referencedColumnName="ID")
     * })
     */
    private $nivelitem2;

    /**
     * @var \Nivelitem
     *
     * @ORM\ManyToOne(targetEntity="Nivelitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIVELITEM_ID5", referencedColumnName="ID")
     * })
     */
    private $nivelitem5;

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
     * Set codigoitem
     *
     * @param string $codigoitem
     * @return Itemcliente
     */
    public function setCodigoitem($codigoitem)
    {
        $this->codigoitem = $codigoitem;
    
        return $this;
    }

    /**
     * Get codigoitem
     *
     * @return string 
     */
    public function getCodigoitem()
    {
        return $this->codigoitem;
    }

    /**
     * Set nivelesitem
     *
     * @param string $nivelesitem
     * @return Itemcliente
     */
    public function setNivelesitem($nivelesitem)
    {
        $this->nivelesitem = $nivelesitem;
    
        return $this;
    }

    /**
     * Get nivelesitem
     *
     * @return string 
     */
    public function getNivelesitem()
    {
        return $this->nivelesitem;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Itemcliente
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
     * @return Itemcliente
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
     * Set item
     *
     * @param \Cadem\ReporteBundle\Entity\Item $item
     * @return Itemcliente
     */
    public function setItem(\Cadem\ReporteBundle\Entity\Item $item = null)
    {
        $this->item = $item;
    
        return $this;
    }

    /**
     * Get item
     *
     * @return \Cadem\ReporteBundle\Entity\Item 
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set itemcliente
     *
     * @param \Cadem\ReporteBundle\Entity\Itemcliente $itemcliente
     * @return Itemcliente
     */
    public function setItemcliente(\Cadem\ReporteBundle\Entity\Itemcliente $itemcliente = null)
    {
        $this->itemcliente = $itemcliente;
    
        return $this;
    }

    /**
     * Get itemcliente
     *
     * @return \Cadem\ReporteBundle\Entity\Itemcliente 
     */
    public function getItemcliente()
    {
        return $this->itemcliente;
    }

    /**
     * Set nivelitem4
     *
     * @param \Cadem\ReporteBundle\Entity\Nivelitem $nivelitem4
     * @return Itemcliente
     */
    public function setNivelitem4(\Cadem\ReporteBundle\Entity\Nivelitem $nivelitem4 = null)
    {
        $this->nivelitem4 = $nivelitem4;
    
        return $this;
    }

    /**
     * Get nivelitem4
     *
     * @return \Cadem\ReporteBundle\Entity\Nivelitem 
     */
    public function getNivelitem4()
    {
        return $this->nivelitem4;
    }

    /**
     * Set nivelitem3
     *
     * @param \Cadem\ReporteBundle\Entity\Nivelitem $nivelitem3
     * @return Itemcliente
     */
    public function setNivelitem3(\Cadem\ReporteBundle\Entity\Nivelitem $nivelitem3 = null)
    {
        $this->nivelitem3 = $nivelitem3;
    
        return $this;
    }

    /**
     * Get nivelitem3
     *
     * @return \Cadem\ReporteBundle\Entity\Nivelitem 
     */
    public function getNivelitem3()
    {
        return $this->nivelitem3;
    }

    /**
     * Set nivelitem
     *
     * @param \Cadem\ReporteBundle\Entity\Nivelitem $nivelitem
     * @return Itemcliente
     */
    public function setNivelitem(\Cadem\ReporteBundle\Entity\Nivelitem $nivelitem = null)
    {
        $this->nivelitem = $nivelitem;
    
        return $this;
    }

    /**
     * Get nivelitem
     *
     * @return \Cadem\ReporteBundle\Entity\Nivelitem 
     */
    public function getNivelitem()
    {
        return $this->nivelitem;
    }

    /**
     * Set nivelitem2
     *
     * @param \Cadem\ReporteBundle\Entity\Nivelitem $nivelitem2
     * @return Itemcliente
     */
    public function setNivelitem2(\Cadem\ReporteBundle\Entity\Nivelitem $nivelitem2 = null)
    {
        $this->nivelitem2 = $nivelitem2;
    
        return $this;
    }

    /**
     * Get nivelitem2
     *
     * @return \Cadem\ReporteBundle\Entity\Nivelitem 
     */
    public function getNivelitem2()
    {
        return $this->nivelitem2;
    }

    /**
     * Set nivelitem5
     *
     * @param \Cadem\ReporteBundle\Entity\Nivelitem $nivelitem5
     * @return Itemcliente
     */
    public function setNivelitem5(\Cadem\ReporteBundle\Entity\Nivelitem $nivelitem5 = null)
    {
        $this->nivelitem5 = $nivelitem5;
    
        return $this;
    }

    /**
     * Get nivelitem5
     *
     * @return \Cadem\ReporteBundle\Entity\Nivelitem 
     */
    public function getNivelitem5()
    {
        return $this->nivelitem5;
    }

    /**
     * Set tipocodigo
     *
     * @param \Cadem\ReporteBundle\Entity\Tipocodigo $tipocodigo
     * @return Itemcliente
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