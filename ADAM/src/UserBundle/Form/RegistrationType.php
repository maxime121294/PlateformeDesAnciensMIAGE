<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RegistrationType extends AbstractType
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
            ->add('graduate_year', IntegerType::class, array(
                "label" => "Année d'obtention du diplôme",
                "required" => true,
            ))
            ->add('birthday', DateType::class, array(
                "label" => "Date de naissance",
                "required" => true,
            ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}