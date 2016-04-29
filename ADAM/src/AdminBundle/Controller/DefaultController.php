<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
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

        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles()) &&
            ($action === "promote" && !in_array("ROLE_ADMIN", $user->getRoles())) || 
            ($action === "demote" && in_array("ROLE_ADMIN", $user->getRoles()))) {

            $command = 'fos:user:'.$action;

            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
               'command' => $command,
               'username' => $user->getUsername(),
               'role' => "ROLE_ADMIN",
            ));

            $output = new NullOutput();
            $application->run($input, $output);
        }

        return $this->redirect($this->generateUrl('registered_list'));;
    }
}
