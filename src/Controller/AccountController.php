<?php

namespace App\Controller;

use App\Entity\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
     * @Route("/profil/{id}", name="profil", methods={"GET"})
     */
    public function getProfilById(int $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if($user == null){
            return  new Response(
                "Cet utilisateur est non trouvé",
                Response::HTTP_NOT_FOUND,
                ['Access-Control-Allow-Origin' => '*']
            );
        } else {
            return new Response(
                serialize($user),
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            );
        }        
    }

    /**
     * @Route("/user/get/{id}", name="getUser", methods={"GET"})
     */
    public function getUserById(int $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $result = $this->getSerializer()->serialize($user, 'json');

        return new Response(
            $result,
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
     * @Route("/getCred", name="getCred", methods={"POST"})
     */
    public function getCredentials(Request $request): Response
    {
        // On décode les données envoyées, on les transforme en tableau
        $form = $request->toArray();

        $entityManager = $this->getDoctrine()->getManager();
        // On récupère l'utilisateur et ça envoie les données grâce au mail et à son mot de passe
        $user = $entityManager->getRepository(User::class)->findOneBy(['mail' => $form["mail"],'password' => $form["password"]]);
        // Si on ne le trouve pas, on répond un message d'erreur 
        if($user == null){
            $response = new Response(
                "Cet utilisateur n'existe pas",
                Response::HTTP_UNAUTHORIZED,
                ['Access-Control-Allow-Origin' => '*']
            );
        }
        // Sinon si le mail et mdp correspond : on envoie l'id et son role 
        else
        {
            $userId = $user->getId();
            $userRole = $user->getRole();
            $credentials = array('userId'=>$userId, 'userRole'=>$userRole);
            $response = new Response(
                json_encode($credentials),
                Response::HTTP_ACCEPTED,
                ['Access-Control-Allow-Origin' => '*']
            );
        }        
        return $response;
    }

    /**
     * Permet de transformer l'objet en format json
     */
    private function getSerializer(){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
            return $object->getId();
        },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        return new Serializer([$normalizer], [$encoder]);
    }
}
