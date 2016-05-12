<?php
 
 namespace AppBundle\Controller;

 use Symfony\Component\Form\AbstractType;
 use Symfony\Component\Form\FormBuilder;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\Form\FormBuilderInterface;

 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

 use Ivory\CKEditorBundle\Form\Type\CKEditorType;
 
 class AdvertController extends Controller
 {
    /**
      * @Route("/annonce", name="annonce")
      */
     public function indexAction(Request $request)
     {
	    // On crée le FormBuilder grâce au service form factory
	    $formBuilder = $this->createFormBuilder()
	    	->add('title', TextType::class, array(
                "label" => "Titre",
                "required" => true,
            ))
		    ->add('category', 'choice', array(
			    'choices' => array(
			        'Evenements' => 'Evenements',
			        'Annonce emploi' => 'Annonce emploi'
			    ),
			    'required'    => true,
			    'empty_value' => 'Choisissez votre catégorie',
			    'empty_data'  => null
			))
        	->add('field', CKEditorType::class, array(
	      		'label' => 'Corps',
	            'config_name' => 'my_custom_config',
        	))
        	->getForm();

	      // On passe la méthode createView() du formulaire à la vue
	    // afin qu'elle puisse afficher le formulaire toute seule
	    return $this->render('AppBundle::advert.html.twig', array(
	      'form' => $formBuilder->createView(),
	    ));
     }
 }