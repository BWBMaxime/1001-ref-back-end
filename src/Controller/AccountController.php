<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{ 
    /**
     * @Route("/sign-in/", name="account", methods={"POST"})
     */
    public function createUser (Request $request): Response
    {

      $response = $this->json($request->getContent(),Response::HTTP_OK,[],[]);
      $response->headers->set('Access-Control-Allow-Origin', '*');

      return $response;
        
    }
}
