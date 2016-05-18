<?php

namespace IAW\ParticipanteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class ParticipanteType extends AbstractType
{

    private $intention;

    public function __construct($intention){
       $this->intention = $intention;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      if($this->intention == "creacion"){
        $builder
            ->add('name')
            ->add('imagePath',FileType::class)
            ->add('descripcion', TextareaType::class)
            ->add('save', 'submit', array('label' => 'Guardar participante'))
        ;
      }
      else{
        $builder
            ->add('name')
            ->add('descripcion', TextareaType::class)
            ->add('save', 'submit', array('label' => 'Guardar participante'))
        ;
      }
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
