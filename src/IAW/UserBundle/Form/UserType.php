<?php

namespace IAW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'password')
            ->add('role', 'choice', array('choices' => array('ROLE_ADMIN' => 'Administrador', 'ROLE_EDITOR' => 'Editor'), 'placeholder' => 'Seleccione un rol'))
            ->add('enabled', 'checkbox')
            ->add('save', 'submit', array('label' => 'Guardar usuario'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IAW\UserBundle\Entity\User'
        ));
    }
    /**
     * @return  simplexml_load_string
     */
    public function getName(){
      return 'user';
    }
}
