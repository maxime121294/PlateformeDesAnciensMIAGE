<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
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
     * @Route("/search", name="search")
     */
    public function liveSearchAction(Request $request)
    {

        $loginVariables = $this->get('user.security')->loginFormInstance($request);

        $string = $request->request->get('searchAdvert');
        $advertsSearch = $this->getDoctrine()
                 ->getRepository('AppBundle:Advert')
                 ->getAdvertsForSearch($string);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $advertsSearch, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5 /*limit per page*/
        );
        return $this->render('AppBundle:advert:search.html.twig', array(
            'search' => $string,
            'pagination' => $pagination,
            'advertsSearch' => $advertsSearch,
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
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
