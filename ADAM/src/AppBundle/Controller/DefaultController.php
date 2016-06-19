<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * Affichage de la liste des membres inscrits.
     *
     * @Route("/registered-list", name="registered_list")
     * @Template()
     */
    public function registeredListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('AdminBundle::registeredList.html.twig',
            array(
                'users' => $users
            ));
    }

    /**
     * Affichage de la page d'accueil
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);

        return $this->render('AppBundle::index.html.twig', $loginVariables);
    }

    /**
     * @Route("/security/", name="security")
     */
    public function securityAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        return $this->render('AppBundle:Security:confidentialite.html.twig', $loginVariables);
    }

    /**
     * @Route("/conditions-generales/", name="conditions_generales")
     */
    public function conditionAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        return $this->render('AppBundle:Security:condition.html.twig', $loginVariables);
    }
    
}
