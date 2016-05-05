<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);

        return $this->render('AppBundle::index.html.twig', $loginVariables);
    }

    /**
     * @Route("/register/", name="register")
     */
    public function registerAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        $registrationForm = $this->get('user.registration')->registrationForm($request);

        return $this->render('UserBundle:Registration:register.html.twig', array(
            'form' => $registrationForm['form'],
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
    }

    /**
     * @Route("/security/", name="security")
     */
    public function securityAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        return $this->render('AppBundle:Security:confidentialite.html.twig', $loginVariables);
    }
}
