<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * Promouvoir ou destituer un membre en lui attribuant ou non le ROLE_ADMIN. Seul le SuperAdmin peut le faire.
     *
     * @Route("/{action}/{id}", name="role_toggle")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function roleToggleAction($id, $action)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
                ->findOneById($id);

            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }

        // Ne pas permettre de promouvoir un admin, de destituer un membre et aucun des deux pour un superadmin
        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles()) &&
            ($action === "promote" && !in_array("ROLE_ADMIN", $user->getRoles())) || 
            ($action === "demote" && in_array("ROLE_ADMIN", $user->getRoles()))) {

            // Commande de FOSUserBundle dynamique pour promote ou demote un membre
            $command = 'fos:user:'.$action;

            // Nécessaire pour appeler une commande existante depuis un controller
            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
               'command' => $command,
               'username' => $user->getUsername(),
               'role' => "ROLE_ADMIN",
            ));

            $output = new NullOutput();
            // On lance la commande
            $application->run($input, $output);
        }

        // Redirection vers la page de la liste des membres inscrits
        return $this->redirect($this->generateUrl('registered_list'));
    }

    /**
     * Bannir un membre. Il faut au moins être Admin pour bannir un utilisateur.
     * Un Admin ne peut bannir un SuperAdmin, et un SuperAdmin doit Destituer un Admin pour le bannir.
     *
     * @Route("/ban-{id}", name="user_ban")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function userBanAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
                ->findOneById($id);   

        if (!$user) {
                throw $this->createNotFoundException('User not found');
        }

        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles()) && !in_array("ROLE_ADMIN", $user->getRoles())){
            if($user->isLocked()) $user->setLocked(false);
            else $user->setLocked(true);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('registered_list'));

    }

    /**
     * Bannir un membre. Seul un SuperAdmin peut supprimer un utilisateur.
     *
     *
     * @Route("/remove-{id}", name="user_remove")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function userRemoveAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
                ->findOneById($id);   

        if (!$user) {
                throw $this->createNotFoundException('User not found');
        }

        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles())){
            $em->remove($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('registered_list'));

    }

    /**
     * Ajouter un membre. Il faut être au moins Admin pour ajouter un utilisateur par l'interface d'administration.
     *
     *
     * @Route("/add", name="user_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function userAddAction(Request $request)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $user->setEnabled(true);
        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('registered_list'));
    
        }else{
            return $this->render('UserBundle:Registration:register.html.twig', array('form' => $form->createView()));

        }
    }

}
