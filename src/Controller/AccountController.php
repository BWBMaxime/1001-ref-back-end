<?php

namespace App\Controller;

use App\Entity\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Serializable;

class AccountController extends AbstractController
{ 
    /**
     * @Route("/register", name="account", methods={"POST"})
     */
    public function createUser (Request $request): Response
    {

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        // On décode les données envoyées
        $form = $request->toArray();

        // on initialise le code de réponse
        $code = 409;

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findBy(['mail' => $form["mail"]]);
        
        // si l'utilisateur n'existe pas
        if($user == null){

            // On instancie un nouvel utilisateur
            $user = new User;
            // On hydrate l'objet user
            $user->hydrate($form);
        

            // on instancie un nouveau compte
            $account = new Account();
            // on hydrate l'objet account
            $account->hydrate($user);

            // On sauvegarde en base de donnée
            $entityManager->persist($account);
            $entityManager->flush();
            
            // on change le code de réponse
            $code = 201;
            $response = new Response('Ok', $code);

            // On retourne la confirmation
            return $response;

        }
        return $response;
    }

    /**
     * @Route("/user/update", name="updateUser", methods={"POST"})
     */
    public function updateUser (Request $request): Response
    {

        // On décode les données envoyées
        $form = $request->toArray();

        // on initialise le code de réponse
        $code = 409;

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find(['id' => $form['id']]);
        
        //Si l'utilisateur existe bien on le met a jour
        if($user != null){

            // On hydrate l'objet user
            $user->hydrate($form);


            // On sauvegarde la mise a jour
            $entityManager->persist($user);
            $entityManager->flush();
            
            // on change le code de réponse
            $code = 201;
            $response = new Response(
              'Utilisateur mit a jour',
              Response::HTTP_OK,
              ['Access-Control-Allow-Origin' => '*']
          );

            // On retourne la confirmation
            return $response;

        }
        return $response = new Response(
          "Utilisateur n'existe pas",
          Response::HTTP_NOT_FOUND,
          ['Access-Control-Allow-Origin' => '*']
      );
    }


    /**
     * @Route("/getUsers", name="getUsers", methods={"GET"})
     */
    public function getUsers(): Response
    {
        $response = $this->getDoctrine()->getRepository(User::class)->findAll();
        ?>
        <pre>
            <?= var_dump($response);?>
        </pre>
        <?php
        return new Response();
    }

    /**
     * @Route("/getCred", name="getCred", methods={"POST"})
     */
    public function getCredentials(Request $request): Response
    {
        // On décode les données envoyées
        $form = $request->toArray();

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['mail' => $form["mail"],'password' => $form["password"]]);

        if($user == null){
            $response = new Response(
                "Utilisateur n'existe pas",
                Response::HTTP_NOT_FOUND,
                ['Access-Control-Allow-Origin' => '*']
            );
                    
        }else
        {
            $userId = $user->getId();
            $response = new Response(
                $userId,
                Response::HTTP_ACCEPTED,
                ['Access-Control-Allow-Origin' => '*']
            );
        }        
        return $response;
    }
}
