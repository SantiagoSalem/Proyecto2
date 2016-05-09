<?php

namespace IAW\TorneoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Torneo
 *
 * @ORM\Table(name="torneo")
 * @ORM\Entity(repositoryClass="IAW\TorneoBundle\Repository\TorneoRepository")
 */
class Torneo
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
     * @ORM\Column(name="nombreTorneo", type="string", length=100)
     */
    private $nombreTorneo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="datetime", unique=true)
     */
    private $fechaInicio;

    /**
   * @ORM\OneToMany(targetEntity="FechaTorneo", mappedBy="torneo")
   */
   private $fechas;


   public function  __construct()
   {
        $this->fechas = new ArrayCollection();
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
     * Set nombreTorneo
     *
     * @param string $nombreTorneo
     * @return Torneo
     */
    public function setNombreTorneo($nombreTorneo)
    {
        $this->nombreTorneo = $nombreTorneo;

        return $this;
    }

    /**
     * Get nombreTorneo
     *
     * @return string
     */
    public function getNombreTorneo()
    {
        return $this->nombreTorneo;
    }

    /**
     * Add fechas
     *
     * @param \IAW\TorneoBundle\Entity\FechaTorneo $fechas
     * @return Torneo
     */
    public function addFecha(\IAW\TorneoBundle\Entity\FechaTorneo $fechas)
    {
        $this->fechas[] = $fechas;

        return $this;
    }

    /**
     * Remove fechas
     *
     * @param \IAW\TorneoBundle\Entity\FechaTorneo $fechas
     */
    public function removeFecha(\IAW\TorneoBundle\Entity\FechaTorneo $fechas)
    {
        $this->fechas->removeElement($fechas);
    }

    /**
     * Get fechas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFechas()
    {
        return $this->fechas;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Torneo
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }
}
