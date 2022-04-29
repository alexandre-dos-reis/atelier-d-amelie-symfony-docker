<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\HttpToHttps\HttpToHttpsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private HttpToHttpsService $httpToHttpsService;

    public function __construct(HttpToHttpsService $httpToHttpsService)
    {
        $this->httpToHttpsService = $httpToHttpsService;
    }
    
    // /**
    //  * @Route("/test", name="test")
    //  */
    // public function index(): Response
    // {
    //     dd($this->httpToHttpsService->convert('http://test.fr'));
    //     return $this->render('accueil/index.html.twig');
    // }
}
