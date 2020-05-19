<?php

namespace App\Controller;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $httpClient;

    /**
     * @param $httpClient
     */
    public function __construct($instagramClient, $instagramId, HttpClientInterface $httpClient)
    {
        $this->instagramClient = $instagramClient;
        $this->instagramId = $instagramId;
        $this->httpClient = $httpClient;
    }


    /**
     * @Route("/app", name="app")
     */
    public function index()
    {

        $user = $this->getUser()->getIgId();
        $token = $this->getUser()->getToken();


        // $query = sprintf("https://graph.instagram.com/%s/media?access_token=%s",
        // $user,$token);

        // $info = $this->httpClient->request('GET', $query, [
        //     'headers' => [
        //         'Authorization' => 'token '.$token
        //     ],
        // ]);


        $media = sprintf("https://graph.instagram.com/%s/media?fields=id,caption,media_url,timestamp&access_token=%s",
        $user,$token);

        $more = $this->httpClient->request('GET', $media, [
            'headers' => [
                'Authorization' => 'token '.$token
            ],

        ]);

        return $this->render('app/index.html.twig', [
            'contents' => $more->toArray() ,
            // 'media' => $info->toArray() ,
        ]);
    }
}
