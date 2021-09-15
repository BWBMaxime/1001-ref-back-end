<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


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
     * @Route("/product/{id}", name="product", methods={"GET"})
     */
    public  function getProduct(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = $entityManager->getRepository(Product::class)->find($id);
        // dd($product);
        $result = $this->getSerializer()->serialize($product, 'json');
        // if ($product == null) {
        //    $response = new Response(
        //      $result,
        //      Response::HTTP_NOT_FOUND,
        //      ['Access-Control-Allow-Origin' => '*']
        //     );
        // }else {
            $response = new Response(
                $result,
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
               );
        // }
      
            return $response;
           
    }
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

    //Gets all of the info from an existing user
   




