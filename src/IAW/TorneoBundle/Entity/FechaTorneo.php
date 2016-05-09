<?php

namespace IAW\TorneoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FechaTorneo
 *
 * @ORM\Table(name="fecha_torneo")
 * @ORM\Entity(repositoryClass="IAW\TorneoBundle\Repository\FechaTorneoRepository")
 */
class FechaTorneo
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", unique=true)
     */
    private $date;


    /**
     * @ORM\ManyToOne(targetEntity="Torneo", inversedBy="fechas")
     * @ORM\JoinColumn(name="torneo_id", referencedColumnName="id")
     */
    private $torneo;

    /**
    * @ORM\OneToMany(targetEntity="Partido", mappedBy="fecha")
    */
    private $partidos;

    public function __construct(){
        $this->partidos = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return FechaTorneo
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set torneo
     *
     * @param \IAW\TorneoBundle\Entity\FechaTorneo $torneo
     * @return FechaTorneo
     */
    public function setTorneo(\IAW\TorneoBundle\Entity\FechaTorneo $torneo = null)
    {
        $this->torneo = $torneo;

        return $this;
    }

    /**
     * Get torneo
     *
     * @return \IAW\TorneoBundle\Entity\FechaTorneo
     */
    public function getTorneo()
    {
        return $this->torneo;
    }

    /**
     * Add partidos
     *
     * @param \IAW\TorneoBundle\Entity\Partido $partidos
     * @return FechaTorneo
     */
    public function addPartido(\IAW\TorneoBundle\Entity\Partido $partidos)
    {
        $this->partidos[] = $partidos;

        return $this;
    }

    /**
     * Remove partidos
     *
     * @param \IAW\TorneoBundle\Entity\Partido $partidos
     */
    public function removePartido(\IAW\TorneoBundle\Entity\Partido $partidos)
    {
        $this->partidos->removeElement($partidos);
    }

    /**
     * Get partidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartidos()
    {
        return $this->partidos;
    }
}
