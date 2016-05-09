<?php

namespace IAW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="IAW\UserBundle\Repository\UserRepository")
 * @UniqueEntity("username")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", columnDefinition="ENUM('ROLE_ADMIN', 'ROLE_EDITOR')", length=50)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"ROLE_ADMIN", "ROLE_EDITOR"})
     */
    private $role;



    public function __construct()
    {
      parent::__construct();
      //your own logic
    }

    /**
    * Set role
    *
    * @param string $role
    * @return User
    */
   public function setRole($role)
   {
       $this->role = $role;
       return $this;
   }
   /**
    * Get role
    *
    * @return string
    */
   public function getRole()
   {
       return $this->role;
   }

}
