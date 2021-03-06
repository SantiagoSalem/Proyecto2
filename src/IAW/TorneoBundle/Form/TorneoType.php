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
            ->add('fechaInicio', 'date', array('data' => new \DateTime(),'years' => range(date('Y'), date('Y') + 5)))
            ->add('save', 'submit', array('label' => 'Generar fixture'))
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
