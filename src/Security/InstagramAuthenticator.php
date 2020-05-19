<?php

namespace App\Security;

use App\Entity\User;
use App\Social\InstagramUserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class InstagramAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(InstagramUserProvider $provider)
    {   
        $this->provider = $provider;        
    }

    public function supports(Request $request)
    {
        return $request->query->get('code');
    }

    public function getCredentials(Request $request)
    {
        return [
            'code' => $request->query->get('code'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // dd($credentials);
         return $this->provider->loadUserFromInstagram($credentials['code']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse("NONO");
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
