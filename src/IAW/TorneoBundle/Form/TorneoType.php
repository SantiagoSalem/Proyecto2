<?php

namespace IAW\TorneoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class TorneoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreTorneo')
            ->add('anioTorneo')
            ->add('fechaInicio', DateType::class, array(
                                                  'input'  => 'datetime',
                                                  'widget' => 'choice',
                                                  )
                  )
            ->add('save','submit',array('label' => 'Guardar Formulario'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IAW\TorneoBundle\Entity\Torneo'
        ));
    }
}
