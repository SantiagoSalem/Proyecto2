<?php

namespace IAW\ParticipanteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Participante
 *
 * @ORM\Table(name="participantes")
 * @ORM\Entity(repositoryClass="IAW\ParticipanteBundle\Repository\ParticipanteRepository")
 * @UniqueEntity("name")
 */
class Participante
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=10000, unique=false)
     * @Assert\NotBlank()
     */
    private $descripcion;

    /**
     * @var int
     *
     * @ORM\Column(name="members", type="integer")
     */
    private $members;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=255)
     */
    private $imagePath;

    /**
    * @Assert\File(maxSize="1M", mimeTypes={"image/png", "image/jpeg","image/pjpeg"})
    */
    private $image;

    /**
     *  @ORM\OneToOne(targetEntity="Puntaje", inversedBy="equipo")
     *  @ORM\JoinColumn(name="puntaje_id", referencedColumnName="id")
     */
     private $puntaje;

   public function __construct()
   {
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
     * Set name
     *
     * @param string $name
     * @return Participante
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Participante
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set members
     *
     * @param integer $members
     * @return Participante
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return integer
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Participante
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }


    /**
     * Set puntaje
     *
     * @param \IAW\ParticipanteBundle\Entity\Puntaje $puntaje
     * @return Participante
     */
    public function setPuntaje(\IAW\ParticipanteBundle\Entity\Puntaje $puntaje = null)
    {
        $this->puntaje = $puntaje;

        return $this;
    }

    /**
     * Get puntaje
     *
     * @return \IAW\ParticipanteBundle\Entity\Puntaje
     */
    public function getPuntaje()
    {
        return $this->puntaje;
    }

    
}
