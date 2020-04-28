<?php

namespace App\Social;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InstagramUserProvider
{
    private $instagramClient;
    private $instagramId;
    private $httpClient;

    /**
     * @param $instagramClient
     * @param $instagramId
     * @param $httpClient
     */
    public function __construct($instagramClient, $instagramId, HttpClientInterface $httpClient)
    {
        $this->instagramClient = $instagramClient;
        $this->instagramId = $instagramId;
        $this->httpClient = $httpClient;
    }

    public function loadUserFromInstagram(string $code, UrlGeneratorInterface $generator)
    {
        $url = sprintf("https://api.instagram.com/oauth/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s&code=%s",
        $this->instagramClient,
        $this->instagramId,
        $generator->generate('app', [], UrlGeneratorInterface::ABSOLUTE_URL ),
        $code );
        
        $response = $this->httpClient->request('POST', $url, [
            'headers' =>[
                'Accept' => 'application/json'                
            ]
        ]);

        $token = $response->toArray()['access_token'];

        $response = $this->httpClient->request('GET', "https://graph.instagram.com/me", [
            'headers' => [
                'Authorization' => 'token '.$token
            ]
        ]);

        dd($response->toArray());
    }
}