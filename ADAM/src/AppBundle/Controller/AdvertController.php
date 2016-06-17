<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Advert;
use AppBundle\Form\AdvertType;

/**
 * Advert controller.
 *
 * @Route("/annonce")
 */
class AdvertController extends Controller
{
    /**
     * Lists all Advert entities.
     *
     * @Route("/", name="annonce_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);
        $em = $this->getDoctrine()->getManager();

        $adverts = $em->getRepository('AppBundle:Advert')->findBy(array(), array('updatedAt' => 'desc'));
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $adverts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5 /*limit per page*/
        );
        return $this->render('AppBundle:advert:index.html.twig', array(
            'pagination' => $pagination,
            'adverts' => $adverts,
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
    }

    /**
     * Creates a new Advert entity.
     *
     * @Route("/new", name="annonce_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $advert = new Advert();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\AdvertType', $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advert->setAuthor($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('annonce_show', array('id' => $advert->getId()));
        }

        return $this->render('AppBundle:advert:new.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Advert entity.
     *
     * @Route("/{id}", name="annonce_show")
     * @Method("GET")
     */
    public function showAction(Advert $advert, Request $request)
    {
        $loginVariables = $this->get('user.security')->loginFormInstance($request);

        return $this->render('AppBundle:advert:show.html.twig', array(
            'advert' => $advert,
            'last_username' => $loginVariables['last_username'],
            'error' => $loginVariables['error'],
            'csrf_token' => $loginVariables['csrf_token'],
        ));
    }

    /**
     * Displays a form to edit an existing Advert entity.
     *
     * @Route("/{id}/edit", name="annonce_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Advert $advert)
    {
        $editForm = $this->createForm('AppBundle\Form\AdvertType', $advert);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $advert->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('annonce_show', array('id' => $advert->getId()));
        }

        return $this->render('AppBundle:advert:edit.html.twig', array(
            'advert' => $advert,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Supprimer un post. 
     *
     *
     * @Route("/remove-{id}", name="advert_remove")
     * @Security("has_role('ROLE_USER')")
     */
    public function advertRemoveAction(Advert $advert)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert');  
            $em->remove($advert);
            $em->flush();
        return $this->redirectToRoute('annonce_index');
    }

    /**
     * 
     * @Route("/upload", name="upload")
     * @Method({"GET", "POST"})
     */
    public function uploadAction()
    {
        if (empty($_FILES['upload'])) {
            return new JsonResponse(['error'=>'No files found for upload.']);
        }
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
        $extension_upload = strtolower(  substr(  strrchr($_FILES['upload']['name'], '.')  ,1)  );

        if ( in_array($extension_upload,$extensions_valides) ) {
            $image = $_FILES['upload'];
            $name = $this->slugify($image['name'], $extension_upload);
            $success = null;
            
            $dateupload = new \DateTime();
            $dateupload = $dateupload->format('Y-m-d');
            $user = $this->getUser()->getId();

            $newname = $dateupload . '_' . $user . '_' . $name;
            $url = "bundles/front/images/uploads/" . $newname;

            if (move_uploaded_file($image['tmp_name'], $url))   {
                $funcNum = $_GET['CKEditorFuncNum'];
                $CKEditor = $_GET['CKEditor'] ;
                $url = $this->get('templating.helper.assets')->getUrl('bundles/front/images/uploads/') . $newname ;
                $message = "Le fichier a bien été intégré !";
                return new response ("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>");
            } else {
                $success = false;
            }

            // check and process based on successful status 
            if ($success === true) {
               // here for save data
            } elseif ($success === false) {
                $output = ['error'=>'Error while uploading images. Contact the system administrator'];
            } else {
                $output = ['error'=>'No files were processed.'];
            }

            // return a json encoded response for plugin to process successfully
            return new JsonResponse($output);
        }
        else{
            return new JsonResponse(['error'=>'Seuls les formats .jpg, .jpeg, .gif, .png sont acceptés.']);
        }
        
    }

    private function slugify( String $text , String $extension) {
        $text = basename($text,'.' . $extension);
        $slug = $this->get('cocur_slugify')->slugify($text);

        return $slug .'.'. $extension;
    }
}