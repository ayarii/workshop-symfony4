<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/list", name="listProduct")
     */
    public function listProduct()
    {
        //return new Response("La liste des produits");
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render("product/listProducts.html.twig",array('listProducts'=>$products));

    }

    /**
     * @Route("/removeProduct/{id}", name="removeProduct")
     */

    public function deleteProduct($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return
         $this->redirectToRoute("listProduct");
    }

    /**
     * @Route("/showProduct/{id}", name="showProduct")
     */

    public function showProduct($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        //var_dump($product).die();
        return $this->render("product/showProduct.html.twig",array('product'=>$product));
   }
    /**
     * @Route("/addProduct", name="addProduct")
     */
   public function addProduct(Request $request){
       $product = new Product();
       $formProduct = $this->createForm(ProductType::class,$product);
       $formProduct->handleRequest($request);
       if($formProduct->isSubmitted()){
           $em=$this->getDoctrine()->getManager();
           $em->persist($product);
           $em->flush();
           return $this->redirectToRoute("listProduct");
       }

       return $this->render("product/addProduct.html.twig",array('formProduct'=>$formProduct->createView()));
   }

    /**
     * @Route("/updateProduct/{id}", name="updateProduct")
     */
    public function updateProduct(Request $request, $id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $formProduct = $this->createForm(ProductType::class,$product);

        $formProduct->handleRequest($request);

        if($formProduct->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("listProduct");
        }
        return $this->render("product/updateProduct.html.twig",array('formProduct'=>$formProduct->createView()));
    }
}
