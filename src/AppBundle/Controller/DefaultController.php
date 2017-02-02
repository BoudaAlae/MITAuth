<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
    $router = $this->container->get('router');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($router->generate('admin'), 307);
        } 

        if ($authChecker->isGranted('ROLE_USER')) {
            return new RedirectResponse($router->generate('admin'), 307);
        }

        return new RedirectResponse($router->generate('fos_user_security_login'), 307);

    }

    /**
     * @Route("/user_home", name="user_home")
     */
    public function userAction(Request $request)
    {
        return $this->render('default/user.html.twig');
    }

    /**
     * @Route("/user_edit", name="user_edit")
     */
    public function editAction(Request $request)

    {

        $user = $this->getUser();
        var_dump($user);

        



        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */

        $dispatcher = $this->get('event_dispatcher');




        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);




        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */

        $formFactory = $this->get('fos_user.profile.form.factory');



        $form = $formFactory->createForm();

        $form->setData($user);



        $form->handleRequest($request);



        if ($form->isValid()) {

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */

            $userManager = $this->get('fos_user.user_manager');



            $event = new FormEvent($form, $request);

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);



            $userManager->updateUser($user);



            if (null === $response = $event->getResponse()) {

                //$url = $this->generateUrl('fos_user_profile_show');
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('message', 'Successfully updated');
                $url = $this->generateUrl('matrix_edi_viewUser');
                $response = new RedirectResponse($url);

            }



            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));



            return $response;

        }



        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(

            'form' => $form->createView()

        ));

    }


}
