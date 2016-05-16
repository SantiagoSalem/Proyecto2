<?php

namespace IAW\TorneoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IAW\TorneoBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;




class PartidoType extends AbstractType
{

  private $em;
  private $sac;

  public function __construct(EntityManager $entityManager, AuthorizationChecker $securityContext )
    {
        $this->em = $entityManager;
        $this->sac = $securityContext;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      $dql = "SELECT e.username
              FROM IAWUserBundle:User e
              WHERE e.enabled = 1 AND e.role = 'ROLE_EDITOR'";

      //Editores habilitados
      $editores = $this->em->createQuery($dql)->getResult();

      $arrNombres = array();

      foreach ($editores as $editor) {
          $arrNombres[$editor["username"]] = $editor["username"];
      }

      if($this->sac->isGranted('ROLE_ADMIN') === true){
        $builder
            ->add('equipoLocal')
            ->add('golesEquipoLocal')
            ->add('equipoVisitante')
            ->add('golesEquipoVisitante')
            ->add('editor', 'choice', array('choices' => $arrNombres))
            ->add('save', 'submit', array('label' => 'Guardar Resultado'))
        ;
      }
      else{
        $builder
            ->add('equipoLocal', 'hidden' ,array('label' => false))
            ->add('golesEquipoLocal',IntegerType::class, array('constraints' => new NotBlank()))
            ->add('equipoVisitante', 'hidden' ,array('label' => false))
            ->add('golesEquipoVisitante',IntegerType::class, array('constraints' => new NotBlank()))
            ->add('save', 'submit', array('label' => 'Guardar Resultado'))
        ;
      }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IAW\TorneoBundle\Entity\Partido'
        ));
    }
    /**
     * @return  simplexml_load_string
     */
    public function getName(){
      return 'partido';
    }
}
