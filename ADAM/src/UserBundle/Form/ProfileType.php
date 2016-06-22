<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\UserBundle\Util\LegacyFormHelper;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
                "label" => "Nom",
                "required" => true,
            ))
            ->add('firstname', TextType::class, array(
                "label" => "Prénom",
                "required" => true,
            ))
            ->add('graduate', EntityType::class, array(
                "label" => "Type de diplôme obtenu",
                "class" => "AppBundle:Degree",
                "required" => false,
                "placeholder" => "Sélectionner un type de diplôme",
                ))
            ->add('graduateYear', TextType::class, array(
                "label" => "Année d'obtention du diplôme",
                'attr' => array("placeholder" => "AAAA"),
                "required" => false,
            ))
            ->add('birthday', DateType::class, array(
                "label" => "Date de naissance",
                'widget' => 'single_text',
                'attr' => array("placeholder" => "JJ/MM/AAAA"),
                'format' => 'dd/MM/yyyy',
                "required" => false,
            ))
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}