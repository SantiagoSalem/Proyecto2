<?php

namespace IAW\ParticipanteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ParticipanteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('imagePath','file') //Cambiar a file y luego realizar las modificaciones para el editar
            ->add('descripcion', TextareaType::class)
            ->add('members')
            ->add('save', 'submit', array('label' => 'Guardar participante'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IAW\ParticipanteBundle\Entity\Participante'
        ));
    }

    /**
     * @return  simplexml_load_string
     */
    public function getName(){
      return 'participante';
    }
}
