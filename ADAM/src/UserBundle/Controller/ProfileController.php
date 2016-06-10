<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use UserBundle\Form\ProfileType;

/**
 * Profile controller.
 *
 * @Route("/profile")
 */

class ProfileController extends Controller
{
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }

    public function editAction()
    {
    	$user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm('UserBundle\Form\ProfileType', $user);
        return $this->render('UserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Affichage du formulaire d'inscription
     * Page de confirmation d'inscription
     *
     * @Route("update/", name="profile_update")
     * @Method("POST")
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm('UserBundle\Form\ProfileType', $user);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('UserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Affichage du formulaire de modification de mot de passe
     *
     * @Route("edit/password", name="profile_password_edit")
     * @Method("GET")
     */
    public function editPasswordAction()
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm('UserBundle\Form\PasswordType', $user);
        return $this->render('UserBundle:Profile:edit_password.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Validation du formulaire de modification de mot de passe
     *
     * @Route("update/password", name="profile_password_update")
     * @Method("POST")
     */
    public function updatePasswordAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm('UserBundle\Form\PasswordType', $user);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('UserBundle:Profile:edit_password.html.twig', array(
            'form' => $form->createView()
        ));
    }
}