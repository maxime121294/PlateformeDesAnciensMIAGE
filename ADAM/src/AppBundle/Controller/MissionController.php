<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Mission;

/**
 * Mission controller.
 *
 * @Route("/mission")
 */
class MissionController extends Controller
{
	/**
     * Lists all Mission entities.
     *
     * @Route("/", name="mission_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $mission = $em->getRepository('AppBundle:Mission')->findOneByUser($user->getId());

        return $this->render('AppBundle:Mission:index.html.twig', array(
            'mission' => $mission,
        ));
    }

	/**
     * Display a form to create a new Mission entity.
     *
     * @Route("/new", name="mission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        $mission = new Mission();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\MissionType', $mission);

        return $this->render('AppBundle:Mission:new.html.twig', array(
            'mission' => $mission,
            'form'    => $form->createView(),
        ));
    }

    /**
     * Creates a new Mission entity.
     *
     * @Route("/create", name="mission_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $mission = new Mission();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\MissionType', $mission);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $mission->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('AppBundle:Mission:new.html.twig', array(
            'mission' => $mission,
            'form'    => $form->createView(),
        ));
    }

    /**
     * Display a form to edit a Mission entity.
     *
     * @Route("/{id}/edit", name="mission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($id);

        $form = $this->createForm('AppBundle\Form\MissionType', $mission);

        return $this->render('AppBundle:Mission:edit.html.twig', array(
            'mission' => $mission,
            'form'    => $form->createView(),
        ));
    }

    /**
     * Update a Mission entity.
     *
     * @Route("/{id}/update", name="mission_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($id);
        
        $form = $this->createForm('AppBundle\Form\MissionType', $mission);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('AppBundle:Mission:edit.html.twig', array(
            'mission' => $mission,
            'form'    => $form->createView(),
        ));
    }

    /**
     * Delete a Mission entity.
     *
     * @Route("/{id}/delete", name="mission_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($mission);
        $em->flush();

        return $this->redirectToRoute('mission_index');
    }
 }  