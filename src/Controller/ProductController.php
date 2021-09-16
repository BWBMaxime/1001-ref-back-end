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
     * @Route("/product/create", name="createproduct", methods={"POST"})
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
     * @Route("/product/{id}", name="getproduct", methods={"GET"})
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
                "L'utilisateur n'existe pas.",
                response::HTTP_NOT_FOUND
            );
        }

        // on récupère tous les produits de l'utilisateur courant
        $products = $user->getProducts();
        $this->dehydrate($products);

        // si l'utilisateur existe mais qu'il n'y a pas de produits
        if ($products->isEmpty()) {
            return new Response(
                "L'utilisateur courant n'a pas de produits.",
                Response::HTTP_NO_CONTENT
            );
        } else {
            $products = $this->getSerializer()->serialize($products, 'json');
            return new Response(
                $products,
                Response::HTTP_OK
            );
        }

    }

    /**
     * @Route("/variation/delete/{id}", name="deleteVariation", methods={"DELETE"})
     */
    public function deleteVariationById(int $id): Response
    {
        // on fait appel au gestionnaire d'entité de doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère notre objet et on le pointe par son id
        $variation = $em->getRepository(Variation::class)->find($id);
        
        // on vérifie si le produit existe
        if($variation != null){
            var_dump($variation->getProduct()->getName());

            // on indique a Doctrine que l'on souhaite supprimer un produit
            $em->remove($variation);

            // applique le changement
            $em->flush();

            return new Response(
                "Le produits a bien été supprimé.",
                Response::HTTP_OK
            );
        } else {

            return new Response(
                "Ce produits n'existe pas.",
                Response::HTTP_NOT_FOUND
            );
        }

    }


    /**
     * alleviate the datas sent to the front by setting products properties to null or an empty string
     */
    private function dehydrate($products){
        foreach ($products as $product){
            $product->setCategory("");
            $product->setDescription("");
            // $product->clearTag();
            $product->setOwner(null);
        }
        
        
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
