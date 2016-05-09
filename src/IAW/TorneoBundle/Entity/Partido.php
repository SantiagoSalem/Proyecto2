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
     * @var int
     *
     * @ORM\Column(name="golesEquipo1", type="integer", nullable=true, unique=true)
     */
    private $golesEquipo1;

    /**
     * @var int
     *
     * @ORM\Column(name="golesEquipo2", type="integer", nullable=true, unique=true)
     */
    private $golesEquipo2;

    /**
     * @var bool
     *
     * @ORM\Column(name="resultadoCargado", type="boolean", nullable=true, unique=true)
     */
    private $resultadoCargado;

    /**
    * @ORM\ManyToOne(targetEntity="FechaTorneo", inversedBy="partidos")
    * @ORM\JoinColumn(name="fecha_id", referencedColumnName="id")
    */
   private $fecha;

   /**
      * @ORM\ManyToMany(targetEntity="\IAW\ParticipanteBundle\Entity\Participante", mappedBy="partidos")
      */
   private $equipos;

   public function __construct()
   {
        $this->equipos = new ArrayCollection();
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
     * Set golesEquipo1
     *
     * @param integer $golesEquipo1
     * @return Partido
     */
    public function setGolesEquipo1($golesEquipo1)
    {
        $this->golesEquipo1 = $golesEquipo1;

        return $this;
    }

    /**
     * Get golesEquipo1
     *
     * @return integer
     */
    public function getGolesEquipo1()
    {
        return $this->golesEquipo1;
    }

    /**
     * Set golesEquipo2
     *
     * @param integer $golesEquipo2
     * @return Partido
     */
    public function setGolesEquipo2($golesEquipo2)
    {
        $this->golesEquipo2 = $golesEquipo2;

        return $this;
    }

    /**
     * Get golesEquipo2
     *
     * @return integer
     */
    public function getGolesEquipo2()
    {
        return $this->golesEquipo2;
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



    /**
     * Get equipos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipos()
    {
        return $this->equipos;
    }

    /**
     * Add equipos
     *
     * @param \IAW\TorneoBundle\Entity\Participante $equipos
     * @return Partido
     */
    public function addEquipo(\IAW\TorneoBundle\Entity\Participante $equipos)
    {
        $this->equipos[] = $equipos;

        return $this;
    }

    /**
     * Remove equipos
     *
     * @param \IAW\TorneoBundle\Entity\Participante $equipos
     */
    public function removeEquipo(\IAW\TorneoBundle\Entity\Participante $equipos)
    {
        $this->equipos->removeElement($equipos);
    }
}
