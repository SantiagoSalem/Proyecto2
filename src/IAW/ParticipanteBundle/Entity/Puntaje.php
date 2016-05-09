<?php

namespace IAW\ParticipanteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puntaje
 *
 * @ORM\Table(name="puntaje")
 * @ORM\Entity(repositoryClass="IAW\ParticipanteBundle\Repository\PuntajeRepository")
 */
class Puntaje
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
     * @ORM\Column(name="puntos", type="integer", unique=true)
     */
    private $puntos;

    /**
     * @var int
     *
     * @ORM\Column(name="pj", type="integer", unique=true)
     */
    private $pj;

    /**
     * @var int
     *
     * @ORM\Column(name="pg", type="integer", unique=true)
     */
    private $pg;

    /**
     * @var int
     *
     * @ORM\Column(name="pe", type="integer", unique=true)
     */
    private $pe;

    /**
     * @var int
     *
     * @ORM\Column(name="pp", type="integer", unique=true)
     */
    private $pp;

    /**
     * @var int
     *
     * @ORM\Column(name="gf", type="integer", unique=true)
     */
    private $gf;

    /**
     * @var int
     *
     * @ORM\Column(name="gc", type="integer", unique=true)
     */
    private $gc;

    /**
     * @var int
     *
     * @ORM\Column(name="dg", type="integer", unique=true)
     */
    private $dg;

    /**
     * @ORM\OneToOne(targetEntity="Participante", mappedBy="puntaje")
     */
    private $equipo;


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
     * Set puntos
     *
     * @param integer $puntos
     * @return Puntaje
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Set pj
     *
     * @param integer $pj
     * @return Puntaje
     */
    public function setPj($pj)
    {
        $this->pj = $pj;

        return $this;
    }

    /**
     * Get pj
     *
     * @return integer
     */
    public function getPj()
    {
        return $this->pj;
    }

    /**
     * Set pg
     *
     * @param integer $pg
     * @return Puntaje
     */
    public function setPg($pg)
    {
        $this->pg = $pg;

        return $this;
    }

    /**
     * Get pg
     *
     * @return integer
     */
    public function getPg()
    {
        return $this->pg;
    }

    /**
     * Set pe
     *
     * @param integer $pe
     * @return Puntaje
     */
    public function setPe($pe)
    {
        $this->pe = $pe;

        return $this;
    }

    /**
     * Get pe
     *
     * @return integer
     */
    public function getPe()
    {
        return $this->pe;
    }

    /**
     * Set pp
     *
     * @param integer $pp
     * @return Puntaje
     */
    public function setPp($pp)
    {
        $this->pp = $pp;

        return $this;
    }

    /**
     * Get pp
     *
     * @return integer
     */
    public function getPp()
    {
        return $this->pp;
    }

    /**
     * Set gf
     *
     * @param integer $gf
     * @return Puntaje
     */
    public function setGf($gf)
    {
        $this->gf = $gf;

        return $this;
    }

    /**
     * Get gf
     *
     * @return integer
     */
    public function getGf()
    {
        return $this->gf;
    }

    /**
     * Set gc
     *
     * @param integer $gc
     * @return Puntaje
     */
    public function setGc($gc)
    {
        $this->gc = $gc;

        return $this;
    }

    /**
     * Get gc
     *
     * @return integer
     */
    public function getGc()
    {
        return $this->gc;
    }

    /**
     * Set dg
     *
     * @param integer $dg
     * @return Puntaje
     */
    public function setDg($dg)
    {
        $this->dg = $dg;

        return $this;
    }

    /**
     * Get dg
     *
     * @return integer
     */
    public function getDg()
    {
        return $this->dg;
    }

    /**
     * Set equipo
     *
     * @param \IAW\ParticipanteBundle\Entity\Participante $equipo
     * @return Puntaje
     */
    public function setEquipo(\IAW\ParticipanteBundle\Entity\Participante $equipo = null)
    {
        $this->equipo = $equipo;
    
        return $this;
    }

    /**
     * Get equipo
     *
     * @return \IAW\ParticipanteBundle\Entity\Participante 
     */
    public function getEquipo()
    {
        return $this->equipo;
    }
}
