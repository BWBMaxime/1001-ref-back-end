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
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $response = $this->getDoctrine()->getRepository(Product::class)->findBy($user);
        ?>
        <pre>
            <?= var_dump($response);?>
        </pre>
        <?php
        
        return new Response();
    }


}

