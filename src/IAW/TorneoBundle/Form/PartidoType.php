<?php

namespace IAW\TorneoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContext;


class PartidoType extends AbstractType
{

    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
       $this->securityContext = $securityContext;
     }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipoLocal')
            ->add('golesEquipoLocal')
            ->add('equipoVisitante')
            ->add('golesEquipoVisitante')
            ->add('save', 'submit', array('label' => 'Guardar Resultado'))
        ;
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
