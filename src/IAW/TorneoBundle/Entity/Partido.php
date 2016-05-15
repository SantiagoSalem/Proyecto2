<?php

namespace IAW\TorneoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Partido
 *
 * @ORM\Table(name="partido")
 * @ORM\Entity(repositoryClass="IAW\TorneoBundle\Repository\PartidoRepository")
 */
class Partido
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="equipoLocal", type="string", length=100)
     */
    private $equipoLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="equipoVisitante", type="string", length=100)
     */
    private $equipoVisitante;

    /**
     * @var int
     *
     * @ORM\Column(name="golesEquipoLocal", type="integer", nullable=true)
     */
    private $golesEquipoLocal;

    /**
     * @var int
     *
     * @ORM\Column(name="golesEquipoVisitante", type="integer", nullable=true)
     */
    private $golesEquipoVisitante;

    /**
     * @var bool
     *
     * @ORM\Column(name="resultadoCargado", type="boolean", nullable=true)
     */
    private $resultadoCargado;

    /**
    * @ORM\ManyToOne(targetEntity="FechaTorneo", inversedBy="partidos")
    * @ORM\JoinColumn(name="fecha_id", referencedColumnName="id")
    */
   private $fecha;

   /**
    * @var string
    *
    * @ORM\Column(name="editor", type="string", length=100, nullable=true)
    */
   private $editor;

   public function __construct()
   {
        $this->equipos = new ArrayCollection();
   }




   public function getEditor()
   {
       return $this->editor;
   }

   public function setEditor($editor)
   {
       $this->editor = $editor;

       return $this;
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
     * Set equipoLocal
     *
     * @param string $equipoLocal
     * @return Partido
     */
    public function setEquipoLocal($equipoLocal)
    {
        $this->equipoLocal = $equipoLocal;

        return $this;
    }

    /**
     * Get equipoLocal
     *
     * @return string
     */
    public function getEquipoLocal()
    {
        return $this->equipoLocal;
    }

    /**
     * Set equipoVisitante
     *
     * @param string $equipoVisitante
     * @return Partido
     */
    public function setEquipoVisitante($equipoVisitante)
    {
        $this->equipoVisitante = $equipoVisitante;

        return $this;
    }

    /**
     * Get equipoVisitante
     *
     * @return string
     */
    public function getEquipoVisitante()
    {
        return $this->equipoVisitante;
    }


    /**
     * Set golesEquipoLocal
     *
     * @param integer $golesEquipoLocal
     * @return Partido
     */
    public function setGolesEquipoLocal($golesEquipoLocal)
    {
        $this->golesEquipoLocal = $golesEquipoLocal;

        return $this;
    }

    /**
     * Get golesEquipoLocal
     *
     * @return integer
     */
    public function getGolesEquipoLocal()
    {
        return $this->golesEquipoLocal;
    }

    /**
     * Set golesEquipoVisitante
     *
     * @param integer $golesEquipoVisitante
     * @return Partido
     */
    public function setGolesEquipoVisitante($golesEquipoVisitante)
    {
        $this->golesEquipoVisitante = $golesEquipoVisitante;

        return $this;
    }

    /**
     * Get golesEquipoVisitante
     *
     * @return integer
     */
    public function getGolesEquipoVisitante()
    {
        return $this->golesEquipoVisitante;
    }

    /**
     * Set resultadoCargado
     *
     * @param boolean $resultadoCargado
     * @return Partido
     */
    public function setResultadoCargado($resultadoCargado)
    {
        $this->resultadoCargado = $resultadoCargado;

        return $this;
    }

    /**
     * Get resultadoCargado
     *
     * @return boolean
     */
    public function getResultadoCargado()
    {
        return $this->resultadoCargado;
    }

    /**
     * Set category
     *
     * @param \IAW\TorneoBundle\Entity\FechaTorneo $category
     * @return Partido
     */
    public function setCategory(\IAW\TorneoBundle\Entity\FechaTorneo $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \IAW\TorneoBundle\Entity\FechaTorneo
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set fecha
     *
     * @param \IAW\TorneoBundle\Entity\FechaTorneo $fecha
     * @return Partido
     */
    public function setFecha(\IAW\TorneoBundle\Entity\FechaTorneo $fecha = null)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \IAW\TorneoBundle\Entity\FechaTorneo
     */
    public function getFecha()
    {
        return $this->fecha;
    }

}
