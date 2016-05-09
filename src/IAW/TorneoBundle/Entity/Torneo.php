<?php

namespace IAW\TorneoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var int
     *
     * @ORM\Column(name="anioTorneo", type="integer", unique=true)
     */
    private $anioTorneo;


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
     * Set anioTorneo
     *
     * @param integer $anioTorneo
     * @return Torneo
     */
    public function setAnioTorneo($anioTorneo)
    {
        $this->anioTorneo = $anioTorneo;
    
        return $this;
    }

    /**
     * Get anioTorneo
     *
     * @return integer 
     */
    public function getAnioTorneo()
    {
        return $this->anioTorneo;
    }
}
