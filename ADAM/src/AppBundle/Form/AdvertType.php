<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\UserBundle\Util\LegacyFormHelper;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('field', CKEditorType::class, array(
            'config' => array(
                'uiColor' => '#ffffff'
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Advert',
            'csrf_token_id' => 'advert',
            // BC for SF < 2.8
            'intention'  => 'advert',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_advert';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}