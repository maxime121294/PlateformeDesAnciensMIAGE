<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MissionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder 
            ->add('duration', TextType::class, array(
                'label' => 'Durée de la mission (en mois)',
                'required' => true,
            ))
            ->add('content', TextType::class, array(
                'label' => 'Description',
                'required' => true,
            ))
            ->add('businessName', TextType::class, array(
                'label' => 'Nom de l\'entreprise',
                'required' => true,
            ))
            ->add('name', TextType::class, array(
                'label' => 'Nom de l\'entreprise',
                'required' => true,
            ))
            ->add('beginAt', DateTimeType::class, array(
                'label' => 'Début de la mission',
                'widget' => 'single_text',
                'required' => true,
                'format' => 'dd/MM/yyyy HH:mm',
                'placeholder' => 'Choississez la date et l\'heure de l\'événement',
            ))
            ->add('endedAt', DateTimeType::class, array(
                'label' => 'Fin de la mission',
                'widget' => 'single_text',
                'required' => true,
                'format' => 'dd/MM/yyyy HH:mm',
                'placeholder' => 'Choississez la date et l\'heure de l\'événement',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Mission'
        ));
    }
}