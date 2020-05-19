<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $instagramClient;

    /**
     * SecurityController constructor
     * @param $instagramClient
     */
    public function __construct($instagramClient)
    {
        $this->instagramClient = $instagramClient;
    }    

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/login/instagram", name="instagram")
     */
    public function instagram(UrlGeneratorInterface $generator)
    {
        $url = $generator->generate('app', [], UrlGeneratorInterface::ABSOLUTE_URL );
        return new RedirectResponse("https://api.instagram.com/oauth/authorize?client_id=$this->instagramClient&redirect_uri=$url&scope=user_profile,user_media&response_type=code");
    }
}
