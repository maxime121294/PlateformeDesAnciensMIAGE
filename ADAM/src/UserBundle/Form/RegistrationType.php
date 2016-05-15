<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\UserBundle\Util\LegacyFormHelper;

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
            ->add('graduate', ChoiceType::class, array(
                "label" => "Type de diplôme obtenu",
                "required" => false,
                "placeholder" => "Selectionner un type de diplôme",
				"choices_as_values" => true,
                "choices"  => array(
                    "licence" => "Licence",
                    "master" => "Master"
                ),
            ))
            ->add('graduateYear', TextType::class, array(
                "label" => "Année d'obtention du diplôme",
                "required" => false,
            ))
            ->add('birthday', DateType::class, array(
                "label" => "Date de naissance",
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                "required" => false,
            ))
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'csrf_token_id' => 'registration',
            // BC for SF < 2.8
            'intention'  => 'registration',
        ));
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