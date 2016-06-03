<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder 
            ->add('title', TextType::class, array(
                'label' => 'Titre',
                'required' => true,
            ))
            ->add('category', EntityType::class, array(
                'placeholder' => 'Sélectionner une catégorie',
                'class' => 'AppBundle:Category',
                'label' => 'Catégorie',
                'required' => true,
            ))
            ->add('evenementDate', DateTimeType::class, array(
                'label' => 'Date et Heure de l\'evenement',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'placeholder' => 'Choississez la date et l\'heure de l\'événement',
            ))
            ->add('content', CKEditorType::class, array(
                'label' => 'Corps',
                'config_name' => 'my_custom_config',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Advert'
        ));
    }
}
