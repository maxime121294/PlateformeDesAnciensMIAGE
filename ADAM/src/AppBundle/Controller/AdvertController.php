<?php
 
 namespace AppBundle\Controller;
 
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
 use Symfony\Component\HttpFoundation\Request;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 use Symfony\Component\Form\FormBuilderInterface;


 
 class AdvertController extends Controller
 {
    /**
      * @Route("/annonce", name="annonce")
      */
     public function indexAction(Request $request)
     {
     	$loginVariables = $this->get('user.security')->loginFormInstance($request);
        $advertForm = $this->get('user_advert')->advertForm($request);

         return $this->render('AppBundle::annonce.html.twig', array(
            'form' => $registrationForm['form'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
     }

     public function advertForm(Request $request)
    {
        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        return array(
            'form' => $form->createView(),
        );
    }
 }