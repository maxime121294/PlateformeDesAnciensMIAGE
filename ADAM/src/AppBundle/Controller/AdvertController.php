<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Method("GET")
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
    public function showAction(Advert $advert)
    {
        $deleteForm = $this->createDeleteForm($advert);

        return $this->render('AppBundle:advert:show.html.twig', array(
            'advert' => $advert,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($advert);
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
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Advert entity.
     *
     * @Route("/{id}", name="annonce_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Advert $advert)
    {
        $form = $this->createDeleteForm($advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advert);
            $em->flush();
        }

        return $this->redirectToRoute('annonce_index');
    }

    /**
     * Creates a form to delete a Advert entity.
     *
     * @param Advert $advert The Advert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('annonce_delete', array('id' => $advert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
                $url = "../../bundles/front/images/uploads/" . $newname;
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

    /**
     * 
     * @Route("/browse", name="browse")
     * @Method({"GET", "POST"})
     */
    public function browseAction() 
    {
        $funcNum = $this->getUrlParam('CKEditorFuncNum');
        $fileUrl = '/path/to/file.txt';
        return new response ("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction( $funcNum, '$fileUrl'); window.close();</script>");
    }

    private function getUrlParam( String $paramName ) {
        $reParam = new RegExp( '(?:[\?&]|&)' + $paramName + '=([^&]+)', 'i' );
        $match = window.location.search.match( $reParam );

        return ( $match && $match.length > 1 ) ? $match[1] : null;
    }


    public function slugify( String $text , String $extension) {
        $text = basename($text,'.' . $extension);
        $slug = $this->get('cocur_slugify')->slugify($text);

        return $slug .'.'. $extension;
    }
}