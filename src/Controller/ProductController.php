<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\Entity\Variation;
use App\Entity\Tags;
use App\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product", methods={"POST"})
     */
    public function createProduct(Request $request): Response
    {
        $product = new Product();
        $product->hydrate($request->toArray(), $this->getDoctrine());

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());

    }


    /**
     * @Route("/getProducts/{id}", name="getProducts", methods={"GET"})
     */
    public function getProductsByUserId(int $id): Response
    {
        // on récupère un utilisateur par son id
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        // on vérifie si l'utilisateur existe
        if($user ==  null){
            return new Response(
                "L'utilisateur n'existe pas",
                response::HTTP_NOT_FOUND
            );
        }

        // on récupère tous les produits de l'utilisateur courant
        $products = $user->getProducts();

        // si l'utilisateur existe mais qu'il n'y a pas de produits
        if ($products->isEmpty()) {
            return new Response(
                "L'utilisateur courant n'a pas de produits",
                Response::HTTP_NO_CONTENT
            );
        } else {
            return new Response(
                serialize($products),
                Response::HTTP_OK
            );
        }

    }

}

