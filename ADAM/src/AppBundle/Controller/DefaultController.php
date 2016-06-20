<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * Retourne l'url de la page précedente.
     */
    private function getRefererRoute()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        //look for the referer route
        $referer = $request->headers->get('referer');
        $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($lastPath);
        $route = $parameters['_route'];

        return $route;
    }

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
     * Affichage de la page des mentions légales
     *
     * @Route("/security/", name="security")
     */
    public function securityAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        $lastRoute = $this->generateUrl($this->getRefererRoute());

        return $this->render('AppBundle:Security:confidentialite.html.twig', array(
            'last_route' => $lastRoute,
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
    }

    /**
     * Affichage de la page des conditions générales d'utilisation
     *
     * @Route("/conditions-generales/", name="conditions_generales")
     */
    public function conditionAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        $lastRoute = $this->generateUrl($this->getRefererRoute());

        return $this->render('AppBundle:Security:condition.html.twig', array(
            'last_route' => $lastRoute,
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
    }
}
