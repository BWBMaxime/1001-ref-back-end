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
        $product = $this->createProductFromForm($request->toArray());
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());}

    /**
     * Creates a product entity from a form and returns it
    */
    private function createProductFromForm($data){

        $product = new Product();

        $product->setName($data['name']);
        $product->setCategory($data['category']);
        $product->setDescription($data['description']);
        $product->setActive(false);
        $product->setNew(true);

        foreach ($data['variations'] as $variation) 
        {

            $newVariation = new Variation();

            $newVariation->setProduct($product);
            $newVariation->setContainer($variation['contenant']);
            $newVariation->setConditioning($variation['conditionnement']);
            $newVariation->setCapacity($variation['contenance']);
            $newVariation->setDealerPrice($variation['prixRevendeur']);
            $newVariation->setRestaurateurPrice($variation['prixRestaurateur']);
            $product->addVariation($newVariation);
        }

        foreach ($data['tags'] as $tag) 
        {
            $newTag = $this->getDoctrine()->getRepository(Tags::class)->findOneBy(['name'=>$tag['name']]);
            //$product->addTag($newTag);
        }

        $owner = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>1]);
        $product->setOwner($owner);

        return $product;
    }
}

