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
     * @var string
     *
     * @ORM\Column(name="equipo", type="string", unique=true)
     */
    private $equipo;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer")
     */
    private $puntos;

    /**
     * @var int
     *
     * @ORM\Column(name="pj", type="integer")
     */
    private $pj;

    /**
     * @var int
     *
     * @ORM\Column(name="pg", type="integer")
     */
    private $pg;

    /**
     * @var int
     *
     * @ORM\Column(name="pe", type="integer")
     */
    private $pe;

    /**
     * @var int
     *
     * @ORM\Column(name="pp", type="integer")
     */
    private $pp;

    /**
     * @var int
     *
     * @ORM\Column(name="gf", type="integer")
     */
    private $gf;

    /**
     * @var int
     *
     * @ORM\Column(name="gc", type="integer")
     */
    private $gc;

    /**
     * @var int
     *
     * @ORM\Column(name="dg", type="integer")
     */
    private $dg;



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
     * Get equipo
     *
     * @return string
     */
    public function getEquipo()
    {
        return $this->equipo;
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

    public function setEquipo($equipo)
    {
        $this->equipo = $equipo;

        return $this;
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

}
