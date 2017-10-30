<?php

namespace TweetProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TweetProxyBundle\Form\LoginForm;

/**
 * Class SecurityController
 * @package TweetProxyBundle\Controller
 *
 * @Route("/user")
 */
class SecurityController extends Controller
{

    /**
     * @Route("/login", name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);
    
        return $this->render(
            'TweetProxyBundle:Default/security:login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error,
                'meta' => ['title' => 'Sign in'],
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
    }
}
