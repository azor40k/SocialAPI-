<?php

namespace App\Social;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    public function loadUserFromInstagram(string $code)
    {
        $url = sprintf("https://api.instagram.com/oauth/access_token?client_id=%s&client_secret=%s&grant_type=%s&redirect_uri=%s&code=%s",
        $this->instagramClient,$this->instagramId,"authorization_code","https://axelcarandang.com/app",$code );
        
        $response = $this->httpClient->request('POST', $url, [
            'headers' =>[
                'Accept' => 'application/json'                
            ],
            'body' =>[
                'client_id' => $this->instagramClient,
                'client_secret' => $this->instagramId,
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'https://axelcarandang.com/app',
                'code' => $code
            ]            
        ]);

        

        $token = $response->toArray()['access_token'];
        $user = $response->toArray()['user_id'];

        $query = sprintf("https://graph.instagram.com/%s?fields=id,username&access_token=%s",
        $user,$token);

        $response = $this->httpClient->request('GET', $query, [
            'headers' => [
                'Authorization' => 'token '.$token
            ],
        ]);

        $data = $response->toArray();

        return new User($data);

    }
}