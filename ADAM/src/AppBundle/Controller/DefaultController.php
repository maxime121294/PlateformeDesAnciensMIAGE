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
     * Affichage du formulaire d'inscription
     * Page de confirmation d'inscription
     *
     * @Route("/register/", name="register")
     */
    public function registerAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        $registrationForm = $this->get('user.registration')->registrationForm($request);

        // Si on récupère un objet (de type RedirectResponse) depuis le RegistrationController : Le compte a bien été enregistré
        if (is_object($registrationForm)) {
            // On renvoit la vue de confirmedAction() en faisant appel au service
            return $this->get('user.registration')->confirmedAction();
        }
        // Sinon on récupère le formulaire d'inscription (sous forme de tableau) qu'on peut renvoyer avec les autres variables du formulaire de connexion (dans la navbar)
        else {
            return $this->render('UserBundle:Registration:register.html.twig', array(
                'form' => $registrationForm['form'],
                'last_username' => $loginVariables['last_username'],
                'error' => $loginVariables['error'],
                'csrf_token' => $loginVariables['csrf_token'],
            ));
        }
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
